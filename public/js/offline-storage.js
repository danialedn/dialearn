// Offline Storage System using IndexedDB
class OfflineStorage {
    constructor() {
        this.dbName = 'DiaLearnOfflineDB';
        this.dbVersion = 1;
        this.db = null;
        this.init();
    }

    async init() {
        try {
            this.db = await this.openDatabase();
            console.log('Offline storage initialized successfully');
        } catch (error) {
            console.error('Failed to initialize offline storage:', error);
        }
    }

    openDatabase() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(this.dbName, this.dbVersion);

            request.onerror = () => reject(request.error);
            request.onsuccess = () => resolve(request.result);

            request.onupgradeneeded = (event) => {
                const db = event.target.result;

                // User Progress Store
                if (!db.objectStoreNames.contains('userProgress')) {
                    const userProgressStore = db.createObjectStore('userProgress', { keyPath: 'id', autoIncrement: true });
                    userProgressStore.createIndex('userId', 'userId', { unique: false });
                    userProgressStore.createIndex('timestamp', 'timestamp', { unique: false });
                }

                // Game Scores Store
                if (!db.objectStoreNames.contains('gameScores')) {
                    const gameScoresStore = db.createObjectStore('gameScores', { keyPath: 'id', autoIncrement: true });
                    gameScoresStore.createIndex('userId', 'userId', { unique: false });
                    gameScoresStore.createIndex('gameType', 'gameType', { unique: false });
                    gameScoresStore.createIndex('timestamp', 'timestamp', { unique: false });
                }

                // User Data Store
                if (!db.objectStoreNames.contains('userData')) {
                    const userDataStore = db.createObjectStore('userData', { keyPath: 'userId' });
                }

                // App Settings Store
                if (!db.objectStoreNames.contains('appSettings')) {
                    const appSettingsStore = db.createObjectStore('appSettings', { keyPath: 'key' });
                }

                // Sync Queue Store (for data to be synced when online)
                if (!db.objectStoreNames.contains('syncQueue')) {
                    const syncQueueStore = db.createObjectStore('syncQueue', { keyPath: 'id', autoIncrement: true });
                    syncQueueStore.createIndex('type', 'type', { unique: false });
                    syncQueueStore.createIndex('timestamp', 'timestamp', { unique: false });
                }
            };
        });
    }

    // User Progress Methods
    async saveUserProgress(progressData) {
        try {
            const transaction = this.db.transaction(['userProgress'], 'readwrite');
            const store = transaction.objectStore('userProgress');
            
            const data = {
                ...progressData,
                timestamp: new Date().toISOString(),
                synced: false
            };
            
            const result = await this.promisifyRequest(store.add(data));
            
            // Add to sync queue
            await this.addToSyncQueue('userProgress', data);
            
            return result;
        } catch (error) {
            console.error('Error saving user progress:', error);
            throw error;
        }
    }

    async getUserProgress(userId) {
        try {
            const transaction = this.db.transaction(['userProgress'], 'readonly');
            const store = transaction.objectStore('userProgress');
            const index = store.index('userId');
            
            const result = await this.promisifyRequest(index.getAll(userId));
            return result.sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));
        } catch (error) {
            console.error('Error getting user progress:', error);
            return [];
        }
    }

    // Game Scores Methods
    async saveGameScore(scoreData) {
        try {
            const transaction = this.db.transaction(['gameScores'], 'readwrite');
            const store = transaction.objectStore('gameScores');
            
            const data = {
                ...scoreData,
                timestamp: new Date().toISOString(),
                synced: false
            };
            
            const result = await this.promisifyRequest(store.add(data));
            
            // Add to sync queue
            await this.addToSyncQueue('gameScore', data);
            
            return result;
        } catch (error) {
            console.error('Error saving game score:', error);
            throw error;
        }
    }

    async getGameScores(userId, gameType = null) {
        try {
            const transaction = this.db.transaction(['gameScores'], 'readonly');
            const store = transaction.objectStore('gameScores');
            
            let result;
            if (gameType) {
                const index = store.index('gameType');
                result = await this.promisifyRequest(index.getAll(gameType));
                result = result.filter(score => score.userId === userId);
            } else {
                const index = store.index('userId');
                result = await this.promisifyRequest(index.getAll(userId));
            }
            
            return result.sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));
        } catch (error) {
            console.error('Error getting game scores:', error);
            return [];
        }
    }

    // User Data Methods
    async saveUserData(userData) {
        try {
            const transaction = this.db.transaction(['userData'], 'readwrite');
            const store = transaction.objectStore('userData');
            
            const data = {
                ...userData,
                lastUpdated: new Date().toISOString(),
                synced: false
            };
            
            const result = await this.promisifyRequest(store.put(data));
            
            // Add to sync queue
            await this.addToSyncQueue('userData', data);
            
            return result;
        } catch (error) {
            console.error('Error saving user data:', error);
            throw error;
        }
    }

    async getUserData(userId) {
        try {
            const transaction = this.db.transaction(['userData'], 'readonly');
            const store = transaction.objectStore('userData');
            
            const result = await this.promisifyRequest(store.get(userId));
            return result || null;
        } catch (error) {
            console.error('Error getting user data:', error);
            return null;
        }
    }

    // App Settings Methods
    async saveSetting(key, value) {
        try {
            const transaction = this.db.transaction(['appSettings'], 'readwrite');
            const store = transaction.objectStore('appSettings');
            
            const data = {
                key,
                value,
                timestamp: new Date().toISOString()
            };
            
            await this.promisifyRequest(store.put(data));
            return true;
        } catch (error) {
            console.error('Error saving setting:', error);
            return false;
        }
    }

    async getSetting(key) {
        try {
            const transaction = this.db.transaction(['appSettings'], 'readonly');
            const store = transaction.objectStore('appSettings');
            
            const result = await this.promisifyRequest(store.get(key));
            return result ? result.value : null;
        } catch (error) {
            console.error('Error getting setting:', error);
            return null;
        }
    }

    // Sync Queue Methods
    async addToSyncQueue(type, data) {
        try {
            const transaction = this.db.transaction(['syncQueue'], 'readwrite');
            const store = transaction.objectStore('syncQueue');
            
            const queueItem = {
                type,
                data,
                timestamp: new Date().toISOString(),
                attempts: 0
            };
            
            await this.promisifyRequest(store.add(queueItem));
        } catch (error) {
            console.error('Error adding to sync queue:', error);
        }
    }

    async getSyncQueue() {
        try {
            const transaction = this.db.transaction(['syncQueue'], 'readonly');
            const store = transaction.objectStore('syncQueue');
            
            const result = await this.promisifyRequest(store.getAll());
            return result.sort((a, b) => new Date(a.timestamp) - new Date(b.timestamp));
        } catch (error) {
            console.error('Error getting sync queue:', error);
            return [];
        }
    }

    async removeSyncQueueItem(id) {
        try {
            const transaction = this.db.transaction(['syncQueue'], 'readwrite');
            const store = transaction.objectStore('syncQueue');
            
            await this.promisifyRequest(store.delete(id));
        } catch (error) {
            console.error('Error removing sync queue item:', error);
        }
    }

    async updateSyncQueueItem(id, attempts) {
        try {
            const transaction = this.db.transaction(['syncQueue'], 'readwrite');
            const store = transaction.objectStore('syncQueue');
            
            const item = await this.promisifyRequest(store.get(id));
            if (item) {
                item.attempts = attempts;
                item.lastAttempt = new Date().toISOString();
                await this.promisifyRequest(store.put(item));
            }
        } catch (error) {
            console.error('Error updating sync queue item:', error);
        }
    }

    // Statistics Methods
    async getOfflineStats(userId) {
        try {
            const [progress, scores, userData] = await Promise.all([
                this.getUserProgress(userId),
                this.getGameScores(userId),
                this.getUserData(userId)
            ]);

            const totalGames = scores.length;
            const totalScore = scores.reduce((sum, score) => sum + (score.score || 0), 0);
            const averageScore = totalGames > 0 ? Math.round(totalScore / totalGames) : 0;
            const bestScore = totalGames > 0 ? Math.max(...scores.map(s => s.score || 0)) : 0;
            
            const recentGames = scores.slice(0, 5).map(score => ({
                gameType: score.gameType || 'quiz',
                score: score.score || 0,
                date: score.timestamp,
                percentage: score.percentage || 0
            }));

            return {
                totalGames,
                totalScore,
                averageScore,
                bestScore,
                recentGames,
                progressEntries: progress.length,
                lastActivity: scores.length > 0 ? scores[0].timestamp : null
            };
        } catch (error) {
            console.error('Error getting offline stats:', error);
            return {
                totalGames: 0,
                totalScore: 0,
                averageScore: 0,
                bestScore: 0,
                recentGames: [],
                progressEntries: 0,
                lastActivity: null
            };
        }
    }

    // Utility Methods
    promisifyRequest(request) {
        return new Promise((resolve, reject) => {
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }

    async clearAllData() {
        try {
            const stores = ['userProgress', 'gameScores', 'userData', 'appSettings', 'syncQueue'];
            const transaction = this.db.transaction(stores, 'readwrite');
            
            await Promise.all(stores.map(storeName => {
                const store = transaction.objectStore(storeName);
                return this.promisifyRequest(store.clear());
            }));
            
            console.log('All offline data cleared');
            return true;
        } catch (error) {
            console.error('Error clearing offline data:', error);
            return false;
        }
    }

    async getStorageSize() {
        try {
            if ('storage' in navigator && 'estimate' in navigator.storage) {
                const estimate = await navigator.storage.estimate();
                return {
                    used: estimate.usage,
                    available: estimate.quota,
                    usedMB: Math.round(estimate.usage / 1024 / 1024 * 100) / 100,
                    availableMB: Math.round(estimate.quota / 1024 / 1024 * 100) / 100
                };
            }
            return null;
        } catch (error) {
            console.error('Error getting storage size:', error);
            return null;
        }
    }
}

// Sync Manager for handling online/offline synchronization
class SyncManager {
    constructor(offlineStorage) {
        this.offlineStorage = offlineStorage;
        this.isOnline = navigator.onLine;
        this.syncInProgress = false;
        
        this.setupEventListeners();
    }

    setupEventListeners() {
        window.addEventListener('online', () => {
            this.isOnline = true;
            this.syncWhenOnline();
        });

        window.addEventListener('offline', () => {
            this.isOnline = false;
        });
    }

    async syncWhenOnline() {
        if (!this.isOnline || this.syncInProgress) return;

        this.syncInProgress = true;
        console.log('Starting sync process...');

        try {
            const syncQueue = await this.offlineStorage.getSyncQueue();
            
            for (const item of syncQueue) {
                try {
                    await this.syncItem(item);
                    await this.offlineStorage.removeSyncQueueItem(item.id);
                } catch (error) {
                    console.error('Failed to sync item:', item, error);
                    await this.offlineStorage.updateSyncQueueItem(item.id, item.attempts + 1);
                    
                    // Remove items that have failed too many times
                    if (item.attempts >= 3) {
                        await this.offlineStorage.removeSyncQueueItem(item.id);
                    }
                }
            }
            
            console.log('Sync process completed');
        } catch (error) {
            console.error('Sync process failed:', error);
        } finally {
            this.syncInProgress = false;
        }
    }

    async syncItem(item) {
        const { type, data } = item;
        
        switch (type) {
            case 'userProgress':
                return await this.syncUserProgress(data);
            case 'gameScore':
                return await this.syncGameScore(data);
            case 'userData':
                return await this.syncUserData(data);
            default:
                throw new Error(`Unknown sync type: ${type}`);
        }
    }

    async syncUserProgress(data) {
        // Implement API call to sync user progress
        const response = await fetch('/api/sync/user-progress', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error(`Failed to sync user progress: ${response.statusText}`);
        }

        return await response.json();
    }

    async syncGameScore(data) {
        // Implement API call to sync game score
        const response = await fetch('/api/sync/game-score', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error(`Failed to sync game score: ${response.statusText}`);
        }

        return await response.json();
    }

    async syncUserData(data) {
        // Implement API call to sync user data
        const response = await fetch('/api/sync/user-data', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error(`Failed to sync user data: ${response.statusText}`);
        }

        return await response.json();
    }
}

// Initialize offline storage and sync manager
let offlineStorage, syncManager;

if (typeof window !== 'undefined') {
    offlineStorage = new OfflineStorage();
    syncManager = new SyncManager(offlineStorage);
    
    // Make them globally available
    window.offlineStorage = offlineStorage;
    window.syncManager = syncManager;
}

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { OfflineStorage, SyncManager };
}