<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 flex items-center justify-center p-4 relative overflow-hidden">
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
    
    <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl max-w-4xl w-full max-h-[95vh] md:max-h-[90vh] overflow-hidden border border-white/20 relative z-10 flex flex-col">
      <!-- Header -->
      <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-600 text-white p-4 md:p-8 relative overflow-hidden flex-shrink-0">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/50 via-purple-600/50 to-blue-600/50 backdrop-blur-sm"></div>
        <div class="relative z-10">
          <div class="flex items-center justify-center mb-2 md:mb-4">
            <div class="w-12 h-12 md:w-16 md:h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/30">
              <svg class="w-6 h-6 md:w-8 md:h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
              </svg>
            </div>
          </div>
          <h1 class="text-2xl md:text-4xl lg:text-5xl font-bold text-center mb-2 md:mb-3 bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">
            دیالرن
          </h1>
          <p class="text-center text-blue-100 text-sm md:text-lg lg:text-xl font-medium">پلتفرم آموزش هوشمند دیابت</p>
        </div>
      </div>

      <!-- Content -->
      <div class="p-4 md:p-8 overflow-y-auto flex-1 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-none text-right" dir="rtl">
          <!-- Welcome Section -->
          <div class="text-center mb-4 md:mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl mb-3 md:mb-6 shadow-lg">
              <svg class="w-8 h-8 md:w-10 md:h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>
            </div>
            <h2 class="text-xl md:text-3xl lg:text-4xl font-bold text-gray-800 mb-2 md:mb-4">خوش آمدید!</h2>
            <p class="text-sm md:text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed px-2">
              به پلتفرم آموزش هوشمند دیابت خوش آمدید. اینجا می‌توانید با روش‌های علمی و تعاملی، دانش خود را در زمینه مدیریت دیابت افزایش دهید.
            </p>
          </div>

          <!-- Features Grid -->
          <div class="grid md:grid-cols-2 gap-4 md:gap-6 mb-4 md:mb-8">
            <!-- Learning Features -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100 hover:shadow-lg transition-all duration-300">
              <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mr-4">
                  <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                  </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800">آموزش تعاملی</h3>
              </div>
              <ul class="space-y-3 text-gray-600">
                <li class="flex items-center">
                  <div class="w-2 h-2 bg-blue-400 rounded-full mr-3"></div>
                  مراحل آموزشی ساختاریافته
                </li>
                <li class="flex items-center">
                  <div class="w-2 h-2 bg-blue-400 rounded-full mr-3"></div>
                  سوالات چندگزینه‌ای هوشمند
                </li>
                <li class="flex items-center">
                  <div class="w-2 h-2 bg-blue-400 rounded-full mr-3"></div>
                  بازخورد فوری و مفصل
                </li>
              </ul>
            </div>

            <!-- Progress Tracking -->
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100 hover:shadow-lg transition-all duration-300">
              <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mr-4">
                  <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/>
                  </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800">پیگیری پیشرفت</h3>
              </div>
              <ul class="space-y-3 text-gray-600">
                <li class="flex items-center">
                  <div class="w-2 h-2 bg-purple-400 rounded-full mr-3"></div>
                  نمودار پیشرفت شخصی
                </li>
                <li class="flex items-center">
                  <div class="w-2 h-2 bg-purple-400 rounded-full mr-3"></div>
                  سیستم امتیازدهی پیشرفته
                </li>
                <li class="flex items-center">
                  <div class="w-2 h-2 bg-purple-400 rounded-full mr-3"></div>
                  گزارش‌های تحلیلی
                </li>
              </ul>
            </div>
          </div>

          <!-- Learning Topics -->
          <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-4 md:p-6 border border-gray-200">
            <h3 class="text-lg md:text-2xl font-bold text-gray-800 mb-3 md:mb-6 text-center">موضوعات آموزشی</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
              <div class="text-center p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                  <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                </div>
                <p class="text-sm font-medium text-gray-700">تغذیه سالم</p>
              </div>
              <div class="text-center p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                  <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                </div>
                <p class="text-sm font-medium text-gray-700">کنترل قند</p>
              </div>
              <div class="text-center p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                  <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                </div>
                <p class="text-sm font-medium text-gray-700">ورزش مناسب</p>
              </div>
              <div class="text-center p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                  <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                </div>
                <p class="text-sm font-medium text-gray-700">مراقبت روزانه</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer with action buttons -->
      <div class="bg-gradient-to-r from-gray-50 to-white p-4 md:p-8 border-t border-gray-200 flex-shrink-0">
        <div class="max-w-2xl mx-auto space-y-3 md:space-y-4">
          <!-- قبلاً ثبت نام کرده ام -->
          <button 
            @click="goToLogin"
            class="group w-full bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 text-white font-semibold py-3 md:py-4 px-4 md:px-6 rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-300 flex items-center justify-center relative overflow-hidden text-sm md:text-base"
          >
            <div class="absolute inset-0 bg-gradient-to-r from-slate-600/50 to-slate-700/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <svg class="w-4 h-4 md:w-5 md:h-5 ml-2 md:ml-3 relative z-10" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            <span class="relative z-10">قبلاً ثبت نام کرده ام</span>
          </button>

          <!-- موافقم با قوانین -->
          <button 
            @click="acceptRules"
            class="group w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold py-4 md:py-5 px-4 md:px-6 rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-300 flex items-center justify-center relative overflow-hidden"
          >
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/50 to-purple-600/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <svg class="w-5 h-5 md:w-6 md:h-6 ml-2 md:ml-3 relative z-10" fill="currentColor" viewBox="0 0 24 24">
              <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>
            <span class="relative z-10 text-base md:text-lg">شروع یادگیری</span>
          </button>

          <!-- رد قوانین -->
          <button 
            @click="rejectRules"
            class="group w-full bg-gradient-to-r from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600 text-white font-medium py-2.5 md:py-3 px-4 md:px-6 rounded-2xl shadow-md hover:shadow-lg transform hover:scale-[1.01] transition-all duration-300 flex items-center justify-center relative overflow-hidden text-sm md:text-base"
          >
            <div class="absolute inset-0 bg-gradient-to-r from-gray-400/50 to-gray-500/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <svg class="w-3 h-3 md:w-4 md:h-4 ml-2 md:ml-3 relative z-10" fill="currentColor" viewBox="0 0 24 24">
              <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
            </svg>
            <span class="relative z-10">خروج</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3'

// قبول قوانین - هدایت به فرم ثبت نام
const acceptRules = () => {
  router.post('/accept-rules')
}

// ورود کاربران قبلی - هدایت به صفحه ورود
const goToLogin = () => {
  router.visit('/login')
}

// رد قوانین - خروج از اپلیکیشن یا نمایش پیام
const rejectRules = () => {
  if (confirm('آیا مطمئن هستید که می‌خواهید از بازی خارج شوید؟')) {
    // می‌توان به صفحه خروج یا صفحه اصلی هدایت کرد
    try {
      window.close()
    } catch {
      window.location.href = 'about:blank'
    }
  }
}
</script>

<style scoped>
@keyframes float {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-20px);
  }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
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

.animate-fadeInUp {
  animation: fadeInUp 0.6s ease-out;
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

.animate-slideInRight {
  animation: slideInRight 0.8s ease-out;
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

.animate-scaleIn {
  animation: scaleIn 0.5s ease-out;
}
</style>