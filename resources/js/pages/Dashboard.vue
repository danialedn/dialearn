<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 relative overflow-hidden">
    <!-- Animated background elements -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl animate-pulse"></div>
      <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl animate-pulse" style="animation-delay: 4s"></div>
    </div>

    <!-- Header -->
    <header class="relative z-10 bg-white/10 backdrop-blur-xl border-b border-white/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-5">
          <div class="flex items-center space-x-5 space-x-reverse">
            <div class="relative">
              <div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-indigo-500 to-purple-600 overflow-hidden border-2 border-white/30 shadow-lg">
                <img 
                  v-if="user.profile_picture" 
                  :src="user.profile_picture" 
                  alt="Profile" 
                  class="w-full h-full object-cover"
                >
                <div v-else class="w-full h-full flex items-center justify-center">
                  <span class="text-white font-bold text-lg">{{ user.name?.charAt(0) }}</span>
                </div>
              </div>
              <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-400 rounded-full border-2 border-white animate-pulse"></div>
            </div>
            <div class="min-w-0 ml-2">
              <h2 class="text-xl font-bold text-white mb-1 truncate">سلام {{ user.name || 'کاربر' }}! 👋</h2>
              <p class="text-blue-200 text-sm truncate">{{ user.email || '@' + user.username }}</p>
            </div>
          </div>
          <div class="flex items-center space-x-5 space-x-reverse">
            <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 border border-white/30">
              <div class="flex items-center space-x-2 space-x-reverse">
                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                <span class="text-white font-semibold">{{ userScore }}</span>
              </div>
            </div>
            <button class="bg-white/20 backdrop-blur-sm rounded-xl p-3 border border-white/30 hover:bg-white/30 transition-all duration-300">
              <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Welcome Section -->
      <div class="text-center mb-16 animate-fadeInUp">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-3xl mb-8 shadow-2xl animate-float hover:scale-110 transition-transform duration-300 animate-glow">
          <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
          </svg>
        </div>
        <h1 class="text-6xl md:text-7xl font-bold mb-8 bg-gradient-to-r from-white via-blue-100 to-purple-200 bg-clip-text text-transparent animate-pulse animate-shimmer">
          دیالرن
        </h1>
        <p class="text-xl md:text-2xl text-blue-200 max-w-4xl mx-auto leading-relaxed mb-6">
          پلتفرم هوشمند آموزش دیابت - مسیر شما به سوی زندگی سالم‌تر
        </p>
        <div class="flex justify-center space-x-2 space-x-reverse">
          <div class="w-3 h-3 bg-purple-400 rounded-full animate-bounce animate-heartbeat"></div>
          <div class="w-3 h-3 bg-blue-400 rounded-full animate-bounce animate-heartbeat" style="animation-delay: 0.1s"></div>
          <div class="w-3 h-3 bg-indigo-400 rounded-full animate-bounce animate-heartbeat" style="animation-delay: 0.2s"></div>
        </div>
      </div>

      <!-- Menu Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 max-w-7xl mx-auto mb-20">
        <!-- Start Learning - Main CTA -->
        <div 
          @click="navigateTo('/game')"
          class="group md:col-span-2 lg:col-span-3 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-3xl shadow-2xl hover:shadow-3xl transition-all duration-700 cursor-pointer transform hover:scale-[1.03] p-10 text-center relative overflow-hidden animate-scaleIn animate-glow hover:animate-shimmer"
        >
          <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/50 to-purple-600/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
          <div class="relative z-10">
            <div class="w-20 h-20 bg-white/20 rounded-3xl flex items-center justify-center mx-auto mb-6 backdrop-blur-sm border border-white/30 group-hover:scale-110 transition-transform duration-300">
              <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M8 5v14l11-7z"/>
              </svg>
            </div>
            <h3 class="text-3xl font-bold text-white mb-4">شروع بازی </h3>
            <p class="text-blue-100 text-lg">ورود به بازی آموزشی تعاملی دیابت</p>
          </div>
        </div>

        <!-- Game Introduction -->
        <div 
          @click="navigateTo('/game-intro')"
          class="group bg-white/10 backdrop-blur-xl rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-700 cursor-pointer transform hover:scale-[1.08] p-8 text-center border border-white/20 hover:bg-white/20 hover:border-blue-400/50 animate-fadeInUp"
          style="animation-delay: 0.1s"
        >
          <div class="w-20 h-20 bg-blue-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-500 animate-heartbeat">
            <svg class="w-10 h-10 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-white mb-3 group-hover:text-blue-300 transition-colors duration-300">راهنمای بازی</h3>
          <p class="text-blue-200 text-base group-hover:text-white/90 transition-colors duration-300">آشنایی با قوانین و نحوه استفاده</p>
        </div>

        <!-- Profile Edit -->
        <div 
          @click="navigateTo('/profile/edit')"
          class="group bg-white/10 backdrop-blur-xl rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-700 cursor-pointer transform hover:scale-[1.08] p-8 text-center border border-white/20 hover:bg-white/20 hover:border-purple-400/50 animate-fadeInUp"
          style="animation-delay: 0.2s"
        >
          <div class="w-20 h-20 bg-purple-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-500">
            <svg class="w-10 h-10 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-white mb-3 group-hover:text-purple-300 transition-colors duration-300">پروفایل من</h3>
          <p class="text-blue-200 text-base group-hover:text-white/90 transition-colors duration-300">ویرایش اطلاعات شخصی</p>
        </div>

        <!-- Store -->
        <div 
          @click="navigateTo('/store')"
          class="group bg-white/10 backdrop-blur-xl rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-700 cursor-pointer transform hover:scale-[1.08] p-8 text-center border border-white/20 hover:bg-white/20 hover:border-yellow-400/50 animate-fadeInUp"
          style="animation-delay: 0.3s"
        >
          <div class="w-20 h-20 bg-yellow-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-500">
            <svg class="w-10 h-10 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
              <path d="M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7Z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-white mb-3 group-hover:text-yellow-300 transition-colors duration-300">فروشگاه</h3>
          <p class="text-blue-200 text-base group-hover:text-white/90 transition-colors duration-300">خرید آیتم‌ها و ارتقاء</p>
        </div>

        <!-- About Us -->
        <div 
          @click="navigateTo('/about')"
          class="group bg-white/10 backdrop-blur-xl rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-700 cursor-pointer transform hover:scale-[1.08] p-8 text-center border border-white/20 hover:bg-white/20 hover:border-green-400/50 animate-fadeInUp"
          style="animation-delay: 0.4s"
        >
          <div class="w-20 h-20 bg-green-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-500">
            <svg class="w-10 h-10 text-green-400" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-white mb-3 group-hover:text-green-300 transition-colors duration-300">درباره ما</h3>
          <p class="text-blue-200 text-base group-hover:text-white/90 transition-colors duration-300">اطلاعات تیم سازنده</p>
        </div>

        <!-- Statistics -->
        <div 
          @click="navigateTo('/statistics')"
          class="group bg-white/10 backdrop-blur-xl rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-700 cursor-pointer transform hover:scale-[1.08] p-8 text-center border border-white/20 hover:bg-white/20 hover:border-indigo-400/50 animate-fadeInUp"
          style="animation-delay: 0.5s"
        >
          <div class="w-20 h-20 bg-indigo-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-500">
            <svg class="w-10 h-10 text-indigo-400" fill="currentColor" viewBox="0 0 24 24">
              <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-white mb-3 group-hover:text-indigo-300 transition-colors duration-300">آمار پیشرفت</h3>
          <p class="text-blue-200 text-base group-hover:text-white/90 transition-colors duration-300">نمودار عملکرد شما</p>
        </div>
      </div>

      <!-- Progress Section -->
      <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-10 border border-white/20 animate-slideInRight">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-bold text-white mb-4 bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">پیشرفت شما</h2>
          <p class="text-blue-200 text-lg">نمای کلی از عملکرد و دستاوردهای شما</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-10">
          <div class="text-center group animate-fadeInUp" style="animation-delay: 0.6s">
            <div class="w-24 h-24 bg-blue-500/20 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:bg-blue-500/30 transition-all duration-300 border border-blue-400/30">
              <svg class="w-12 h-12 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
              </svg>
            </div>
            <div class="text-5xl font-bold text-white mb-3 animate-pulse">{{ completedStages }}</div>
            <div class="text-blue-200 text-lg">مراحل تکمیل شده</div>
          </div>
          
          <div class="text-center group animate-fadeInUp" style="animation-delay: 0.7s">
            <div class="w-24 h-24 bg-yellow-500/20 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:bg-yellow-500/30 transition-all duration-300 border border-yellow-400/30">
              <svg class="w-12 h-12 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>
            </div>
            <div class="text-5xl font-bold text-white mb-3 animate-pulse">{{ userScore }}</div>
            <div class="text-blue-200 text-lg">امتیاز کل</div>
          </div>
          
          <div class="text-center group animate-fadeInUp" style="animation-delay: 0.8s">
            <div class="w-24 h-24 bg-purple-500/20 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:bg-purple-500/30 transition-all duration-300 border border-purple-400/30">
              <svg class="w-12 h-12 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/>
              </svg>
            </div>
            <div class="text-5xl font-bold text-white mb-3 animate-pulse">{{ progressPercentage }}%</div>
            <div class="text-blue-200 text-lg">درصد پیشرفت</div>
          </div>
        </div>
        
        <!-- Progress Bar -->
        <div class="bg-white/10 rounded-2xl p-8 border border-white/20 animate-fadeInUp" style="animation-delay: 0.9s">
          <div class="flex justify-between text-white mb-6">
            <span class="font-semibold text-xl">پیشرفت کلی</span>
            <span class="font-bold text-xl bg-gradient-to-r from-purple-400 to-blue-400 bg-clip-text text-transparent">{{ progressPercentage }}%</span>
          </div>
          <div class="w-full bg-white/20 rounded-full h-6 overflow-hidden shadow-inner">
            <div 
              class="bg-gradient-to-r from-indigo-500 via-purple-500 to-blue-500 h-6 rounded-full transition-all duration-2000 ease-out relative overflow-hidden shadow-lg"
              :style="{ width: progressPercentage + '%' }"
            >
              <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-pulse"></div>
              <div class="absolute inset-0 bg-gradient-to-r from-white/20 via-transparent to-white/20 animate-pulse" style="animation-delay: 1s"></div>
            </div>
          </div>
          <div class="flex justify-center mt-4">
            <div class="flex space-x-1 space-x-reverse">
              <div class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce"></div>
              <div class="w-2 h-2 bg-purple-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
              <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
import type { User } from '../types'

interface UserProgress {
  stage_id: number
  score: number
  completed: boolean
}

interface Props {
  user: User
  userProgress: UserProgress[]
  totalStages: number
}

const props = defineProps<Props>()

const userScore = computed(() => {
  return props.userProgress.reduce((total, progress) => total + progress.score, 0)
})

const completedStages = computed(() => {
  return props.userProgress.filter(progress => progress.completed).length
})

const progressPercentage = computed(() => {
  if (props.totalStages === 0) return 0
  return Math.round((completedStages.value / props.totalStages) * 100)
})

const navigateTo = (path: string) => {
  router.visit(path)
}
</script>

<style scoped>
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
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

@keyframes glow {
  0%, 100% {
    box-shadow: 0 0 20px rgba(139, 92, 246, 0.3);
  }
  50% {
    box-shadow: 0 0 30px rgba(139, 92, 246, 0.6);
  }
}

@keyframes shimmer {
  0% {
    background-position: -200% 0;
  }
  100% {
    background-position: 200% 0;
  }
}

@keyframes heartbeat {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.1);
  }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

.animate-fadeInUp {
  animation: fadeInUp 0.8s ease-out forwards;
}

.animate-slideInRight {
  animation: slideInRight 0.8s ease-out forwards;
}

.animate-scaleIn {
  animation: scaleIn 0.6s ease-out forwards;
}

.animate-glow {
  animation: glow 2s ease-in-out infinite;
}

.animate-shimmer {
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  background-size: 200% 100%;
  animation: shimmer 2s infinite;
}

.animate-heartbeat {
  animation: heartbeat 1.5s ease-in-out infinite;
}

/* Custom hover effects */
.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}

.group:hover .group-hover\:opacity-100 {
  opacity: 1;
}
</style>
