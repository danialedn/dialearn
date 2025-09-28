<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 relative overflow-hidden">
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
            <h1 class="text-3xl font-bold bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">فروشگاه</h1>
          </div>
          
          <div class="bg-white/20 backdrop-blur-sm rounded-2xl px-4 py-2 border border-white/30">
            <div class="flex items-center space-x-2 space-x-reverse">
              <span class="text-white font-bold">{{ userCoins }}</span>
              <svg class="w-5 h-5 text-yellow-400 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Store Header -->
      <div class="text-center mb-12">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-3xl mb-6 shadow-2xl">
          <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7Z"/>
          </svg>
        </div>
        <h2 class="text-4xl md:text-5xl font-bold mb-6 bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">
          فروشگاه دیالرن
        </h2>
        <p class="text-xl text-blue-200 max-w-3xl mx-auto leading-relaxed">
          با سکه‌های به دست آمده از بازی، آیتم‌های مختلف خریداری کنید
        </p>
      </div>

      <!-- Categories -->
      <div class="mb-12">
        <div class="flex flex-wrap gap-4 justify-center">
          <button
            v-for="category in categories"
            :key="category.id"
            @click="selectedCategory = category.id"
            class="group px-8 py-4 rounded-2xl font-semibold transition-all duration-300 transform hover:scale-[1.02] relative overflow-hidden"
            :class="[
              selectedCategory === category.id
                ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg'
                : 'bg-white/20 backdrop-blur-sm text-white border border-white/30 hover:bg-white/30'
            ]"
          >
            <div v-if="selectedCategory === category.id" class="absolute inset-0 bg-gradient-to-r from-indigo-600/50 to-purple-600/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <span class="relative z-10">{{ category.name }}</span>
          </button>
        </div>
      </div>

      <!-- Medicine Section -->
      <div v-if="selectedCategory === 'medicines'">
        <h2 class="text-3xl font-bold text-center mb-8 bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">خرید قلب</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="medicine in medicines"
            :key="medicine.id"
            class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-6 hover:bg-white/20 transition-all duration-300 transform hover:scale-105 border border-white/20 group"
          >
            <div class="text-center mb-6">
              <div class="text-5xl mb-4 group-hover:scale-110 transition-transform duration-300">{{ medicine.emoji }}</div>
              <h3 class="text-xl font-bold text-white mb-2">{{ medicine.name }}</h3>
              <p class="text-blue-200 text-sm mb-3">{{ medicine.description }}</p>
              <div class="text-red-400 font-bold text-lg bg-red-500/20 rounded-2xl py-2 px-4 border border-red-400/30">+{{ medicine.lives }} قلب</div>
            </div>
            
            <div class="flex items-center justify-between">
              <div class="flex items-center text-green-400">
                <span class="text-lg font-bold">{{ medicine.price.toLocaleString() }}</span>
                <span class="text-sm mr-1">تومان</span>
              </div>
              
              <button
                @click="purchaseMedicine(medicine)"
                :disabled="processing"
                class="px-6 py-3 rounded-2xl font-medium transition-all duration-300 bg-gradient-to-r from-green-600 to-emerald-600 text-white hover:from-green-700 hover:to-emerald-700 disabled:opacity-50 transform hover:scale-105 shadow-lg shadow-green-500/25 border border-green-400/30"
              >
                <span v-if="processing">در حال پردازش...</span>
                <span v-else>خرید</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Support Section -->
      <div v-if="selectedCategory === 'support'">
        <h2 class="text-3xl font-bold text-center mb-8 bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">حمایت از سازندگان بازی</h2>
        <div class="max-w-2xl mx-auto">
          <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-8 mb-8 border border-white/20">
            <h3 class="text-xl font-bold mb-6 text-white">مبلغ دلخواه</h3>
            <div class="flex gap-4">
              <input
                v-model="customAmount"
                type="number"
                placeholder="مبلغ به تومان"
                class="flex-1 px-4 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-white placeholder-blue-200 transition-all duration-300"
              >
              <button
                @click="customAmount && supportDevelopers(customAmount)"
                :disabled="!customAmount || processing"
                class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-2xl hover:from-purple-700 hover:to-indigo-700 disabled:opacity-50 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-purple-500/25 border border-purple-400/30"
              >
                حمایت
              </button>
            </div>
          </div>
          
          <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
            <h3 class="text-xl font-bold mb-6 text-white">مبالغ پیشنهادی</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <button
                v-for="amount in supportAmounts"
                :key="amount.amount"
                @click="supportDevelopers(amount.amount)"
                :disabled="processing"
                class="p-6 bg-white/10 backdrop-blur-sm border-2 border-purple-400/30 rounded-2xl hover:border-purple-400 hover:bg-purple-500/20 transition-all duration-300 disabled:opacity-50 transform hover:scale-105 group"
              >
                <div class="text-lg font-bold text-purple-300 group-hover:text-purple-200 transition-colors duration-300">{{ amount.label }}</div>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="selectedCategory !== 'medicines' && selectedCategory !== 'support'" class="text-center py-12">
        <div class="text-6xl mb-4">🛍️</div>
        <h3 class="text-xl font-semibold text-white mb-2">دسته‌بندی انتخاب کنید</h3>
        <p class="text-blue-200">لطفاً یکی از دسته‌بندی‌های بالا را انتخاب کنید</p>
      </div>

      <!-- Purchase Success Modal -->
      <div v-if="showSuccessModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 max-w-md mx-4 border border-white/20 shadow-2xl">
          <div class="text-center">
            <div class="text-6xl mb-4">🎉</div>
            <h3 class="text-2xl font-bold text-white mb-2">خرید موفق!</h3>
            <p class="text-blue-200 mb-4">
              {{ purchasedItem?.name }} با موفقیت خریداری شد
            </p>
            <div v-if="purchasedItem?.lives" class="text-green-400 font-bold mb-4 bg-green-500/20 rounded-2xl py-2 px-4 border border-green-400/30">
              +{{ purchasedItem.lives }} جان به حساب شما اضافه شد
            </div>
            <button
              @click="showSuccessModal = false"
              class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-2xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-blue-500/25 border border-blue-400/30"
            >
              تایید
            </button>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

interface Product {
  id: number
  name: string
  description: string
  price: number
  category: string
  emoji: string
  purchased: boolean
}

interface Category {
  id: string
  name: string
}

const selectedCategory = ref('medicines')
const showSuccessModal = ref(false)
const purchasedItem = ref<any | null>(null)
const processing = ref(false)
const userCoins = ref(1000) // مقدار اولیه سکه‌های کاربر
const customAmount = ref<number | null>(null)

const categories: Category[] = [
  { id: 'medicines', name: 'خرید قلب' },
  { id: 'support', name: 'حمایت از سازندگان' }
]

const medicines = ref([
  {
    id: 1,
    name: 'یک قلب',
    lives: 1,
    price: 5000,
    category: 'medicines',
    emoji: '❤️',
    description: '۱ قلب اضافه می‌کند',
    purchased: false
  },
  {
    id: 2,
    name: 'دو قلب',
    lives: 2,
    price: 10000,
    category: 'medicines',
    emoji: '💕',
    description: '۲ قلب اضافه می‌کند',
    purchased: false
  },
  {
    id: 3,
    name: 'سه قلب',
    lives: 3,
    price: 15000,
    category: 'medicines',
    emoji: '💖',
    description: '۳ قلب اضافه می‌کند',
    purchased: false
  },
  {
    id: 4,
    name: 'چهار قلب',
    lives: 4,
    price: 20000,
    category: 'medicines',
    emoji: '💗',
    description: '۴ قلب اضافه می‌کند',
    purchased: false
  },
  {
    id: 5,
    name: 'پنج قلب',
    lives: 5,
    price: 25000,
    category: 'medicines',
    emoji: '💝',
    description: '۵ قلب اضافه می‌کند',
    purchased: false
  },
  {
    id: 6,
    name: 'شش قلب',
    lives: 6,
    price: 30000,
    category: 'medicines',
    emoji: '💞',
    description: '۶ قلب اضافه می‌کند',
    purchased: false
  }
])

const supportAmounts = ref([
  { amount: 10000, label: '۱۰,۰۰۰ تومان', category: 'support' },
  { amount: 20000, label: '۲۰,۰۰۰ تومان', category: 'support' },
  { amount: 50000, label: '۵۰,۰۰۰ تومان', category: 'support' },
  { amount: 100000, label: '۱۰۰,۰۰۰ تومان', category: 'support' },
  { amount: 200000, label: '۲۰۰,۰۰۰ تومان', category: 'support' }
])



const purchaseMedicine = async (medicine: any) => {
  if (!processing.value) {
    processing.value = true
    
    try {
      const response = await fetch('/api/game/buy-hearts', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
          hearts: medicine.lives
        })
      })
      
      const data = await response.json()
      
      if (data.success) {
        purchasedItem.value = medicine
        showSuccessModal.value = true
        
        // Redirect to payment gateway or show success
        alert(`خرید ${medicine.lives} قلب با مبلغ ${medicine.price.toLocaleString()} تومان انجام شد.`)
      } else {
        alert('خطا در خرید قلب‌ها')
      }
    } catch (error) {
      console.error('Error purchasing hearts:', error)
      alert('خطا در اتصال به سرور')
    } finally {
      processing.value = false
    }
  }
}

const supportDevelopers = (amount: number) => {
  if (amount > 0 && !processing.value) {
    processing.value = true
    
    setTimeout(() => {
      // Here you would integrate with payment gateway
      // Example: initiatePayment(amount)
      processing.value = false
      customAmount.value = null
      
      // Show success message
      alert(`حمایت ${amount.toLocaleString()} تومانی شما ثبت شد. متشکریم!`)
    }, 1000)
  }
}

const goBack = () => {
  router.visit('/dashboard')
}

// Load user coins and purchased items on mount
onMounted(() => {
  // This would typically come from an API call
  // Example: loadUserData()
})
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