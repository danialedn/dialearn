<template>
  <div dir="rtl" lang="fa" class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 relative overflow-hidden">
    <!-- Animated background elements -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl animate-pulse"></div>
      <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl animate-pulse" style="animation-delay: 4s"></div>
    </div>

    <!-- Header -->
    <header class="bg-white/10 backdrop-blur-xl border-b border-white/20 relative z-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
          <button 
            @click="goBack"
            class="flex items-center text-blue-200 hover:text-white transition-colors bg-white/10 backdrop-blur-sm rounded-2xl px-4 py-2 border border-white/20 hover:bg-white/20"
          >
            <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            بازگشت به داشبورد
          </button>
          <h1 class="text-2xl font-bold bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">معرفی بازی دیالرن</h1>
          <div></div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10">
      <!-- Introduction Section -->
      <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-8 mb-8 border border-white/20">
        <div class="text-center mb-8">
          <div class="w-24 h-24 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-500/25">
            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd" />
            </svg>
          </div>
          <h2 class="text-3xl font-bold text-white mb-4">دیالرن چیست؟</h2>
          <p class="text-xl text-blue-200 leading-relaxed">
            دیالرن یک بازی آموزشی تعاملی است که به شما کمک می‌کند تا درباره دیابت، علائم، درمان و مدیریت آن یاد بگیرید.
          </p>
        </div>
      </div>

      <!-- How to Play Section -->
      <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-8 mb-8 border border-white/20">
        <h3 class="text-2xl font-bold text-white mb-6 text-center">نحوه بازی</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="text-center group">
            <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 border border-blue-400/30">
              <span class="text-2xl font-bold text-blue-400">1</span>
            </div>
            <h4 class="font-semibold text-white mb-2">انتخاب مرحله</h4>
            <p class="text-blue-200 text-sm">از بین مراحل مختلف آموزشی یکی را انتخاب کنید</p>
          </div>
          <div class="text-center group">
            <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 border border-green-400/30">
              <span class="text-2xl font-bold text-green-400">2</span>
            </div>
            <h4 class="font-semibold text-white mb-2">پاسخ دادن</h4>
            <p class="text-blue-200 text-sm">به سوالات چندگزینه‌ای پاسخ دهید</p>
          </div>
          <div class="text-center group">
            <div class="w-16 h-16 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 border border-yellow-400/30">
              <span class="text-2xl font-bold text-yellow-400">3</span>
            </div>
            <h4 class="font-semibold text-white mb-2">کسب امتیاز</h4>
            <p class="text-blue-200 text-sm">با پاسخ‌های صحیح امتیاز کسب کنید</p>
          </div>
          <div class="text-center group">
            <div class="w-16 h-16 bg-purple-500/20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 border border-purple-400/30">
              <span class="text-2xl font-bold text-purple-400">4</span>
            </div>
            <h4 class="font-semibold text-white mb-2">پیشرفت</h4>
            <p class="text-blue-200 text-sm">مراحل بعدی را باز کنید</p>
          </div>
        </div>
      </div>

      <!-- Game Features -->
      <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-8 mb-8 border border-white/20">
        <h3 class="text-2xl font-bold text-white mb-6 text-center">ویژگی‌های بازی</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="flex items-start space-x-4 space-x-reverse group">
            <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300 border border-blue-400/30">
              <svg class="w-6 h-6 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
              </svg>
            </div>
            <div>
              <h4 class="font-semibold text-white mb-2">مراحل متنوع</h4>
              <p class="text-blue-200">مراحل مختلف آموزشی از مبتدی تا پیشرفته</p>
            </div>
          </div>
          <div class="flex items-start space-x-4 space-x-reverse group">
            <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300 border border-green-400/30">
              <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
            <div>
              <h4 class="font-semibold text-white mb-2">سیستم امتیازدهی</h4>
              <p class="text-blue-200">کسب امتیاز و رقابت با سایر کاربران</p>
            </div>
          </div>
          <div class="flex items-start space-x-4 space-x-reverse group">
            <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300 border border-yellow-400/30">
              <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
            <div>
              <h4 class="font-semibold text-white mb-2">پیگیری پیشرفت</h4>
              <p class="text-blue-200">مشاهده پیشرفت و عملکرد خود</p>
            </div>
          </div>
          <div class="flex items-start space-x-4 space-x-reverse group">
            <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300 border border-purple-400/30">
              <svg class="w-6 h-6 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
              </svg>
            </div>
            <div>
              <h4 class="font-semibold text-white mb-2">محتوای آموزشی</h4>
              <p class="text-blue-200">اطلاعات علمی و کاربردی درباره دیابت</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Learning Topics -->
      <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-8 mb-8 border border-white/20">
        <h3 class="text-2xl font-bold text-white mb-6 text-center">موضوعات آموزشی</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="bg-gradient-to-r from-blue-500/20 to-blue-600/20 p-4 rounded-2xl border border-blue-400/30 hover:bg-blue-500/30 transition-all duration-300 hover:scale-105">
            <h4 class="font-semibold text-blue-300 mb-2">آشنایی با دیابت</h4>
            <p class="text-blue-200 text-sm">تعریف، انواع و علل ابتلا به دیابت</p>
          </div>
          <div class="bg-gradient-to-r from-green-500/20 to-green-600/20 p-4 rounded-2xl border border-green-400/30 hover:bg-green-500/30 transition-all duration-300 hover:scale-105">
            <h4 class="font-semibold text-green-300 mb-2">علائم و تشخیص</h4>
            <p class="text-green-200 text-sm">نشانه‌ها و روش‌های تشخیص دیابت</p>
          </div>
          <div class="bg-gradient-to-r from-yellow-500/20 to-yellow-600/20 p-4 rounded-2xl border border-yellow-400/30 hover:bg-yellow-500/30 transition-all duration-300 hover:scale-105">
            <h4 class="font-semibold text-yellow-300 mb-2">درمان و مدیریت</h4>
            <p class="text-yellow-200 text-sm">روش‌های درمان و کنترل قند خون</p>
          </div>
          <div class="bg-gradient-to-r from-purple-500/20 to-purple-600/20 p-4 rounded-2xl border border-purple-400/30 hover:bg-purple-500/30 transition-all duration-300 hover:scale-105">
            <h4 class="font-semibold text-purple-300 mb-2">تغذیه مناسب</h4>
            <p class="text-purple-200 text-sm">رژیم غذایی و تغذیه برای دیابتی‌ها</p>
          </div>
          <div class="bg-gradient-to-r from-red-500/20 to-red-600/20 p-4 rounded-2xl border border-red-400/30 hover:bg-red-500/30 transition-all duration-300 hover:scale-105">
            <h4 class="font-semibold text-red-300 mb-2">ورزش و فعالیت</h4>
            <p class="text-red-200 text-sm">اهمیت ورزش در کنترل دیابت</p>
          </div>
          <div class="bg-gradient-to-r from-indigo-500/20 to-indigo-600/20 p-4 rounded-2xl border border-indigo-400/30 hover:bg-indigo-500/30 transition-all duration-300 hover:scale-105">
            <h4 class="font-semibold text-indigo-300 mb-2">عوارض و پیشگیری</h4>
            <p class="text-indigo-200 text-sm">عوارض دیابت و راه‌های پیشگیری</p>
          </div>
        </div>
      </div>

      <!-- Call to Action -->
      <div class="text-center">
        <button 
          @click="startGame"
          class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-2xl text-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-blue-500/25 border border-blue-400/30"
        >
          شروع بازی
        </button>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3'

const goBack = () => {
  router.visit('/dashboard')
}

const startGame = () => {
  router.visit('/quiz')
}
</script>

<style scoped>
* {
  font-family: 'Vazirmatn', 'Tahoma', 'Arial', sans-serif;
}

/* RTL Support */
.space-x-4.space-x-reverse > * + * {
  margin-right: 1rem;
  margin-left: 0;
}
</style>