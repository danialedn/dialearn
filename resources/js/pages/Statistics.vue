<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 relative overflow-hidden">
    <!-- Animated background elements -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl animate-pulse"></div>
      <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl animate-pulse" style="animation-delay: 4s"></div>
    </div>

    <div class="relative z-10 p-4 md:p-8">
      <!-- Header -->
      <div class="flex items-center justify-between mb-8">
        <button 
          @click="goBack"
          class="flex items-center text-blue-200 hover:text-white transition-colors bg-white/10 backdrop-blur-sm rounded-2xl px-4 py-2 border border-white/20 hover:bg-white/20"
        >
          <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          بازگشت
        </button>
        
        <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">آمار بازی</h1>
        
        <div class="w-16"></div> <!-- Spacer -->
      </div>

      <!-- User Overview -->
      <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-6 md:p-8 mb-8 border border-white/20">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
          <div class="text-center group">
            <div class="w-16 h-16 bg-blue-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
              <svg class="w-8 h-8 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>
            </div>
            <div class="text-3xl font-bold text-white mb-2">{{ userStats.current_stage }}</div>
            <div class="text-blue-200 text-sm">مرحله فعلی</div>
          </div>
          <div class="text-center group">
            <div class="w-16 h-16 bg-green-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
              <svg class="w-8 h-8 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>
            </div>
            <div class="text-3xl font-bold text-white mb-2">{{ userStats.total_score }}</div>
            <div class="text-blue-200 text-sm">امتیاز کل</div>
          </div>
          <div class="text-center group">
            <div class="w-16 h-16 bg-purple-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
              <svg class="w-8 h-8 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
              </svg>
            </div>
            <div class="text-3xl font-bold text-white mb-2">{{ userStats.completed_stages }}</div>
            <div class="text-blue-200 text-sm">مراحل تکمیل شده</div>
          </div>
          <div class="text-center group">
            <div class="w-16 h-16 bg-red-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
              <svg class="w-8 h-8 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
              </svg>
            </div>
            <div class="text-3xl font-bold text-white mb-2">{{ userStats.hearts }}</div>
            <div class="text-blue-200 text-sm">قلب‌های باقی مانده</div>
          </div>
        </div>
      </div>

      <!-- Progress Chart -->
      <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-6 md:p-8 mb-8 border border-white/20">
        <h2 class="text-2xl font-bold text-white mb-6">پیشرفت مراحل</h2>
        <div class="space-y-4">
          <div 
            v-for="stage in stageProgress" 
            :key="stage.level"
            class="flex items-center justify-between p-4 bg-white/10 rounded-2xl border border-white/20 hover:bg-white/20 transition-all duration-300"
          >
            <div class="flex items-center">
              <div 
                :class="[
                  'w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold ml-4',
                  stage.completed ? 'bg-green-500' : stage.current ? 'bg-blue-500' : 'bg-gray-500'
                ]"
              >
                {{ stage.level }}
              </div>
              <div>
                <div class="font-medium text-white">مرحله {{ stage.level }}</div>
                <div class="text-blue-200 text-sm">{{ stage.title }}</div>
              </div>
            </div>
            
            <div class="text-left">
              <div v-if="stage.completed" class="text-green-400 font-medium">
                ✅ تکمیل شده
              </div>
              <div v-else-if="stage.current" class="text-blue-400 font-medium">
                🎯 در حال انجام
              </div>
              <div v-else class="text-gray-400">
                🔒 قفل شده
              </div>
              
              <div v-if="stage.score" class="text-blue-200 text-sm">
                امتیاز: {{ stage.score }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Games -->
      <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-6 md:p-8 mb-8 border border-white/20">
        <h2 class="text-2xl font-bold text-white mb-6">بازی‌های اخیر</h2>
        <div class="space-y-4">
          <div 
            v-for="game in recentGames" 
            :key="game.id"
            class="flex items-center justify-between p-4 bg-white/10 rounded-2xl border border-white/20 hover:bg-white/20 transition-all duration-300"
          >
            <div>
              <div class="font-medium text-white">مرحله {{ game.stage }}</div>
              <div class="text-blue-200 text-sm">{{ formatDate(game.played_at) }}</div>
            </div>
            
            <div class="text-left">
              <div 
                :class="[
                  'font-medium',
                  game.completed ? 'text-green-400' : 'text-red-400'
                ]"
              >
                {{ game.completed ? 'موفق' : 'ناموفق' }}
              </div>
              <div class="text-blue-200 text-sm">
                امتیاز: {{ game.score }}
              </div>
            </div>
          </div>
          
          <div v-if="recentGames.length === 0" class="text-center text-blue-200 py-8">
            هنوز بازی‌ای انجام نداده‌اید
          </div>
        </div>
      </div>

      <!-- Achievements -->
      <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-6 md:p-8 border border-white/20">
        <h2 class="text-2xl font-bold text-white mb-6">دستاوردها</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div 
            v-for="achievement in achievements" 
            :key="achievement.id"
            :class="[
              'p-6 rounded-2xl border-2 transition-all duration-300 hover:scale-105',
              achievement.unlocked 
                ? 'border-yellow-400/50 bg-yellow-500/20 shadow-lg shadow-yellow-500/20' 
                : 'border-white/20 bg-white/10 opacity-60'
            ]"
          >
            <div class="flex items-center mb-4">
              <div class="text-3xl ml-4">{{ achievement.icon }}</div>
              <div>
                <div class="font-medium text-white">{{ achievement.title }}</div>
                <div class="text-blue-200 text-sm">{{ achievement.description }}</div>
              </div>
            </div>
            
            <div v-if="achievement.progress" class="mt-4">
              <div class="flex justify-between text-blue-200 text-sm mb-2">
                <span>پیشرفت</span>
                <span>{{ achievement.progress.current }}/{{ achievement.progress.total }}</span>
              </div>
              <div class="w-full bg-white/20 rounded-full h-3 overflow-hidden">
                <div 
                  class="bg-gradient-to-r from-indigo-500 to-purple-600 h-3 rounded-full transition-all duration-500"
                  :style="{ width: (achievement.progress.current / achievement.progress.total * 100) + '%' }"
                >
                  <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-pulse"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

interface UserStats {
  current_stage: number
  total_score: number
  completed_stages: number
  hearts: number
}

interface StageProgress {
  level: number
  title: string
  completed: boolean
  current: boolean
  score?: number
}

interface RecentGame {
  id: number
  stage: number
  completed: boolean
  score: number
  played_at: string
}

interface Achievement {
  id: number
  title: string
  description: string
  icon: string
  unlocked: boolean
  progress?: {
    current: number
    total: number
  }
}

const userStats = ref<UserStats>({
  current_stage: 1,
  total_score: 0,
  completed_stages: 0,
  hearts: 6
})

const stageProgress = ref<StageProgress[]>([])
const recentGames = ref<RecentGame[]>([])
const achievements = ref<Achievement[]>([
  {
    id: 1,
    title: 'اولین قدم',
    description: 'اولین بازی خود را شروع کنید',
    icon: '🎯',
    unlocked: false
  },
  {
    id: 2,
    title: 'برنده',
    description: 'اولین مرحله را با موفقیت تمام کنید',
    icon: '🏆',
    unlocked: false
  },
  {
    id: 3,
    title: 'مداوم',
    description: '5 مرحله پشت سر هم تمام کنید',
    icon: '🔥',
    unlocked: false,
    progress: { current: 0, total: 5 }
  },
  {
    id: 4,
    title: 'استاد',
    description: '10 مرحله تمام کنید',
    icon: '👑',
    unlocked: false,
    progress: { current: 0, total: 10 }
  },
  {
    id: 5,
    title: 'امتیاز بالا',
    description: 'در یک بازی 70 امتیاز کسب کنید',
    icon: '⭐',
    unlocked: false
  },
  {
    id: 6,
    title: 'بی‌نقص',
    description: 'یک مرحله را بدون اشتباه تمام کنید',
    icon: '💎',
    unlocked: false
  }
])

const goBack = () => {
  router.visit('/dashboard')
}

const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('fa-IR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const loadUserStats = async () => {
  try {
    const response = await fetch('/api/game/statistics')
    const data = await response.json()
    
    if (data.success) {
      userStats.value = data.user_stats
      stageProgress.value = data.stage_progress
      recentGames.value = data.recent_games
      
      // Update achievements based on stats
      updateAchievements(data)
    }
  } catch (error) {
    console.error('Error loading user stats:', error)
    
    // Generate mock data for demonstration
    generateMockData()
  }
}

const updateAchievements = (data: any) => {
  achievements.value.forEach(achievement => {
    switch (achievement.id) {
      case 1: // اولین قدم
        achievement.unlocked = data.recent_games.length > 0
        break
      case 2: // برنده
        achievement.unlocked = data.user_stats.completed_stages > 0
        break
      case 3: // مداوم
        if (achievement.progress) {
          achievement.progress.current = Math.min(data.user_stats.completed_stages, 5)
          achievement.unlocked = data.user_stats.completed_stages >= 5
        }
        break
      case 4: // استاد
        if (achievement.progress) {
          achievement.progress.current = Math.min(data.user_stats.completed_stages, 10)
          achievement.unlocked = data.user_stats.completed_stages >= 10
        }
        break
      case 5: // امتیاز بالا
        achievement.unlocked = data.recent_games.some((game: any) => game.score >= 70)
        break
      case 6: // بی‌نقص
        achievement.unlocked = data.recent_games.some((game: any) => game.perfect)
        break
    }
  })
}

const generateMockData = () => {
  // Generate stage progress
  for (let i = 1; i <= 15; i++) {
    stageProgress.value.push({
      level: i,
      title: `مرحله ${i}`,
      completed: i < userStats.value.current_stage,
      current: i === userStats.value.current_stage,
      score: i < userStats.value.current_stage ? Math.floor(Math.random() * 30) + 40 : undefined
    })
  }
  
  // Generate recent games
  for (let i = 0; i < 5; i++) {
    recentGames.value.push({
      id: i + 1,
      stage: Math.floor(Math.random() * userStats.value.current_stage) + 1,
      completed: Math.random() > 0.3,
      score: Math.floor(Math.random() * 70) + 10,
      played_at: new Date(Date.now() - Math.random() * 7 * 24 * 60 * 60 * 1000).toISOString()
    })
  }
}

onMounted(() => {
  loadUserStats()
})
</script>