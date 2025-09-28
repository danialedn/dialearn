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

    <div class="relative z-10 max-w-4xl mx-auto p-4 sm:p-6 lg:p-8">
      <!-- Back Button -->
      <div class="mb-6">
        <button 
          @click="goToStages"
          class="group flex items-center bg-white/20 backdrop-blur-sm rounded-2xl px-4 py-2 border border-white/30 hover:bg-white/30 transition-all duration-300"
        >
          <svg class="w-5 h-5 ml-2 text-white group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
          </svg>
          <span class="text-white font-medium">بازگشت به مراحل</span>
        </button>
      </div>

      <!-- Header -->
      <div v-if="stage" class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-8 mb-8 border border-white/20">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-white mb-2">{{ stage.name }}</h1>
            <p class="text-blue-200 text-lg">{{ stage.description }}</p>
          </div>
          <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg mb-2">
              <span class="text-white font-bold text-xl">{{ stage.level }}</span>
            </div>
            <div class="text-blue-200 text-sm font-medium">مرحله</div>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-16">
        <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-12 max-w-md mx-auto border border-white/20">
          <div class="relative w-16 h-16 mx-auto mb-6">
            <div class="absolute inset-0 rounded-full border-4 border-indigo-500/30"></div>
            <div class="absolute inset-0 rounded-full border-4 border-indigo-500 border-t-transparent animate-spin"></div>
          </div>
          <p class="text-white text-lg font-medium">در حال بارگذاری سوالات...</p>
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
          <p class="text-red-200 text-lg font-medium mb-4">{{ error }}</p>
          <button 
            @click="fetchQuestions"
            class="bg-red-500/30 hover:bg-red-500/50 text-white px-6 py-3 rounded-2xl font-semibold transition-all duration-300"
          >
            تلاش مجدد
          </button>
        </div>
      </div>

      <!-- Question Card -->
      <div v-else-if="currentQuestion" class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-8 mb-8 border border-white/20">
        <!-- Timer and Score Section -->
        <div class="flex justify-between items-center mb-6">
          <!-- Timer -->
          <div class="bg-gradient-to-r from-red-500/20 to-orange-500/20 backdrop-blur-sm rounded-2xl px-6 py-3 border border-red-400/30">
            <div class="flex items-center space-x-3 space-x-reverse">
              <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span class="text-white font-bold text-xl">{{ timeLeft }}</span>
            </div>
          </div>
          
          <!-- Score Display -->
          <div class="flex space-x-4 space-x-reverse">
            <!-- Correct Answers -->
            <div class="bg-gradient-to-r from-green-500/20 to-emerald-500/20 backdrop-blur-sm rounded-2xl px-4 py-2 border border-green-400/30">
              <div class="flex items-center space-x-2 space-x-reverse">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="text-green-200 font-semibold">{{ correctAnswers }}</span>
              </div>
            </div>
            
            <!-- Wrong Answers -->
            <div class="bg-gradient-to-r from-red-500/20 to-pink-500/20 backdrop-blur-sm rounded-2xl px-4 py-2 border border-red-400/30">
              <div class="flex items-center space-x-2 space-x-reverse">
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span class="text-red-200 font-semibold">{{ wrongAnswers }}</span>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Progress Section -->
        <div class="mb-8">
          <div class="flex justify-between items-center mb-6">
            <div class="bg-white/20 backdrop-blur-sm rounded-2xl px-4 py-2 border border-white/30">
              <span class="text-white font-semibold">سوال {{ currentQuestionIndex + 1 }} از {{ questions.length }}</span>
            </div>
            <div class="bg-white/20 backdrop-blur-sm rounded-2xl px-4 py-2 border border-white/30">
              <span class="text-blue-200">پیشرفت: {{ Math.round(((currentQuestionIndex + 1) / questions.length) * 100) }}%</span>
            </div>
          </div>
          
          <!-- Progress Bar -->
          <div class="w-full bg-white/20 rounded-full h-3 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-3 rounded-full transition-all duration-500 ease-out relative overflow-hidden" 
                 :style="{ width: ((currentQuestionIndex + 1) / questions.length) * 100 + '%' }">
              <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-pulse"></div>
            </div>
          </div>
        </div>

        <!-- Question -->
        <div class="text-center mb-8">
          <h2 class="text-2xl md:text-3xl font-bold text-white mb-4 leading-relaxed">{{ currentQuestion.question }}</h2>
        </div>

        <!-- Help Options -->
        <div class="mb-6">
          <div class="flex justify-center space-x-4 space-x-reverse">
            <!-- Remove Wrong Options -->
            <button
              @click="removeWrongOptions"
              :disabled="helpUsed.removeWrong || removedOptions.length > 0"
              class="group flex items-center bg-gradient-to-r from-yellow-600/20 to-orange-600/20 backdrop-blur-sm rounded-2xl px-4 py-3 border border-yellow-400/30 hover:from-yellow-600/30 hover:to-orange-600/30 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg class="w-5 h-5 ml-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16" />
              </svg>
              <div class="text-right">
                <div class="text-yellow-200 font-medium text-sm">حذف دو گزینه اشتباه</div>
                <div class="text-yellow-300 text-xs">هزینه: 1 جان</div>
              </div>
            </button>

            <!-- Skip Question -->
            <button
              @click="skipQuestion"
              :disabled="helpUsed.skipQuestion"
              class="group flex items-center bg-gradient-to-r from-purple-600/20 to-indigo-600/20 backdrop-blur-sm rounded-2xl px-4 py-3 border border-purple-400/30 hover:from-purple-600/30 hover:to-indigo-600/30 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg class="w-5 h-5 ml-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3-3 3m-4-6l3 3-3 3m-2-3h8m6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div class="text-right">
                <div class="text-purple-200 font-medium text-sm">رد کردن سوال</div>
                <div class="text-purple-300 text-xs">هزینه: 2 جان</div>
              </div>
            </button>
          </div>
        </div>

        <!-- Game Over Dialog -->
        <div v-if="gameOver" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm">
          <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-3xl p-8 max-w-md mx-4 border border-red-400/30 shadow-2xl">
            <div class="text-center">
              <div class="w-20 h-20 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
              </div>
              <h3 class="text-2xl font-bold text-white mb-4">بازی تمام شد!</h3>
              <p class="text-gray-300 mb-6">شما دو پاسخ غلط دادید. برای ادامه بازی نیاز به جان دارید.</p>
              <div class="flex flex-col space-y-3">
                <button
                  @click="showContinueDialog = true; gameOver = false"
                  class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white py-3 px-6 rounded-2xl font-semibold transition-all duration-300"
                >
                  ادامه با از دست دادن جان
                </button>
                <button
                  @click="showAnswerAndContinue"
                  class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white py-3 px-6 rounded-2xl font-semibold transition-all duration-300"
                >
                  نمایش پاسخ صحیح و رفتن به مرحله بعدی
                </button>
                <button
                  @click="goToStore"
                  class="w-full bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white py-3 px-6 rounded-2xl font-semibold transition-all duration-300"
                >
                  رفتن به فروشگاه
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Continue Dialog -->
        <div v-if="showContinueDialog" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm">
          <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-3xl p-8 max-w-md mx-4 border border-yellow-400/30 shadow-2xl">
            <div class="text-center">
              <div class="w-20 h-20 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
              </div>
              <h3 class="text-2xl font-bold text-white mb-4">ادامه بازی</h3>
              <p class="text-gray-300 mb-6">آیا می‌خواهید با از دست دادن یک جان به بازی ادامه دهید؟</p>
              <div class="flex space-x-3 space-x-reverse">
                <button
                  @click="continueGame"
                  class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white py-3 px-6 rounded-2xl font-semibold transition-all duration-300"
                >
                  بله
                </button>
                <button
                  @click="goToStore"
                  class="flex-1 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white py-3 px-6 rounded-2xl font-semibold transition-all duration-300"
                >
                  خیر
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Answer Animation Overlay -->
        <div v-if="showAnswerAnimation" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
          <div class="text-center">
            <div v-if="isAnswerCorrect" class="animate-bounce">
              <div class="w-32 h-32 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-2xl">
                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <h3 class="text-4xl font-bold text-green-400 mb-2">{{ gameOver ? 'پاسخ صحیح' : 'آفرین!' }}</h3>
              <p class="text-xl text-green-200">{{ gameOver ? 'انتقال به مرحله بعدی...' : 'پاسخ صحیح است' }}</p>
            </div>
            
            <div v-else class="animate-pulse">
              <div class="w-32 h-32 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-2xl">
                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </div>
              <h3 class="text-4xl font-bold text-red-400 mb-2">اشتباه!</h3>
              <p class="text-xl text-red-200">پاسخ نادرست است</p>
            </div>
          </div>
        </div>

        <!-- Options -->
        <div class="space-y-4 mb-8">
          <button
            v-for="(option, index) in currentQuestion.options"
            :key="index"
            v-show="!removedOptions.includes(index)"
            @click="selectAnswer(index)"
            class="group w-full p-6 text-right rounded-2xl border-2 transition-all duration-300 transform hover:scale-[1.02] relative overflow-hidden"
            :class="[
              selectedAnswer === index
                ? 'border-indigo-400 bg-indigo-500/20 text-white shadow-lg'
                : 'border-white/30 bg-white/10 text-blue-100 hover:border-indigo-300 hover:bg-white/20'
            ]"
          >
            <div v-if="selectedAnswer === index" class="absolute inset-0 bg-gradient-to-r from-indigo-500/20 to-purple-500/20"></div>
            <div class="relative z-10 flex items-center space-x-4 space-x-reverse">
              <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold text-lg"
                   :class="selectedAnswer === index ? 'bg-indigo-500 text-white' : 'bg-white/20 text-blue-200'">
                {{ String.fromCharCode(65 + index) }}
              </div>
              <span class="font-medium text-lg">{{ option }}</span>
            </div>
          </button>
        </div>

        <!-- Navigation -->
        <div class="flex justify-between space-x-4 space-x-reverse">
          <button
            @click="previousQuestion"
            :disabled="currentQuestionIndex === 0"
            class="group px-8 py-4 bg-gray-600/50 text-white rounded-2xl disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-600/70 transition-all duration-300 transform hover:scale-[1.02] font-semibold relative overflow-hidden"
          >
            <div class="absolute inset-0 bg-gradient-to-r from-gray-600/50 to-gray-700/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10 flex items-center space-x-2 space-x-reverse">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
              </svg>
              <span>قبلی</span>
            </div>
          </button>
          
          <button
            @click="nextQuestion"
            :disabled="selectedAnswer === null"
            class="group px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl disabled:opacity-50 disabled:cursor-not-allowed hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-[1.02] font-semibold shadow-lg relative overflow-hidden"
          >
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/50 to-purple-600/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10 flex items-center space-x-2 space-x-reverse">
              <span>{{ currentQuestionIndex === questions.length - 1 ? 'تمام' : 'بعدی' }}</span>
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M4 11v2h12.17l-5.59 5.59L12 20l8-8-8-8-1.41 1.41L16.17 11H4z"/>
              </svg>
            </div>
          </button>
        </div>
      </div>


    </div>

    <!-- Full-Screen Congratulations Modal -->
    <div v-if="showResults" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm">
      <div class="relative w-full h-full flex items-center justify-center p-4">
        <!-- Animated background elements for modal -->
        <div class="absolute inset-0 overflow-hidden">
          <!-- Diabetes-related floating elements -->
          <!-- Blood glucose molecules -->
          <div class="absolute top-1/4 left-1/4 w-8 h-8 bg-blue-400/60 rounded-full animate-float-glucose"></div>
          <div class="absolute top-1/3 right-1/3 w-6 h-6 bg-blue-500/50 rounded-full animate-float-glucose" style="animation-delay: 1s"></div>
          <div class="absolute bottom-1/3 left-1/5 w-10 h-10 bg-blue-300/40 rounded-full animate-float-glucose" style="animation-delay: 2s"></div>
          
          <!-- Insulin syringes -->
          <div class="absolute top-1/5 right-1/4 animate-float-insulin">
            <svg class="w-12 h-12 text-green-400/60" fill="currentColor" viewBox="0 0 24 24">
              <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-8 14H9v-2h2v2zm0-4H9V9h2v4zm4 4h-2v-2h2v2zm0-4h-2V9h2v4z"/>
            </svg>
          </div>
          <div class="absolute bottom-1/4 right-1/5 animate-float-insulin" style="animation-delay: 1.5s">
            <svg class="w-10 h-10 text-green-500/50" fill="currentColor" viewBox="0 0 24 24">
              <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-8 14H9v-2h2v2zm0-4H9V9h2v4zm4 4h-2v-2h2v2zm0-4h-2V9h2v4z"/>
            </svg>
          </div>
          
          <!-- Healthy hearts -->
          <div class="absolute top-2/3 left-1/4 animate-heartbeat">
            <svg class="w-14 h-14 text-red-400/60" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
          </div>
          <div class="absolute top-1/6 left-2/3 animate-heartbeat" style="animation-delay: 0.5s">
            <svg class="w-10 h-10 text-red-500/50" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
          </div>
          
          <!-- Healthy food icons -->
          <div class="absolute bottom-1/5 left-1/3 animate-float-food">
            <svg class="w-12 h-12 text-green-400/60" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
          </div>
          <div class="absolute top-3/4 right-1/3 animate-float-food" style="animation-delay: 1s">
            <svg class="w-8 h-8 text-orange-400/50" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
          </div>
          
          <!-- Background gradient circles -->
          <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-green-500/20 rounded-full blur-3xl animate-pulse"></div>
          <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s"></div>
          <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-green-400/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s"></div>
        </div>

        <!-- Modal Content -->
        <div class="relative z-10 bg-gradient-to-br from-green-900/90 via-emerald-900/90 to-green-900/90 backdrop-blur-xl rounded-3xl shadow-2xl p-12 max-w-2xl w-full border border-green-400/30 text-center animate-fadeInUp">
          <!-- Celebration Icon -->
          <div class="w-32 h-32 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-8 shadow-2xl animate-bounce">
            <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>
          </div>
          
          <!-- Congratulations Title -->
          <h2 class="text-5xl md:text-6xl font-bold mb-6 bg-gradient-to-r from-white to-green-200 bg-clip-text text-transparent">
            🎉 تبریک! 🎉
          </h2>
          
          <!-- Success Message -->
          <p class="text-2xl text-green-200 mb-8 leading-relaxed">
            شما این مرحله را با موفقیت تمام کردید!
          </p>
          
          <!-- Score Display -->
          <div class="bg-gradient-to-r from-green-500/30 to-emerald-500/30 rounded-3xl p-8 mb-10 border border-green-400/50">
            <div class="text-7xl font-bold text-green-300 mb-3">{{ score }}%</div>
            <p class="text-green-200 text-xl">امتیاز شما در این مرحله</p>
          </div>
          
          <!-- Next Stage Guidance -->
          <div v-if="hasNextStage" class="mb-8">
            <p class="text-xl text-white mb-6">آماده برای چالش بعدی هستید؟</p>
            <button
              @click="goToNextStage"
              class="group px-12 py-5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-3xl font-bold text-xl transition-all duration-300 transform hover:scale-105 shadow-2xl relative overflow-hidden"
            >
              <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/50 to-purple-600/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
              <div class="relative z-10 flex items-center space-x-3 space-x-reverse">
                <span>شروع مرحله بعدی</span>
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M4 11v2h12.17l-5.59 5.59L12 20l8-8-8-8-1.41 1.41L16.17 11H4z"/>
                </svg>
              </div>
            </button>
          </div>

          <!-- Completion Message (if no next stage) -->
          <div v-else class="mb-8">
            <p class="text-xl text-white mb-6">🏆 تبریک! شما تمام مراحل را تکمیل کردید!</p>
          </div>
          
          <!-- Alternative Actions -->
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button
              @click="goToStages"
              class="group px-8 py-4 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white rounded-2xl font-semibold text-lg transition-all duration-300 transform hover:scale-[1.02] border border-white/30"
            >
              <div class="flex items-center space-x-2 space-x-reverse">
                <span>بازگشت به فهرست مراحل</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M3 13h8V3H9v6H3v4zm0 8h6v-6H3v6zm10 0h8v-6h-2v4h-6v2zm8-8V9h-6v4h6z"/>
                </svg>
              </div>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed, onUnmounted, watch } from 'vue'
import { router } from '@inertiajs/vue3'

interface Question {
  id: number
  question: string
  options: string[]
  correct_answer: number | string
  stage_id: number
}

interface Stage {
  id: number
  name: string
  description: string
  level: number
  difficulty: number
  questions_count: number
}

const props = defineProps<{
  stage: Stage
  stageId: string
}>()

const questions = ref<Question[]>([])
const currentQuestionIndex = ref(0)
const selectedAnswer = ref<number | null>(null)
const answers = ref<(number | null)[]>([])
const showResults = ref(false)
const loading = ref(true)
const error = ref<string | null>(null)
const hasNextStage = ref(false)

// Timer and game state
const timeLeft = ref(30) // 30 seconds per question
const timerInterval = ref<number | null>(null)
const correctAnswers = ref(0)
const wrongAnswers = ref(0)
const showAnswerAnimation = ref(false)
const isAnswerCorrect = ref(false)
const gameOver = ref(false)
const showContinueDialog = ref(false)

// Help options state
const helpUsed = ref({
  removeWrong: false,
  skipQuestion: false
})
const removedOptions = ref<number[]>([])

const currentQuestion = computed(() => {
  return questions.value[currentQuestionIndex.value] || null
})

const score = computed(() => {
  if (questions.value.length === 0) return 0
  const correct = answers.value.filter((answer, index) => {
    return answer !== null && answer === Number(questions.value[index]?.correct_answer)
  }).length
  return Math.round((correct / questions.value.length) * 100)
})

const selectAnswer = (index: number) => {
  if (showAnswerAnimation.value || gameOver.value) return
  
  selectedAnswer.value = index
  stopTimer()
  
  // Check if answer is correct - compare index directly with correct_answer
  console.log('Debug: Selected index:', index, 'Correct answer:', currentQuestion.value.correct_answer, 'Question:', currentQuestion.value.question)
  const correct = Number(currentQuestion.value.correct_answer) === index
  console.log('Debug: Is correct?', correct)
  isAnswerCorrect.value = correct
  
  if (correct) {
    correctAnswers.value++
  } else {
    wrongAnswers.value++
    
    // Check for game over (2 wrong answers)
    if (wrongAnswers.value >= 2) {
      showAnswerAnimation.value = true
      setTimeout(() => {
        showAnswerAnimation.value = false
        gameOver.value = true
      }, 2000)
      return
    }
  }
  
  // Show animation
  showAnswerAnimation.value = true
  setTimeout(async () => {
    showAnswerAnimation.value = false
    
    // Save the answer
    answers.value[currentQuestionIndex.value] = selectedAnswer.value
    
    // Check if stage should be completed
    await checkStageCompletion()
    
    // Only move to next question if results are not showing and not the last question
    if (!showResults.value && currentQuestionIndex.value < questions.value.length - 1) {
      currentQuestionIndex.value++
      selectedAnswer.value = null
      removedOptions.value = []
      helpUsed.value.removeWrong = false
      helpUsed.value.skipQuestion = false
      startTimer()
    }
  }, 2000)
}

const startTimer = () => {
  timeLeft.value = 30
  timerInterval.value = setInterval(() => {
    timeLeft.value--
    if (timeLeft.value <= 0) {
      stopTimer()
      // Auto select wrong answer when time runs out
      wrongAnswers.value++
      isAnswerCorrect.value = false
      
      if (wrongAnswers.value >= 2) {
        showAnswerAnimation.value = true
        setTimeout(() => {
          showAnswerAnimation.value = false
          gameOver.value = true
        }, 2000)
        return
      }
      
      showAnswerAnimation.value = true
      setTimeout(() => {
        showAnswerAnimation.value = false
        nextQuestion()
      }, 2000)
    }
  }, 1000)
}

const stopTimer = () => {
  if (timerInterval.value) {
    clearInterval(timerInterval.value)
    timerInterval.value = null
  }
}

const continueGame = async () => {
  try {
    // Call API to use a heart/life
    const response = await fetch('/api/game/use-heart', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        'Accept': 'application/json'
      },
      credentials: 'same-origin'
    })
    
    const data = await response.json()
    
    if (response.ok && data.success) {
      // Reset game state
      wrongAnswers.value = 0
      gameOver.value = false
      showContinueDialog.value = false
      startTimer()
    } else if (response.status === 400 && data.error === 'No hearts available') {
      // No hearts left, show message and go to store
      alert('جان‌های شما تمام شده است! برای ادامه بازی به فروشگاه مراجعه کنید.')
      goToStore()
    } else {
      // Other error, show generic message
      alert('خطا در استفاده از جان. لطفاً دوباره تلاش کنید.')
      showContinueDialog.value = false
    }
  } catch (error) {
    console.error('Error using heart:', error)
    alert('خطا در اتصال به سرور. لطفاً دوباره تلاش کنید.')
    showContinueDialog.value = false
  }
}

const goToStore = () => {
  // Navigate to store page
  window.location.href = '/store'
}



// Help option functions
const removeWrongOptions = async () => {
  if (helpUsed.value.removeWrong || removedOptions.value.length > 0) return
  
  try {
    // Use 1 heart for this help option
    const response = await fetch('/api/game/use-heart', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        'Accept': 'application/json'
      }
    })
    
    // Check if response is HTML (redirect to login)
    const contentType = response.headers.get('content-type')
    if (contentType && contentType.includes('text/html')) {
      alert('لطفاً ابتدا وارد حساب کاربری خود شوید')
      window.location.href = '/login'
      return
    }
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }
    
    const result = await response.json()
    
    if (!result.success) {
      alert(result.error || result.message || 'جان کافی برای استفاده از این کمک ندارید')
      return
    }
    
    const correctAnswer = Number(currentQuestion.value.correct_answer)
    const wrongOptions: number[] = []
    
    // Find all wrong options
    for (let i = 0; i < currentQuestion.value.options.length; i++) {
      if (i !== correctAnswer) {
        wrongOptions.push(i)
      }
    }
    
    // Randomly select 2 wrong options to remove
    const shuffled = wrongOptions.sort(() => 0.5 - Math.random())
    const optionsToRemove = shuffled.slice(0, 2)
    
    removedOptions.value = optionsToRemove
    helpUsed.value.removeWrong = true
    
    console.log('Used remove wrong options help - 1 heart deducted')
  } catch (error) {
    console.error('Error using heart:', error)
    alert('خطا در استفاده از جان. لطفاً دوباره تلاش کنید.')
  }
}

const skipQuestion = async () => {
  if (helpUsed.value.skipQuestion) return
  
  try {
    // Use 2 hearts for this help option (call API twice)
    for (let i = 0; i < 2; i++) {
      const response = await fetch('/api/game/use-heart', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
          'Accept': 'application/json'
        }
      })
      
      // Check if response is HTML (redirect to login)
      const contentType = response.headers.get('content-type')
      if (contentType && contentType.includes('text/html')) {
        alert('لطفاً ابتدا وارد حساب کاربری خود شوید')
        window.location.href = '/login'
        return
      }
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      
      const result = await response.json()
      
      if (!result.success) {
        alert(result.error || result.message || 'جان کافی برای استفاده از این کمک ندارید')
        return
      }
    }
    
    helpUsed.value.skipQuestion = true
    console.log('Used skip question help - 2 hearts deducted')
    
    // Move to next question or complete stage
    if (currentQuestionIndex.value === questions.value.length - 1) {
      // Last question - complete stage
      stopTimer()
      await submitStageAnswers()
      showResults.value = true
    } else {
      // Move to next question
      stopTimer()
      currentQuestionIndex.value++
      selectedAnswer.value = null
      removedOptions.value = [] // Reset removed options for new question
      startTimer()
    }
  } catch (error) {
    console.error('Error using hearts:', error)
    alert('خطا در استفاده از جان. لطفاً دوباره تلاش کنید.')
  }
}

const nextQuestion = async () => {
  // Save the current answer if one was selected
  if (selectedAnswer.value !== null) {
    answers.value[currentQuestionIndex.value] = selectedAnswer.value
  }
  
  if (currentQuestionIndex.value === questions.value.length - 1) {
    // Submit all answers and complete the stage
    stopTimer()
    await submitStageAnswers();
    showResults.value = true
  } else {
    currentQuestionIndex.value++
    selectedAnswer.value = answers.value[currentQuestionIndex.value] ?? null
    // Reset help options state for new question
    removedOptions.value = []
    helpUsed.value.removeWrong = false
    helpUsed.value.skipQuestion = false
    startTimer() // Start timer for new question
  }
}

const previousQuestion = () => {
  if (currentQuestionIndex.value > 0) {
    stopTimer()
    currentQuestionIndex.value--
    selectedAnswer.value = answers.value[currentQuestionIndex.value] ?? null
    // Reset help options state for previous question
    removedOptions.value = []
    helpUsed.value.removeWrong = false
    helpUsed.value.skipQuestion = false
    startTimer()
  }
}

const goToNextStage = async () => {
  try {
    const currentPath = window.location.pathname;
    const basePath = currentPath.includes('/game') ? '/game' : '/quiz';
    
    // First, get the current stage data to check completion status
    const currentStageResponse = await fetch(`/api/stages/${props.stageId}`, {
      headers: {
        'Accept': 'application/json'
      },
      credentials: 'same-origin'
    });
    
    if (!currentStageResponse.ok) {
      console.error('Failed to fetch current stage data');
      router.visit(basePath);
      return;
    }
    
    const currentStageData = await currentStageResponse.json();
    
    // Check if current stage is completed
    if (!currentStageData.stage.is_completed) {
      console.log('Current stage is not completed yet');
      return;
    }
    
    // Now get all stages to find the next one
    const stagesResponse = await fetch('/api/stages', {
      headers: {
        'Accept': 'application/json'
      },
      credentials: 'same-origin'
    });
    
    if (stagesResponse.ok) {
      const stages = await stagesResponse.json();
      const nextStage = stages.find((s: any) => s.level === currentStageData.stage.level + 1);
      
      if (nextStage) {
        // Navigate to next stage
        router.visit(`${basePath}/stage/${nextStage.id}`);
      } else {
        // No more stages, go back to stages list
        router.visit(basePath);
      }
    } else {
      // Fallback to stages list
      router.visit(basePath);
    }
  } catch (error) {
    console.error('Error fetching next stage:', error);
    // Fallback to stages list
    const currentPath = window.location.pathname;
    const basePath = currentPath.includes('/game') ? '/game' : '/quiz';
    router.visit(basePath);
  }
}

const goToStages = () => {
  // Check current route to determine which path to use
  const currentPath = window.location.pathname;
  const basePath = currentPath.includes('/game') ? '/game' : '/quiz';
  router.visit(basePath);
}

const checkStageCompletion = async () => {
  // Check if all questions have been answered or if we should complete the stage
  const totalQuestions = questions.value.length;
  const answeredQuestions = answers.value.filter(answer => answer !== null && answer !== undefined).length;
  
  // Complete stage if all questions answered or if we're at the last question
  if (answeredQuestions === totalQuestions || currentQuestionIndex.value >= totalQuestions - 1) {
    stopTimer();
    await submitStageAnswers();
    showResults.value = true;
  }
}

const submitStageAnswers = async () => {
  try {
    // Submit each answer to the server
    for (let i = 0; i < questions.value.length; i++) {
      const question = questions.value[i];
      const userAnswer = answers.value[i];
      
      // Convert answer index to letter (0 -> A, 1 -> B, etc.)
      // If no answer was selected, default to 'A' (will be marked as incorrect)
      const answerLetter = userAnswer !== null && userAnswer !== undefined 
        ? String.fromCharCode(65 + userAnswer)
        : 'A';
      
      const response = await fetch(`/api/questions/${question.id}/answer`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
          'Accept': 'application/json',
        },
        credentials: 'same-origin',
        body: JSON.stringify({
          answer: answerLetter
        })
      });
      
      if (!response.ok) {
        console.error(`Failed to submit answer for question ${question.id}`);
      }
    }
    
    console.log('All answers submitted successfully');
  } catch (error) {
    console.error('Error submitting answers:', error);
  }
};

const fetchQuestions = async () => {
  try {
    error.value = null
    const response = await fetch(`/api/stages/${props.stageId}/questions`, {
      headers: {
        'Accept': 'application/json'
      },
      credentials: 'same-origin'
    })
    if (response.ok) {
      const data = await response.json()
      questions.value = data
      if (data.length === 0) {
        error.value = 'هیچ سوالی برای این مرحله یافت نشد'
      }
    } else {
      error.value = 'خطا در بارگذاری سوالات'
    }
    
    // Check if next stage exists
    await checkNextStage();
  } catch (err) {
    error.value = 'خطا در اتصال به سرور'
    console.error('Error fetching questions:', err)
  } finally {
    loading.value = false
  }
}

const checkNextStage = async () => {
  try {
    const response = await fetch('/api/stages', {
      headers: {
        'Accept': 'application/json'
      },
      credentials: 'same-origin'
    });
    if (response.ok) {
      const stages = await response.json();
      const currentStageData = stages.find((s: any) => s.id === props.stageId);
      
      if (currentStageData) {
        const nextStage = stages.find((s: any) => s.level === currentStageData.level + 1);
        hasNextStage.value = !!nextStage;
      }
    }
  } catch (error) {
    console.error('Error checking next stage:', error);
  }
}

onMounted(async () => {
  // Check if we should show results directly
  const urlParams = new URLSearchParams(window.location.search);
  const viewParam = urlParams.get('view');
  
  if (viewParam === 'results') {
    // Check if stage is completed and show results
    try {
      const response = await fetch(`/api/stages/${props.stageId}`, {
        headers: {
          'Accept': 'application/json'
        },
        credentials: 'same-origin'
      });
      
      if (response.ok) {
        const stageData = await response.json();
        if (stageData.stage.is_completed) {
          // Load questions and show results
          await fetchQuestions();
          showResults.value = true;
          return;
        }
      }
    } catch (error) {
      console.error('Error checking stage completion:', error);
    }
  }
  
  // Normal flow - load questions and start quiz
  fetchQuestions();
})

// Start timer when questions are loaded
watch(questions, (newQuestions) => {
  if (newQuestions.length > 0 && !loading.value) {
    startTimer()
  }
})

// Cleanup timer on unmount
onUnmounted(() => {
  stopTimer()
})
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

/* Persian text styling */
h1, h2, h3, h4, h5, h6, p, span, div {
  font-feature-settings: 'kern' 1, 'liga' 1;
  text-rendering: optimizeLegibility;
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

/* Diabetes-related animations */
@keyframes floatGlucose {
  0%, 100% {
    transform: translateY(0px) translateX(0px) scale(1);
    opacity: 0.6;
  }
  25% {
    transform: translateY(-20px) translateX(10px) scale(1.1);
    opacity: 0.8;
  }
  50% {
    transform: translateY(-10px) translateX(-15px) scale(0.9);
    opacity: 0.7;
  }
  75% {
    transform: translateY(-25px) translateX(5px) scale(1.05);
    opacity: 0.9;
  }
}

@keyframes floatInsulin {
  0%, 100% {
    transform: translateY(0px) rotate(0deg);
    opacity: 0.6;
  }
  33% {
    transform: translateY(-15px) rotate(5deg);
    opacity: 0.8;
  }
  66% {
    transform: translateY(-8px) rotate(-3deg);
    opacity: 0.7;
  }
}

@keyframes heartbeat {
  0%, 100% {
    transform: scale(1);
    opacity: 0.6;
  }
  14% {
    transform: scale(1.2);
    opacity: 0.9;
  }
  28% {
    transform: scale(1);
    opacity: 0.7;
  }
  42% {
    transform: scale(1.15);
    opacity: 0.8;
  }
  56% {
    transform: scale(1);
    opacity: 0.6;
  }
}

@keyframes floatFood {
  0%, 100% {
    transform: translateY(0px) rotate(0deg) scale(1);
    opacity: 0.6;
  }
  50% {
    transform: translateY(-12px) rotate(180deg) scale(1.1);
    opacity: 0.8;
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

.animate-float-glucose {
  animation: floatGlucose 4s ease-in-out infinite;
}

.animate-float-insulin {
  animation: floatInsulin 5s ease-in-out infinite;
}

.animate-heartbeat {
  animation: heartbeat 2s ease-in-out infinite;
}

.animate-float-food {
  animation: floatFood 6s ease-in-out infinite;
}

/* Custom hover effects */
.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}

.group:hover .group-hover\:opacity-100 {
  opacity: 1;
}
</style>