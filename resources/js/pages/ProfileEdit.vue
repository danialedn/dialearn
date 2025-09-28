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
          <h1 class="text-2xl font-bold bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">ویرایش پروفایل</h1>
          <div></div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10">
      <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden animate-bounce-in">
        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-purple-600/80 to-blue-600/80 backdrop-blur-xl text-white p-8 text-center">
          <div class="w-24 h-24 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-500/25">
            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
          </div>
          <h2 class="text-3xl font-bold mb-2">ویرایش اطلاعات شخصی</h2>
          <p class="text-blue-100">اطلاعات خود را به‌روزرسانی کنید</p>
        </div>

        <!-- Form -->
        <form @submit.prevent="updateProfile" class="p-8 space-y-6">
          <!-- Profile Picture -->
          <div class="text-center">
            <div class="relative inline-block">
              <div class="w-32 h-32 rounded-full bg-gradient-to-r from-purple-500/20 to-blue-500/20 backdrop-blur-sm border border-white/20 flex items-center justify-center mx-auto mb-4 overflow-hidden shadow-lg">
                <img 
                  v-if="profilePictureUrl" 
                  :src="profilePictureUrl" 
                  alt="Profile" 
                  class="w-full h-full object-cover"
                >
                <div v-else class="w-full h-full bg-gradient-to-r from-purple-600 to-blue-600 flex items-center justify-center">
                  <span class="text-white text-2xl font-semibold">{{ form.name?.charAt(0) }}</span>
                </div>
              </div>
              <input 
                ref="fileInput"
                type="file" 
                accept="image/*" 
                @change="handleFileUpload" 
                class="hidden"
              >
              <button 
                type="button"
                @click="fileInput?.click()"
                class="absolute bottom-2 right-1/2 transform translate-x-1/2 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-full p-3 hover:from-purple-700 hover:to-blue-700 transition-all duration-300 shadow-lg shadow-purple-500/25 border border-white/20"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                </svg>
              </button>
            </div>
            <p class="text-sm text-blue-200">برای تغییر تصویر پروفایل کلیک کنید</p>
          </div>

          <!-- Name -->
          <div class="animate-fade-in-up" style="animation-delay: 0.1s">
            <label class="block text-sm font-medium text-white mb-2">نام و نام خانوادگی *</label>
            <input 
              v-model="form.name" 
              type="text" 
              required
              class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:scale-105 text-white placeholder-blue-200 text-right transition-all duration-300 hover:bg-white/15"
              placeholder="نام خود را وارد کنید"
            >
            <div v-if="errors.name" class="text-red-400 text-sm mt-1 animate-shake">{{ errors.name }}</div>
          </div>

          <!-- Username -->
          <div class="animate-fade-in-up" style="animation-delay: 0.2s">
            <label class="block text-sm font-medium text-white mb-2">نام کاربری *</label>
            <input 
              v-model="form.username" 
              type="text" 
              required
              class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:scale-105 text-white placeholder-blue-200 transition-all duration-300 hover:bg-white/15"
              placeholder="نام کاربری منحصر به فرد"
            >
            <div v-if="errors.username" class="text-red-400 text-sm mt-1 animate-shake">{{ errors.username }}</div>
          </div>

          <!-- Email -->
          <div class="animate-fade-in-up" style="animation-delay: 0.3s">
            <label class="block text-sm font-medium text-white mb-2">ایمیل</label>
            <input 
              v-model="form.email" 
              type="email" 
              class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:scale-105 text-white placeholder-blue-200 transition-all duration-300 hover:bg-white/15"
              placeholder="ایمیل خود را وارد کنید"
            >
            <div v-if="errors.email" class="text-red-400 text-sm mt-1 animate-shake">{{ errors.email }}</div>
          </div>

          <!-- Gender -->
          <div class="animate-fade-in-up" style="animation-delay: 0.4s">
            <label class="block text-sm font-medium text-white mb-3">جنسیت *</label>
            <div class="grid grid-cols-2 gap-4">
              <label class="flex items-center bg-white/5 backdrop-blur-sm border border-white/20 rounded-2xl p-4 cursor-pointer hover:bg-white/10 hover:border-purple-400/50 hover:scale-105 transition-all duration-300 group">
                <input 
                  v-model="form.gender" 
                  type="radio" 
                  value="male" 
                  class="mr-3 text-purple-600 focus:ring-purple-500 bg-white/10 border-white/20"
                >
                <span class="text-white group-hover:text-purple-200 transition-colors duration-300">مرد</span>
              </label>
              <label class="flex items-center bg-white/5 backdrop-blur-sm border border-white/20 rounded-2xl p-4 cursor-pointer hover:bg-white/10 hover:border-purple-400/50 hover:scale-105 transition-all duration-300 group">
                <input 
                  v-model="form.gender" 
                  type="radio" 
                  value="female" 
                  class="mr-3 text-purple-600 focus:ring-purple-500 bg-white/10 border-white/20"
                >
                <span class="text-white group-hover:text-purple-200 transition-colors duration-300">زن</span>
              </label>
            </div>
            <div v-if="errors.gender" class="text-red-400 text-sm mt-1 animate-shake">{{ errors.gender }}</div>
          </div>

          <!-- Age -->
          <div class="animate-fade-in-up" style="animation-delay: 0.5s">
            <label class="block text-sm font-medium text-white mb-2">سن *</label>
            <input 
              v-model.number="form.age" 
              type="number" 
              min="10" 
              max="100" 
              required
              class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:scale-105 text-white placeholder-blue-200 transition-all duration-300 hover:bg-white/15"
              placeholder="سن خود را وارد کنید"
            >
            <div v-if="errors.age" class="text-red-400 text-sm mt-1 animate-shake">{{ errors.age }}</div>
          </div>

          <!-- Education Level -->
          <div class="animate-fade-in-up" style="animation-delay: 0.6s">
            <label class="block text-sm font-medium text-white mb-2">میزان تحصیلات *</label>
            <select 
              v-model="form.education_level" 
              required
              class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:scale-105 text-white text-right transition-all duration-300 hover:bg-white/15"
            >
              <option value="" class="bg-slate-800 text-white">انتخاب کنید</option>
              <option value="elementary" class="bg-slate-800 text-white">ابتدایی</option>
              <option value="middle_school" class="bg-slate-800 text-white">راهنمایی</option>
              <option value="high_school" class="bg-slate-800 text-white">دبیرستان</option>
              <option value="diploma" class="bg-slate-800 text-white">دیپلم</option>
              <option value="bachelor" class="bg-slate-800 text-white">کارشناسی</option>
              <option value="master" class="bg-slate-800 text-white">کارشناسی ارشد</option>
              <option value="phd" class="bg-slate-800 text-white">دکتری</option>
            </select>
            <div v-if="errors.education_level" class="text-red-400 text-sm mt-1 animate-shake">{{ errors.education_level }}</div>
          </div>

          <!-- Success Message -->
          <div v-if="successMessage" class="bg-green-500/20 backdrop-blur-sm border border-green-400/30 text-green-300 px-4 py-3 rounded-2xl">
            {{ successMessage }}
          </div>

          <!-- Submit Buttons -->
          <div class="flex space-x-4 space-x-reverse pt-6 animate-fade-in-up" style="animation-delay: 0.7s">
            <button 
              type="submit" 
              :disabled="processing"
              class="flex-1 bg-gradient-to-r from-purple-600 to-blue-600 text-white py-4 px-6 rounded-2xl hover:from-purple-700 hover:to-blue-700 hover:scale-105 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-transparent transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed font-semibold shadow-lg shadow-purple-500/25 border border-white/20 transform hover:shadow-xl"
            >
              <span v-if="processing" class="flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                در حال ذخیره...
              </span>
              <span v-else>ذخیره تغییرات</span>
            </button>
            <button 
              type="button"
              @click="goBack"
              class="flex-1 bg-white/10 backdrop-blur-sm text-white py-4 px-6 rounded-2xl hover:bg-white/20 hover:scale-105 transition-all duration-300 font-semibold border border-white/20 transform hover:shadow-lg"
            >
              انصراف
            </button>
          </div>
        </form>
      </div>
                  <!-- Logout Section -->
            <div class="space-y-6">
                <HeadingSmall title="خروج از حساب" description="از حساب کاربری خود خارج شوید" />
                <div class="space-y-4 rounded-lg border border-orange-100 bg-orange-50 p-4 dark:border-orange-200/10 dark:bg-orange-700/10">
                    <div class="relative space-y-0.5 text-orange-600 dark:text-orange-100">
                        <p class="font-medium">خروج از حساب</p>
                        <p class="text-sm">با کلیک بر روی دکمه زیر از حساب کاربری خود خارج خواهید شد.</p>
                    </div>
                    <Button 
                        @click="handleLogout" 
                        variant="outline" 
                        class="border-orange-300 text-orange-700 hover:bg-orange-100 dark:border-orange-600 dark:text-orange-300 dark:hover:bg-orange-900/20"
                    >
                        خروج از حساب
                    </Button>
                </div>
            </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { router, useForm, usePage } from '@inertiajs/vue3'
import { logout } from '@/routes'
import type { User } from '../types'

interface FormData {
  name: string
  username: string
  email: string
  gender: string
  age: number | null
  education_level: string
  profile_picture: File | null
}

const page = usePage()
const user = computed(() => page.props.auth?.user as User)

const form = useForm<FormData>({
  name: '',
  username: '',
  email: '',
  gender: '',
  age: null,
  education_level: '',
  profile_picture: null
})

const errors = ref<Record<string, string>>({})
const processing = ref(false)
const successMessage = ref('')
const fileInput = ref<HTMLInputElement | null>(null)

const profilePictureUrl = computed(() => {
  if (form.profile_picture) {
    return URL.createObjectURL(form.profile_picture)
  }
  if (user.value?.profile_picture) {
    return `/storage/${user.value.profile_picture}`
  }
  return undefined
})

// Load user data on mount
onMounted(() => {
  if (user.value) {
    form.name = user.value.name
    form.username = user.value.username
    form.email = user.value.email
    form.gender = user.value.gender
    form.age = user.value.age
    form.education_level = user.value.education_level
  }
})

const handleFileUpload = (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  if (file) {
    form.profile_picture = file
  }
}

const updateProfile = () => {
  processing.value = true
  errors.value = {}
  successMessage.value = ''
  
  form.post('/profile/update', {
    onSuccess: () => {
      successMessage.value = 'پروفایل شما با موفقیت به‌روزرسانی شد!'
      setTimeout(() => {
        successMessage.value = ''
      }, 3000)
    },
    onError: (formErrors) => {
      errors.value = formErrors
    },
    onFinish: () => {
      processing.value = false
    }
  })
}

const goBack = () => {
  router.visit('/dashboard')
}

const handleLogout = () => {
  router.post(logout().url)
}
</script>

<style scoped>
* {
  font-family: 'Vazirmatn', 'Tahoma', 'Arial', sans-serif;
}

.space-x-4.space-x-reverse > * + * {
  margin-right: 1rem;
  margin-left: 0;
}

/* Custom scrollbar for select */
select::-webkit-scrollbar {
  width: 8px;
}

select::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.1);
  border-radius: 10px;
}

select::-webkit-scrollbar-thumb {
  background: rgba(147, 51, 234, 0.5);
  border-radius: 10px;
}

select::-webkit-scrollbar-thumb:hover {
  background: rgba(147, 51, 234, 0.7);
}

/* Animation for background elements */
@keyframes pulse {
  0%, 100% {
    opacity: 0.4;
    transform: scale(1);
  }
  50% {
    opacity: 0.6;
    transform: scale(1.05);
  }
}

.animate-pulse {
  animation: pulse 6s ease-in-out infinite;
}

/* Input focus effects */
input:focus, select:focus {
  box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.1);
}

/* Radio button custom styling */
input[type="radio"]:checked {
  background-color: rgb(147 51 234);
  border-color: rgb(147 51 234);
}

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-5px); }
  75% { transform: translateX(5px); }
}

.animate-shake {
  animation: shake 0.5s ease-in-out;
}

@keyframes pulse-glow {
  0%, 100% { 
    box-shadow: 0 0 5px rgba(147, 51, 234, 0.3);
  }
  50% { 
    box-shadow: 0 0 20px rgba(147, 51, 234, 0.6), 0 0 30px rgba(147, 51, 234, 0.4);
  }
}

@keyframes bounce-in {
  0% {
    transform: scale(0.3);
    opacity: 0;
  }
  50% {
    transform: scale(1.05);
  }
  70% {
    transform: scale(0.9);
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}

.animate-pulse-glow {
  animation: pulse-glow 2s ease-in-out infinite;
}

.animate-bounce-in {
  animation: bounce-in 0.6s ease-out;
}

/* Hover animations for form elements */
input:focus, select:focus {
  animation: pulse-glow 1s ease-in-out;
}

/* Button hover effects */
button:hover {
  transform: translateY(-2px);
}

button:active {
  transform: translateY(0);
}
</style>