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
    
    <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl max-w-2xl w-full border border-white/20 relative z-10">
      <!-- Header -->
      <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-600 text-white p-8 relative overflow-hidden rounded-t-3xl">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/50 via-purple-600/50 to-blue-600/50 backdrop-blur-sm"></div>
        <div class="relative z-10 text-center">
          <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/30 mx-auto mb-4">
            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
          </div>
          <h1 class="text-4xl font-bold mb-3 bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">
            تکمیل پروفایل
          </h1>
          <p class="text-blue-100 text-lg">اطلاعات خود را وارد کنید تا بتوانیم تجربه بهتری برایتان فراهم کنیم</p>
        </div>
      </div>

      <!-- Form -->
      <form @submit.prevent="submitForm" class="p-8 space-y-8 bg-gradient-to-b from-gray-50 to-white">
        <!-- Profile Picture Upload -->
        <div class="text-center">
          <div class="relative inline-block">
            <div class="w-32 h-32 rounded-3xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center overflow-hidden border-2 border-white/30 shadow-2xl">
              <img v-if="form.profile_picture" :src="profilePictureUrl" alt="Profile" class="w-full h-full object-cover">
              <svg v-else class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
              </svg>
            </div>
            <label for="profile_picture" class="absolute -bottom-2 -right-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-3 rounded-2xl cursor-pointer hover:from-indigo-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-110 shadow-lg border-2 border-white">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2h1a1 1 0 000 2H6a1 1 0 00-1 1v6a1 1 0 001 1h1a1 1 0 100 2H6a2 2 0 01-2-2V5zM14 5a1 1 0 011-1h1a2 2 0 012 2v6a2 2 0 01-2 2h-1a1 1 0 110-2h1a1 1 0 001-1V5a1 1 0 00-1-1h-1a1 1 0 01-1-1z" clip-rule="evenodd"/>
              </svg>
            </label>
            <input 
              id="profile_picture" 
              type="file" 
              @change="handleFileUpload" 
              accept="image/*" 
              class="hidden"
            >
          </div>
          <p class="mt-4 text-gray-600 font-medium">
            تصویر پروفایل (اختیاری)
          </p>
          <div v-if="form.errors.profile_picture" class="text-red-500 text-sm mt-2 bg-red-50 rounded-lg p-3 border border-red-200">{{ form.errors.profile_picture }}</div>
        </div>

        <!-- Name -->
        <div class="space-y-2">
          <label for="name" class="block text-sm font-semibold text-gray-700">
            نام کامل
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
              <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
              </svg>
            </div>
            <input 
              id="name"
              v-model="form.name" 
              type="text" 
              required 
              class="w-full pl-4 pr-12 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 text-lg bg-white/80 backdrop-blur-sm shadow-sm hover:shadow-md"
              placeholder="نام کامل خود را وارد کنید"
            >
          </div>
          <div v-if="form.errors.name" class="text-red-500 text-sm mt-2 bg-red-50 rounded-lg p-3 border border-red-200">
            {{ form.errors.name }}
          </div>
        </div>

        <!-- Username -->
        <div class="space-y-2">
          <label for="username" class="block text-sm font-semibold text-gray-700">
            نام کاربری
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
              <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
              </svg>
            </div>
            <input 
              id="username"
              v-model="form.username" 
              type="text" 
              required 
              class="w-full pl-4 pr-12 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 text-lg bg-white/80 backdrop-blur-sm shadow-sm hover:shadow-md"
              placeholder="نام کاربری منحصر به فرد انتخاب کنید"
            >
          </div>
          <div v-if="form.errors.username" class="text-red-500 text-sm mt-2 bg-red-50 rounded-lg p-3 border border-red-200">
            {{ form.errors.username }}
          </div>
        </div>

        <!-- Password -->
        <div class="space-y-2">
          <label for="password" class="block text-sm font-semibold text-gray-700">
            رمز عبور
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
              <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
              </svg>
            </div>
            <input 
              id="password"
              v-model="form.password" 
              type="password" 
              required 
              class="w-full pl-4 pr-12 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 text-lg bg-white/80 backdrop-blur-sm shadow-sm hover:shadow-md"
              placeholder="رمز عبور خود را وارد کنید"
            >
          </div>
          <div v-if="form.errors.password" class="text-red-500 text-sm mt-2 bg-red-50 rounded-lg p-3 border border-red-200">
            {{ form.errors.password }}
          </div>
        </div>

        <!-- Password Confirmation -->
        <div class="space-y-2">
          <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">
            تکرار رمز عبور
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
              <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
              </svg>
            </div>
            <input 
              id="password_confirmation"
              v-model="form.password_confirmation" 
              type="password" 
              required 
              class="w-full pl-4 pr-12 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 text-lg bg-white/80 backdrop-blur-sm shadow-sm hover:shadow-md"
              placeholder="رمز عبور خود را دوباره وارد کنید"
            >
          </div>
          <div v-if="form.errors.password_confirmation" class="text-red-500 text-sm mt-2 bg-red-50 rounded-lg p-3 border border-red-200">
            {{ form.errors.password_confirmation }}
          </div>
        </div>

        <!-- Gender -->
        <div class="space-y-4">
          <label class="block text-sm font-semibold text-gray-700">
            جنسیت
          </label>
          <div class="grid grid-cols-2 gap-4">
            <label class="group relative flex flex-col items-center p-6 bg-white/80 backdrop-blur-sm rounded-2xl border border-gray-200 cursor-pointer hover:border-indigo-300 hover:shadow-md transition-all duration-300 transform hover:scale-[1.02]" :class="form.gender === 'male' ? 'border-indigo-500 bg-indigo-50' : ''">
              <input 
                v-model="form.gender" 
                type="radio" 
                value="male" 
                class="hidden"
              >
              <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
              </div>
              <span class="font-semibold text-gray-700">مرد</span>
            </label>
            
            <label class="group relative flex flex-col items-center p-6 bg-white/80 backdrop-blur-sm rounded-2xl border border-gray-200 cursor-pointer hover:border-pink-300 hover:shadow-md transition-all duration-300 transform hover:scale-[1.02]" :class="form.gender === 'female' ? 'border-pink-500 bg-pink-50' : ''">
              <input 
                v-model="form.gender" 
                type="radio" 
                value="female" 
                class="hidden"
              >
              <div class="w-12 h-12 bg-pink-100 rounded-2xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-6 h-6 text-pink-600" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
              </div>
              <span class="font-semibold text-gray-700">زن</span>
            </label>
          </div>
          <div v-if="form.errors.gender" class="text-red-500 text-sm mt-2 bg-red-50 rounded-lg p-3 border border-red-200">
            {{ form.errors.gender }}
          </div>
        </div>

        <!-- Age -->
        <div class="bg-gradient-to-r from-yellow-100 to-orange-100 rounded-2xl p-4 border-2 border-yellow-300">
          <label class="flex items-center text-lg font-bold text-yellow-700 mb-3">
            <span class="text-2xl mr-2">🎂</span>
            چند سالته؟
          </label>
          <input 
            v-model.number="form.age" 
            type="number" 
            min="10" 
            max="100" 
            required
            class="w-full px-4 py-3 border-2 border-yellow-300 rounded-xl focus:ring-4 focus:ring-yellow-200 focus:border-yellow-500 transition-all duration-300 text-lg font-medium bg-white shadow-lg text-center"
            placeholder="سن خودت رو بنویس... 🎈"
          >
          <div v-if="form.errors.age" class="text-red-500 text-sm mt-2 bg-red-100 rounded-lg p-2 flex items-center">
            <span class="text-lg mr-1">⚠️</span>
            {{ form.errors.age }}
          </div>
        </div>

        <!-- Education Level -->
        <div class="bg-gradient-to-r from-indigo-100 to-blue-100 rounded-2xl p-4 border-2 border-indigo-300">
          <label class="flex items-center text-lg font-bold text-indigo-700 mb-3">
            <span class="text-2xl mr-2">🎓</span>
            تحصیلات
          </label>
          <select 
            v-model="form.education_level" 
            required
            class="w-full px-4 py-3 border-2 border-indigo-300 rounded-xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition-all duration-300 text-lg font-medium bg-white shadow-lg"
          >
            <option value="">انتخاب کن... 📚</option>
            <option value="elementary">🏫 ابتدایی</option>
            <option value="middle_school">📖 راهنمایی</option>
            <option value="high_school">🎒 دبیرستان</option>
            <option value="diploma">📜 دیپلم</option>
            <option value="bachelor">🎓 کارشناسی</option>
            <option value="master">👨‍🎓 کارشناسی ارشد</option>
            <option value="phd">👩‍🔬 دکتری</option>
          </select>
          <div v-if="form.errors.education_level" class="text-red-500 text-sm mt-2 bg-red-100 rounded-lg p-2 flex items-center">
            <span class="text-lg mr-1">📚</span>
            {{ form.errors.education_level }}
          </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-6">
          <button 
            type="submit" 
            :disabled="form.processing"
            class="w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white py-4 px-6 rounded-2xl hover:from-purple-600 hover:to-pink-600 focus:ring-4 focus:ring-purple-200 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed font-bold text-xl shadow-lg transform hover:scale-105 animate-pulse flex items-center justify-center"
          >
            <span v-if="form.processing" class="flex items-center">
              <span class="text-2xl mr-2">⏳</span>
              در حال ثبت اطلاعات...
            </span>
            <span v-else class="flex items-center">
              <span class="text-2xl mr-2">🚀</span>
              آماده‌ام! بیا شروع کنیم!
              <span class="text-2xl ml-2">✨</span>
            </span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { router, useForm } from '@inertiajs/vue3'

interface FormData {
  name: string
  username: string
  password: string
  password_confirmation: string
  gender: string
  age: number | null
  education_level: string
  profile_picture: File | null
}

const form = useForm<FormData>({
  name: '',
  username: '',
  password: '',
  password_confirmation: '',
  gender: '',
  age: null,
  education_level: '',
  profile_picture: null
})




const profilePictureUrl = computed(() => {
  if (form.profile_picture) {
    return URL.createObjectURL(form.profile_picture)
  }
  return undefined
})

const handleFileUpload = (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  if (file) {
    form.profile_picture = file
  }
}

const submitForm = () => {
  form.post('/complete-profile', {
    onSuccess: () => {
      router.visit('/dashboard')
    }
  })
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