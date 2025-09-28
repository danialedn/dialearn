<template>
  <div dir="rtl" lang="fa" class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 relative overflow-hidden">
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

    <!-- Header -->
    <header class="relative z-10 bg-white/10 backdrop-blur-xl border-b border-white/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <button 
            @click="goBack"
            class="group flex items-center bg-white/20 backdrop-blur-sm rounded-2xl px-4 py-2 border border-white/30 hover:bg-white/30 transition-all duration-300"
          >
            <svg class="w-5 h-5 ml-2 text-white group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
              <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
            </svg>
            <span class="text-white font-medium">بازگشت</span>
          </button>
          
          <div class="text-center">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">درباره ما</h1>
          </div>
          
          <div class="w-20"></div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Mission Section -->
      <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-12 mb-12 border border-white/20">
        <div class="text-center">
          <div class="w-24 h-24 bg-gradient-to-r from-green-500 to-emerald-600 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-2xl">
            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
          </div>
          <h2 class="text-4xl md:text-5xl font-bold mb-6 bg-gradient-to-r from-white to-green-200 bg-clip-text text-transparent">
            ماموریت ما
          </h2>
          <p class="text-xl md:text-2xl text-blue-200 leading-relaxed max-w-4xl mx-auto">
            ما در دیالرن با هدف ارتقاء آگاهی عمومی درباره دیابت و کمک به بهبود کیفیت زندگی افراد مبتلا به این بیماری فعالیت می‌کنیم.
          </p>
        </div>
      </div>

      <!-- About Project -->
      <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-12 mb-12 border border-white/20">
        <div class="text-center mb-8">
          <h3 class="text-3xl md:text-4xl font-bold mb-6 bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">
            درباره پروژه دیالرن
          </h3>
        </div>
        <div class="space-y-6 text-blue-200 leading-relaxed text-lg">
          <p>
            دیالرن یک پلتفرم آموزشی تعاملی است که با استفاده از روش‌های نوین یادگیری و بازی‌سازی، اطلاعات علمی و کاربردی درباره دیابت را به کاربران ارائه می‌دهد.
          </p>
          <p>
            این پروژه با همکاری متخصصان غدد و متابولیسم، متخصصان تغذیه و تیم فنی مجرب طراحی و توسعه یافته است تا بتواند نیازهای آموزشی مختلف کاربران را پاسخ دهد.
          </p>
          <p>
            هدف اصلی ما ایجاد یک محیط یادگیری جذاب و مؤثر است که کاربران بتوانند با لذت و انگیزه، دانش خود را درباره دیابت افزایش دهند.
          </p>
        </div>
      </div>

      <!-- Features -->
      <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-12 mb-12 border border-white/20">
        <div class="text-center mb-12">
          <h3 class="text-3xl md:text-4xl font-bold mb-6 bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">
            ویژگی‌های کلیدی
          </h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div class="group flex items-start space-x-6 space-x-reverse p-6 bg-white/10 rounded-2xl border border-white/20 hover:bg-white/20 transition-all duration-300">
            <div class="w-16 h-16 bg-blue-500/20 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
              <svg class="w-8 h-8 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
              </svg>
            </div>
            <div>
              <h4 class="font-bold text-white mb-3 text-xl">محتوای علمی معتبر</h4>
              <p class="text-blue-200 leading-relaxed">تمام اطلاعات بر اساس آخرین یافته‌های علمی و استانداردهای بین‌المللی</p>
            </div>
          </div>
          
          <div class="group flex items-start space-x-6 space-x-reverse p-6 bg-white/10 rounded-2xl border border-white/20 hover:bg-white/20 transition-all duration-300">
            <div class="w-16 h-16 bg-green-500/20 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
              <svg class="w-8 h-8 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
              </svg>
            </div>
            <div>
              <h4 class="font-bold text-white mb-3 text-xl">رابط کاربری ساده</h4>
              <p class="text-blue-200 leading-relaxed">طراحی شده برای استفاده آسان توسط تمام گروه‌های سنی</p>
            </div>
          </div>
          
          <div class="group flex items-start space-x-6 space-x-reverse p-6 bg-white/10 rounded-2xl border border-white/20 hover:bg-white/20 transition-all duration-300">
            <div class="w-16 h-16 bg-yellow-500/20 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
              <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
              </svg>
            </div>
            <div>
              <h4 class="font-bold text-white mb-3 text-xl">یادگیری تدریجی</h4>
              <p class="text-blue-200 leading-relaxed">مراحل آموزشی از ساده به پیچیده برای یادگیری مؤثر</p>
            </div>
          </div>
          
          <div class="group flex items-start space-x-6 space-x-reverse p-6 bg-white/10 rounded-2xl border border-white/20 hover:bg-white/20 transition-all duration-300">
            <div class="w-16 h-16 bg-purple-500/20 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
              <svg class="w-8 h-8 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>
            </div>
            <div>
              <h4 class="font-bold text-white mb-3 text-xl">پیگیری پیشرفت</h4>
              <p class="text-blue-200 leading-relaxed">امکان مشاهده پیشرفت و عملکرد شخصی</p>
            </div>
          </div>
        </div>
      </div>

      <!-- University Logo Section -->
      <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">حمایت کننده</h3>
        <div class="text-center">
          <div class="w-32 h-32 mx-auto mb-4 bg-gradient-to-br from-blue-600 to-green-600 rounded-full flex items-center justify-center shadow-lg">
            <svg class="w-20 h-20 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2L3 7l9 5 9-5-9-5zM3 17l9 5 9-5M3 12l9 5 9-5"/>
            </svg>
          </div>
          <h4 class="text-xl font-bold text-gray-900 mb-2">دانشگاه علوم پزشکی آبادان</h4>
          <p class="text-gray-600">این پروژه با حمایت و نظارت دانشگاه علوم پزشکی آبادان توسعه یافته است</p>
        </div>
      </div>

      <!-- Team Section -->
      <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">تیم توسعه</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="text-center">
            <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
              </svg>
            </div>
            <h4 class="font-semibold text-gray-900 mb-2">متخصصان پزشکی</h4>
            <p class="text-gray-600 text-sm">متخصصان غدد و متابولیسم و تغذیه</p>
          </div>
          <div class="text-center">
            <div class="w-20 h-20 bg-gradient-to-r from-green-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
              </svg>
            </div>
            <h4 class="font-semibold text-gray-900 mb-2">تیم فنی</h4>
            <p class="text-gray-600 text-sm">توسعه‌دهندگان و طراحان رابط کاربری</p>
          </div>
          <div class="text-center">
            <div class="w-20 h-20 bg-gradient-to-r from-yellow-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
              </svg>
            </div>
            <h4 class="font-semibold text-gray-900 mb-2">مشاوران آموزشی</h4>
            <p class="text-gray-600 text-sm">متخصصان روش‌های یادگیری</p>
          </div>
        </div>
      </div>

      <!-- Contact Section -->
      <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">تماس با ما</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
              </svg>
            </div>
            <h4 class="font-semibold text-gray-900 mb-2">ایمیل</h4>
            <p class="text-gray-600">s.sharafi@abadanums.ac.ir</p>
          </div>
          <div class="text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
              </svg>
            </div>
            <h4 class="font-semibold text-gray-900 mb-2">تلفن پشتیبانی</h4>
            <p class="text-gray-600">021-12345678</p>
          </div>
        </div>
      </div>

      <!-- Call to Action -->
      <div class="text-center">
        <button 
          @click="goBack"
          class="group bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-12 py-4 rounded-2xl text-lg font-semibold transition-all duration-300 transform hover:scale-[1.02] shadow-2xl relative overflow-hidden"
        >
          <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/50 to-purple-600/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          <div class="relative z-10 flex items-center space-x-2 space-x-reverse">
            <span>بازگشت به داشبورد</span>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
            </svg>
          </div>
        </button>
      </div>
    </main>
  </div>
</template>

<style scoped>
* {
  font-family: 'Vazirmatn', 'Tahoma', 'Arial', sans-serif;
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

/* RTL Support */
.space-x-2.space-x-reverse > * + * {
  margin-right: 0.5rem;
  margin-left: 0;
}

.space-x-6.space-x-reverse > * + * {
  margin-right: 1.5rem;
  margin-left: 0;
}
</style>

<script setup lang="ts">
import { router } from '@inertiajs/vue3'

const goBack = () => {
  router.visit('/dashboard')
}
</script>