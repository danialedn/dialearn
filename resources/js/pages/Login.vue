<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Animated background elements -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl animate-pulse"></div>
      <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s"></div>
    </div>
    
    <!-- Floating particles -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute top-20 left-20 w-2 h-2 bg-purple-400 rounded-full animate-float"></div>
      <div class="absolute top-40 right-32 w-1 h-1 bg-blue-400 rounded-full animate-float" style="animation-delay: 1s"></div>
      <div class="absolute bottom-32 left-40 w-3 h-3 bg-indigo-400 rounded-full animate-float" style="animation-delay: 2s"></div>
    </div>
    
    <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl max-w-md w-full border border-white/20 relative z-10">
      <!-- Header -->
      <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-600 text-white p-8 relative overflow-hidden rounded-t-3xl">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/50 via-purple-600/50 to-blue-600/50 backdrop-blur-sm"></div>
        <div class="relative z-10 text-center">
          <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/30 mx-auto mb-4">
            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
          </div>
          <h1 class="text-3xl font-bold mb-2 bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">خوش برگشتی!</h1>
          <p class="text-blue-100">وارد حساب کاربری خود شوید</p>
        </div>
      </div>

      <!-- Content -->
      <div class="p-8 bg-gradient-to-b from-gray-50 to-white">

        <form @submit.prevent="submitForm" class="space-y-6">
          <!-- Username -->
          <div class="space-y-2">
            <label for="username" class="block text-sm font-semibold text-gray-700">
              نام کاربری
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
              </div>
              <input 
                id="username"
                v-model="form.username" 
                type="text" 
                required 
                class="w-full pl-4 pr-12 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 text-lg bg-white/80 backdrop-blur-sm shadow-sm hover:shadow-md"
                placeholder="نام کاربری خود را وارد کنید"
              >
            </div>
            <div v-if="props.errors?.username" class="text-red-500 text-sm mt-2 bg-red-50 rounded-lg p-3 border border-red-200">
              {{ props.errors.username[0] }}
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
            <div v-if="props.errors?.password" class="text-red-500 text-sm mt-2 bg-red-50 rounded-lg p-3 border border-red-200">
              {{ props.errors.password[0] }}
            </div>
          </div>

          <!-- Submit Button -->
          <div class="pt-6">
            <button 
              type="submit" 
              :disabled="processing"
              class="group w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold py-4 px-6 rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-300 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed relative overflow-hidden"
            >
              <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/50 to-purple-600/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
              <span v-if="processing" class="flex items-center relative z-10">
                <svg class="animate-spin w-5 h-5 ml-3" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                در حال ورود...
              </span>
              <span v-else class="flex items-center relative z-10">
                <svg class="w-5 h-5 ml-3" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M10 17l5-5-5-5v3H3v4h7v3z"/>
                </svg>
                ورود به حساب کاربری
              </span>
            </button>
          </div>

          <!-- Back to Rules Button -->
          <div class="pt-2">
            <button 
              type="button"
              @click="goToRules"
              class="group w-full bg-gradient-to-r from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600 text-white font-medium py-3 px-6 rounded-2xl shadow-md hover:shadow-lg transform hover:scale-[1.01] transition-all duration-300 flex items-center justify-center relative overflow-hidden"
            >
              <div class="absolute inset-0 bg-gradient-to-r from-gray-400/50 to-gray-500/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
              <svg class="w-4 h-4 ml-3 relative z-10" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
              </svg>
              <span class="relative z-10">بازگشت</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

interface FormData {
  username: string
  password: string
}

interface Props {
  errors?: Record<string, string[]>
}

const props = defineProps<Props>()

const form = useForm<FormData>({
  username: '',
  password: ''
})

const processing = computed(() => form.processing)

const submitForm = () => {
  form.post('/login')
}

const goToRules = () => {
  router.visit('/')
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
</style>