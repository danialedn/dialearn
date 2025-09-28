<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 relative overflow-hidden rtl">
        <!-- Animated background elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl animate-pulse" style="animation-delay: 4s"></div>
        </div>

        <!-- Floating particles -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-20 w-2 h-2 bg-purple-400 rounded-full animate-float"></div>
            <div class="absolute top-40 right-32 w-1 h-1 bg-blue-400 rounded-full animate-float" style="animation-delay: 1s"></div>
            <div class="absolute bottom-32 left-40 w-3 h-3 bg-indigo-400 rounded-full animate-float" style="animation-delay: 2s"></div>
            <div class="absolute bottom-20 right-20 w-2 h-2 bg-violet-400 rounded-full animate-float" style="animation-delay: 3s"></div>
        </div>

        <div class="relative z-10 max-w-6xl mx-auto p-4 sm:p-6 lg:p-8">
            <!-- Header -->
            <header class="relative z-10 p-6">
              <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4 space-x-reverse">
          <!-- Back Button -->
          <button 
            @click="goBack"
            class="w-12 h-12 bg-white/20 backdrop-blur-xl rounded-2xl flex items-center justify-center hover:bg-white/30 transition-all duration-300 group"
          >
            <svg class="w-6 h-6 text-white group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
          </button>
          <div class="w-12 h-12 bg-white/20 backdrop-blur-xl rounded-2xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
          </div>
          <div class="text-white">
            <h1 class="text-xl font-bold">آموزش دیابت</h1>
            <p class="text-blue-200 text-sm">یادگیری تعاملی</p>
          </div>
        </div>
        
        <!-- Game Status (Hearts and Score) - Only show in game mode -->
        <div v-if="isGameMode && props.gameStatus" class="flex items-center space-x-6 space-x-reverse">
          <!-- Hearts Display -->
          <div class="flex items-center space-x-2 space-x-reverse bg-white/20 backdrop-blur-xl rounded-2xl px-4 py-2">
            <div class="flex flex-col items-center space-y-1">
              <div class="flex space-x-1 space-x-reverse">
                <svg 
                  v-for="i in props.gameStatus.max_hearts" 
                  :key="i" 
                  class="w-5 h-5 transition-all duration-300"
                  :class="i <= props.gameStatus.hearts ? 'text-red-400' : 'text-gray-400'"
                  fill="currentColor" 
                  viewBox="0 0 24 24"
                >
                  <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
              </div>
              <!-- Heart Regeneration Timer -->
              <div v-if="props.gameStatus.hearts < props.gameStatus.max_hearts && props.gameStatus.heart_regeneration_time" 
                   class="text-xs text-blue-200 bg-blue-500/20 px-2 py-1 rounded-full">
                {{ formatTimeUntilNextHeart() }}
              </div>
            </div>
            <span class="text-white font-semibold">{{ props.gameStatus.hearts }}/{{ props.gameStatus.max_hearts }}</span>
          </div>
          
          <!-- Score Display -->
          <div class="flex items-center space-x-2 space-x-reverse bg-yellow-500/20 backdrop-blur-xl rounded-2xl px-4 py-2">
            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
            <span class="text-yellow-400 font-bold">{{ currentScore }}</span>
          </div>
          
          <!-- Mute Button -->
          <button 
            @click="toggleMute"
            class="w-12 h-12 bg-white/20 backdrop-blur-xl rounded-2xl flex items-center justify-center hover:bg-white/30 transition-all duration-300"
          >
            <svg v-if="!isMuted" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
            </svg>
            <svg v-else class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/>
            </svg>
          </button>
        </div>
              </div>
            </header>

            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-3xl mb-6 shadow-2xl">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold mb-6 bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">
                    مراحل آموزشی
                </h1>
                <p class="text-xl md:text-2xl text-blue-200 max-w-3xl mx-auto leading-relaxed">
                    مراحل آموزشی دیابت را تکمیل کنید و دانش خود را افزایش دهید
                </p>
            </div>

            <!-- No Hearts Message (Game Mode Only) -->
            <div v-if="isGameMode && props.gameStatus && props.gameStatus.hearts === 0" class="bg-red-500/20 backdrop-blur-xl rounded-3xl p-8 border border-red-400/30 max-w-md mx-auto mb-8">
              <div class="text-center">
                <div class="w-16 h-16 bg-red-500/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                  <svg class="w-8 h-8 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                  </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">قلب‌های شما تمام شده!</h3>
                <p class="text-red-200 mb-6">برای بازگشت قلب‌ها ۸ ساعت صبر کنید یا از فروشگاه دارو خریداری کنید.</p>
                <button 
                  @click="goToStore"
                  class="group bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-8 py-3 rounded-2xl font-semibold transition-all duration-300 transform hover:scale-[1.02] shadow-lg relative overflow-hidden"
                >
                  <div class="absolute inset-0 bg-gradient-to-r from-green-600/50 to-emerald-600/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                  <div class="relative z-10 flex items-center space-x-2 space-x-reverse">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7Z"/>
                    </svg>
                    <span>رفتن به فروشگاه</span>
                  </div>
                </button>
              </div>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="text-center py-16">
                <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-12 max-w-md mx-auto border border-white/20">
                    <div class="relative w-16 h-16 mx-auto mb-6">
                        <div class="absolute inset-0 rounded-full border-4 border-indigo-500/30"></div>
                        <div class="absolute inset-0 rounded-full border-4 border-indigo-500 border-t-transparent animate-spin"></div>
                    </div>
                    <p class="text-white text-lg font-medium">در حال بارگذاری مراحل...</p>
                </div>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="text-center py-16">
                <div class="bg-red-500/20 backdrop-blur-xl rounded-3xl p-12 max-w-md mx-auto border border-red-400/30">
                    <div class="w-16 h-16 bg-red-500/30 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-red-200 text-lg font-medium">{{ error }}</p>
                </div>
            </div>

            <!-- Stages Grid -->
            <div v-else class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="stage in props.stages"
                    :key="stage.id"
                    class="group bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-8 hover:shadow-3xl transition-all duration-500 cursor-pointer transform hover:scale-[1.02] border border-white/20 relative overflow-hidden"
                >
                    <!-- Background gradient overlay -->
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    
                    <div class="relative z-10">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-3 space-x-reverse">
                                <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <span class="text-white font-bold text-lg">{{ stage.level }}</span>
                                </div>
                                <h3 class="text-xl font-bold text-white">
                                    مرحله {{ stage.level }}
                                </h3>
                            </div>
                            
                            <div
                                v-if="stage.is_completed"
                                class="px-4 py-2 text-sm font-semibold text-green-400 bg-green-500/20 rounded-2xl border border-green-400/30"
                            >
                                ✓ تکمیل شده
                            </div>
                            <div
                                v-else
                                class="px-4 py-2 text-sm font-semibold text-blue-400 bg-blue-500/20 rounded-2xl border border-blue-400/30"
                            >
                                در انتظار
                            </div>
                        </div>

                        <!-- Title -->
                        <h4 class="text-2xl font-bold text-white mb-3">
                            {{ stage.name }}
                        </h4>

                        <!-- Description -->
                        <p class="text-blue-200 mb-6 leading-relaxed">
                            {{ stage.description }}
                        </p>

                        <!-- Stats -->
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between items-center">
                                <span class="text-blue-200 flex items-center">
                                    <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9.5 3A6.5 6.5 0 0 1 16 9.5c0 1.61-.59 3.09-1.56 4.23l.27.27h.79l5 5-1.5 1.5-5-5v-.79l-.27-.27A6.516 6.516 0 0 1 9.5 16 6.5 6.5 0 0 1 3 9.5 6.5 6.5 0 0 1 9.5 3m0 2C7 5 5 7 5 9.5S7 14 9.5 14 14 12 14 9.5 12 5 9.5 5z"/>
                                    </svg>
                                    سوال‌ها
                                </span>
                                <span class="text-white font-bold">{{ stage.questions_count }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-blue-200 flex items-center">
                                    <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    سطح دشواری
                                </span>
                                <div class="flex space-x-1 space-x-reverse">
                                    <svg
                                        v-for="i in 5"
                                        :key="i"
                                        class="w-4 h-4"
                                        :class="i <= stage.difficulty ? 'text-yellow-400' : 'text-gray-500'"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <div v-if="stage.is_completed" class="flex justify-between items-center">
                                <span class="text-blue-200 flex items-center">
                                    <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                    </svg>
                                    دقت
                                </span>
                                <span class="text-green-400 font-bold">{{ stage.accuracy }}%</span>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <button
                            @click="goToStage(stage.id)"
                            :disabled="!canAccessStage(stage) || (isGameMode && props.gameStatus && props.gameStatus.hearts === 0)"
                            class="group w-full relative overflow-hidden rounded-2xl transition-all duration-300 transform hover:scale-[1.02]"
                            :class="[
                                canAccessStage(stage) && !(isGameMode && props.gameStatus && props.gameStatus.hearts === 0)
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white shadow-lg hover:shadow-xl'
                                    : 'bg-gray-600/50 text-gray-400 cursor-not-allowed'
                            ]"
                        >
                            <div v-if="canAccessStage(stage)" class="absolute inset-0 bg-gradient-to-r from-indigo-600/50 to-purple-600/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="relative z-10 py-4 px-6 font-semibold text-lg flex items-center justify-center space-x-2 space-x-reverse">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path v-if="stage.is_completed" d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                    <path v-else d="M8 5v14l11-7z"/>
                                </svg>
                                <span>{{ stage.is_completed ? 'مشاهده نتایج' : 'شروع مرحله' }}</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { router } from '@inertiajs/vue3';

interface Stage {
    id: number;
    name: string;
    description: string;
    level: number;
    questions_count: number;
    difficulty: number;
    is_completed: boolean;
    accuracy: number;
}

interface Props {
  stages: Stage[];
  gameStatus?: {
    hearts: number;
    max_hearts: number;
    current_stage: number;
    total_stages: number;
    heart_regeneration_time: string | null;
    can_play: boolean;
    score?: number;
  };
}

const props = defineProps<Props>();
const loading = ref(true);
const error = ref<string | null>(null);

// Game-related state
const isMuted = ref(false);
const currentScore = ref(0);
const isGameMode = computed(() => {
  return window.location.pathname.startsWith('/game');
});



const canAccessStage = (stage: Stage): boolean => {
    if (stage.level === 1) return true;
    
    const previousStage = props.stages.find(s => s.level === stage.level - 1);
    return previousStage?.is_completed ?? false;
};

const goToStage = async (stageId: number) => {
  // Check current route to determine base path
  const currentPath = window.location.pathname;
  const basePath = currentPath.startsWith('/game') ? '/game' : '/quiz';
  
  // Find the stage to check if it's completed
  const stage = props.stages.find(s => s.id === stageId);
  
  // If in game mode, start a new game session
  if (basePath === '/game') {
    try {
      const response = await fetch('/api/game/start', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        credentials: 'same-origin'
      });
      
      if (response.ok) {
        // Navigate to the game stage
        router.visit(`${basePath}/stage/${stageId}`);
      } else {
        const errorData = await response.json();
        console.error('Failed to start game:', errorData.error);
        // Still navigate to show the error in the game component
        router.visit(`${basePath}/stage/${stageId}`);
      }
    } catch (error) {
      console.error('Error starting game:', error);
      router.visit(`${basePath}/stage/${stageId}`);
    }
  } else {
    // Regular quiz mode
    if (stage?.is_completed) {
      // For completed stages, go to stage with results view
      router.visit(`${basePath}/stage/${stageId}?view=results`);
    } else {
      // For incomplete stages, start the stage normally
      router.visit(`${basePath}/stage/${stageId}`);
    }
  }
};

const goBack = () => {
  router.visit('/dashboard');
};

const toggleMute = () => {
  isMuted.value = !isMuted.value;
  // Here you can add logic to mute/unmute background music
};

const goToStore = () => {
  router.visit('/store');
};

const fetchGameScore = async () => {
  if (!isGameMode.value) return;
  
  try {
    const response = await fetch('/api/game/current-question', {
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        'Accept': 'application/json',
      },
      credentials: 'same-origin'
    });
    
    if (response.ok) {
      const data = await response.json();
      currentScore.value = data.score || 0;
    }
  } catch {
    // If no active game session, score remains 0
    console.log('No active game session');
  }
};

const formatTimeUntilNextHeart = () => {
  if (!props.gameStatus?.heart_regeneration_time) return '';
  
  const now = new Date();
  const nextHeartTime = new Date(props.gameStatus.heart_regeneration_time);
  const diff = nextHeartTime.getTime() - now.getTime();
  
  if (diff <= 0) return 'آماده!';
  
  const hours = Math.floor(diff / (1000 * 60 * 60));
  const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
  
  if (hours > 0) {
    return `${hours}:${minutes.toString().padStart(2, '0')}`;
  } else {
    return `${minutes}م`;
  }
};

onMounted(() => {
    loading.value = false;
    fetchGameScore();
    
    // Listen for window focus to refresh game score when user returns
    const handleFocus = () => {
        if (isGameMode.value) {
            fetchGameScore();
        }
    };
    
    window.addEventListener('focus', handleFocus);
    
    // Cleanup event listener on unmount
    return () => {
        window.removeEventListener('focus', handleFocus);
    };
});
</script>

<style scoped>
/* Persian/RTL Support */
* {
  font-family: 'Vazirmatn', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.rtl {
  direction: rtl;
  text-align: right;
}

/* Ensure proper RTL spacing */
.space-x-reverse > * + * {
  margin-right: 0.5rem;
  margin-left: 0;
}

.space-x-2.space-x-reverse > * + * {
  margin-right: 0.5rem;
  margin-left: 0;
}

.space-x-3.space-x-reverse > * + * {
  margin-right: 0.75rem;
  margin-left: 0;
}

.space-x-4.space-x-reverse > * + * {
  margin-right: 1rem;
  margin-left: 0;
}

.space-x-6.space-x-reverse > * + * {
  margin-right: 1.5rem;
  margin-left: 0;
}

/* Persian text styling */
h1, h2, h3, h4, h5, h6, p, span, div {
  font-feature-settings: 'kern' 1, 'liga' 1;
  text-rendering: optimizeLegibility;
}

/* Better Persian number display */
.persian-numbers {
  font-variant-numeric: tabular-nums;
}

@keyframes float {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-20px);
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideInRight {
  from {
    opacity: 0;
    transform: translateX(30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes scaleIn {
  from {
    opacity: 0;
    transform: scale(0.9);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

.animate-fadeInUp {
  animation: fadeInUp 0.6s ease-out;
}

.animate-slideInRight {
  animation: slideInRight 0.8s ease-out;
}

.animate-scaleIn {
  animation: scaleIn 0.5s ease-out;
}

/* Custom hover effects */
.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}

.group:hover .group-hover\:opacity-100 {
  opacity: 1;
}
</style>