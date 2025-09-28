/**
 * Offline Questions Manager
 * Manages questions storage and retrieval using IndexedDB
 */

class OfflineQuestionsManager {
    constructor() {
        this.dbName = 'DiaLearnOfflineDB';
        this.dbVersion = 1;
        this.questionsStore = 'questions';
        this.stagesStore = 'stages';
        this.userAnswersStore = 'userAnswers';
        this.db = null;
    }

    /**
     * Initialize IndexedDB
     */
    async init() {
        // Prevent double initialization
        if (this.db) {
            return this.db;
        }

        // Prevent concurrent initialization
        if (this.initPromise) {
            return this.initPromise;
        }

        this.initPromise = new Promise((resolve, reject) => {
            const request = indexedDB.open(this.dbName, this.dbVersion);

            request.onerror = () => {
                console.error('خطا در باز کردن پایگاه داده آفلاین:', request.error);
                this.initPromise = null;
                reject(request.error);
            };

            request.onsuccess = () => {
                this.db = request.result;
                console.log('پایگاه داده آفلاین با موفقیت باز شد');
                
                // Handle database close/error events
                this.db.onclose = () => {
                    console.log('پایگاه داده آفلاین بسته شد');
                    this.db = null;
                    this.initPromise = null;
                };

                this.db.onerror = (event) => {
                    console.error('خطا در پایگاه داده آفلاین:', event);
                };

                resolve(this.db);
            };

            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                console.log('در حال ایجاد/به‌روزرسانی پایگاه داده آفلاین...');

                // Questions store
                if (!db.objectStoreNames.contains(this.questionsStore)) {
                    const questionsStore = db.createObjectStore(this.questionsStore, { keyPath: 'id' });
                    questionsStore.createIndex('stage_id', 'stage_id', { unique: false });
                    questionsStore.createIndex('difficulty', 'difficulty', { unique: false });
                    questionsStore.createIndex('stage_level', 'stage_level', { unique: false });
                    console.log('Object store questions ایجاد شد');
                }

                // Stages store
                if (!db.objectStoreNames.contains(this.stagesStore)) {
                    const stagesStore = db.createObjectStore(this.stagesStore, { keyPath: 'id' });
                    stagesStore.createIndex('level', 'level', { unique: false });
                    console.log('Object store stages ایجاد شد');
                }

                // User answers store (for offline mode)
                if (!db.objectStoreNames.contains(this.userAnswersStore)) {
                    const userAnswersStore = db.createObjectStore(this.userAnswersStore, { keyPath: 'id', autoIncrement: true });
                    userAnswersStore.createIndex('question_id', 'question_id', { unique: false });
                    userAnswersStore.createIndex('stage_id', 'stage_id', { unique: false });
                    userAnswersStore.createIndex('timestamp', 'timestamp', { unique: false });
                    console.log('Object store userAnswers ایجاد شد');
                }

                console.log('پایگاه داده آفلاین ایجاد شد');
            };
        });

        return this.initPromise;
    }

    /**
     * Store all questions from API
     */
    async storeAllQuestions() {
        try {
            console.log('در حال دریافت سوالات از سرور...');
            const response = await fetch('/api/questions/offline/all');
            
            if (!response.ok) {
                throw new Error(`خطا در دریافت سوالات: ${response.status}`);
            }

            const data = await response.json();
            
            if (!data.success) {
                throw new Error('دریافت سوالات ناموفق بود');
            }

            // Store questions
            await this.storeQuestions(data.questions);
            
            // Extract and store unique stages
            const stages = this.extractStagesFromQuestions(data.questions);
            await this.storeStages(stages);

            console.log(`${data.total_count} سوال با موفقیت ذخیره شد`);
            return data.total_count;
        } catch (error) {
            console.error('خطا در ذخیره‌سازی سوالات:', error);
            throw error;
        }
    }

    /**
     * Ensure database is initialized
     */
    async ensureInitialized() {
        if (!this.db && !this.initPromise) {
            await this.init();
        } else if (this.initPromise) {
            await this.initPromise;
        }
        return this.db;
    }

    /**
     * Validate that required object stores exist
     */
    validateObjectStores(storeNames) {
        if (!this.db) {
            throw new Error('دیتابیس مقداردهی نشده است');
        }
        for (const storeName of storeNames) {
            if (!this.db.objectStoreNames.contains(storeName)) {
                throw new Error(`Object store '${storeName}' یافت نشد`);
            }
        }
    }

    /**
     * Check if database schema is properly initialized
     */
    isDatabaseSchemaReady() {
        if (!this.db) {
            return false;
        }
        
        const requiredStores = [this.questionsStore, this.stagesStore, this.userAnswersStore];
        return requiredStores.every(store => this.db.objectStoreNames.contains(store));
    }

    /**
     * Store questions in IndexedDB
     */
    async storeQuestions(questions) {
        await this.ensureInitialized();
        this.validateObjectStores([this.questionsStore]);
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.questionsStore], 'readwrite');
            const store = transaction.objectStore(this.questionsStore);

            transaction.oncomplete = () => {
                console.log('سوالات با موفقیت ذخیره شدند');
                resolve();
            };

            transaction.onerror = () => {
                console.error('خطا در ذخیره‌سازی سوالات:', transaction.error);
                reject(transaction.error);
            };

            questions.forEach(question => {
                store.put(question);
            });
        });
    }

    /**
     * Store stages in IndexedDB
     */
    async storeStages(stages) {
        await this.ensureInitialized();
        this.validateObjectStores([this.stagesStore]);
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.stagesStore], 'readwrite');
            const store = transaction.objectStore(this.stagesStore);

            transaction.oncomplete = () => {
                console.log('مراحل با موفقیت ذخیره شدند');
                resolve();
            };

            transaction.onerror = () => {
                console.error('خطا در ذخیره‌سازی مراحل:', transaction.error);
                reject(transaction.error);
            };

            stages.forEach(stage => {
                store.put(stage);
            });
        });
    }

    /**
     * Extract unique stages from questions
     */
    extractStagesFromQuestions(questions) {
        const stagesMap = new Map();
        
        questions.forEach(question => {
            if (!stagesMap.has(question.stage_id)) {
                stagesMap.set(question.stage_id, {
                    id: question.stage_id,
                    name: question.stage_name,
                    level: question.stage_level,
                    cached_at: question.cached_at
                });
            }
        });

        return Array.from(stagesMap.values());
    }

    /**
     * Get all questions from IndexedDB
     */
    async getAllQuestions() {
        await this.ensureInitialized();
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.questionsStore], 'readonly');
            const store = transaction.objectStore(this.questionsStore);
            const request = store.getAll();

            request.onsuccess = () => {
                resolve(request.result);
            };

            request.onerror = () => {
                reject(request.error);
            };
        });
    }

    /**
     * Get questions by stage ID
     */
    async getQuestionsByStage(stageId) {
        await this.ensureInitialized();
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.questionsStore], 'readonly');
            const store = transaction.objectStore(this.questionsStore);
            const index = store.index('stage_id');
            const request = index.getAll(stageId);

            request.onsuccess = () => {
                resolve(request.result);
            };

            request.onerror = () => {
                reject(request.error);
            };
        });
    }

    /**
     * Get questions by difficulty
     */
    async getQuestionsByDifficulty(difficulty) {
        await this.ensureInitialized();
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.questionsStore], 'readonly');
            const store = transaction.objectStore(this.questionsStore);
            const index = store.index('difficulty');
            const request = index.getAll(difficulty);

            request.onsuccess = () => {
                resolve(request.result);
            };

            request.onerror = () => {
                reject(request.error);
            };
        });
    }

    /**
     * Get all stages from IndexedDB
     */
    async getAllStages() {
        await this.ensureInitialized();
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.stagesStore], 'readonly');
            const store = transaction.objectStore(this.stagesStore);
            const request = store.getAll();

            request.onsuccess = () => {
                resolve(request.result.sort((a, b) => a.level - b.level));
            };

            request.onerror = () => {
                reject(request.error);
            };
        });
    }

    /**
     * Store user answer offline
     */
    async storeUserAnswer(questionId, stageId, selectedAnswer, isCorrect) {
        await this.ensureInitialized();
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.userAnswersStore], 'readwrite');
            const store = transaction.objectStore(this.userAnswersStore);

            const answer = {
                question_id: questionId,
                stage_id: stageId,
                selected_answer: selectedAnswer,
                is_correct: isCorrect,
                timestamp: new Date().toISOString(),
                synced: false
            };

            const request = store.add(answer);

            request.onsuccess = () => {
                console.log('پاسخ کاربر ذخیره شد');
                resolve(request.result);
            };

            request.onerror = () => {
                console.error('خطا در ذخیره پاسخ:', request.error);
                reject(request.error);
            };
        });
    }

    /**
     * Get unsynced user answers
     */
    async getUnsyncedAnswers() {
        await this.ensureInitialized();
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.userAnswersStore], 'readonly');
            const store = transaction.objectStore(this.userAnswersStore);
            const request = store.getAll();

            request.onsuccess = () => {
                const unsyncedAnswers = request.result.filter(answer => !answer.synced);
                resolve(unsyncedAnswers);
            };

            request.onerror = () => {
                reject(request.error);
            };
        });
    }

    /**
     * Mark answers as synced
     */
    async markAnswersAsSynced(answerIds) {
        await this.ensureInitialized();
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.userAnswersStore], 'readwrite');
            const store = transaction.objectStore(this.userAnswersStore);

            let completed = 0;
            const total = answerIds.length;

            if (total === 0) {
                resolve();
                return;
            }

            answerIds.forEach(id => {
                const getRequest = store.get(id);
                
                getRequest.onsuccess = () => {
                    const answer = getRequest.result;
                    if (answer) {
                        answer.synced = true;
                        const putRequest = store.put(answer);
                        
                        putRequest.onsuccess = () => {
                            completed++;
                            if (completed === total) {
                                resolve();
                            }
                        };
                        
                        putRequest.onerror = () => {
                            reject(putRequest.error);
                        };
                    } else {
                        completed++;
                        if (completed === total) {
                            resolve();
                        }
                    }
                };
                
                getRequest.onerror = () => {
                    reject(getRequest.error);
                };
            });
        });
    }

    /**
     * Check if questions are cached
     */
    async isQuestionsCached() {
        try {
            await this.ensureInitialized();
            
            // Check if database schema is ready
            if (!this.isDatabaseSchemaReady()) {
                console.log('Database schema is not ready, questions not cached');
                return false;
            }
            
            // Check if database is initialized
            if (!this.db) {
                return false;
            }
            
            // Use a simple count query instead of getAllQuestions to avoid loading all data
            return new Promise((resolve, reject) => {
                const transaction = this.db.transaction([this.questionsStore], 'readonly');
                const store = transaction.objectStore(this.questionsStore);
                const countRequest = store.count();

                countRequest.onsuccess = () => {
                    resolve(countRequest.result > 0);
                };

                countRequest.onerror = () => {
                    console.debug('بررسی cache سوالات در این صفحه امکان‌پذیر نیست');
                    resolve(false);
                };
            });
        } catch (error) {
            console.debug('بررسی cache سوالات در این صفحه امکان‌پذیر نیست');
            return false;
        }
    }

    /**
     * Get cache info
     */
    async getCacheInfo() {
        try {
            const questions = await this.getAllQuestions();
            const stages = await this.getAllStages();
            const unsyncedAnswers = await this.getUnsyncedAnswers();

            return {
                questionsCount: questions.length,
                stagesCount: stages.length,
                unsyncedAnswersCount: unsyncedAnswers.length,
                lastCached: questions.length > 0 ? questions[0].cached_at : null
            };
        } catch (error) {
            console.error('خطا در دریافت اطلاعات cache:', error);
            return {
                questionsCount: 0,
                stagesCount: 0,
                unsyncedAnswersCount: 0,
                lastCached: null
            };
        }
    }

    /**
     * Clear all cached data
     */
    async clearCache() {
        await this.ensureInitialized();
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.questionsStore, this.stagesStore, this.userAnswersStore], 'readwrite');
            
            transaction.oncomplete = () => {
                console.log('Cache پاک شد');
                resolve();
            };

            transaction.onerror = () => {
                console.error('خطا در پاک کردن cache:', transaction.error);
                reject(transaction.error);
            };

            const questionsStore = transaction.objectStore(this.questionsStore);
            const stagesStore = transaction.objectStore(this.stagesStore);

            questionsStore.clear();
            stagesStore.clear();
        });
    }
}

// Global instance
window.offlineQuestionsManager = new OfflineQuestionsManager();

// Auto-initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', async () => {
    try {
        await window.offlineQuestionsManager.init();
        console.log('مدیریت سوالات آفلاین آماده است');
    } catch (error) {
        // Silently fail if offline questions manager can't initialize
        // This is expected on pages that don't need offline functionality
        console.debug('مدیریت سوالات آفلاین در این صفحه فعال نیست');
    }
});