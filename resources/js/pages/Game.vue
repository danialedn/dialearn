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
    <header class="relative z-10 bg-white/10 backdrop-blur-xl border-b border-white/20 rtl font-persian">
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
            <h1 class="text-3xl font-bold bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">دیالرن</h1>
            <p class="text-blue-200 text-sm">بازی آموزشی</p>
          </div>
          
          <div class="flex items-center space-x-4 space-x-reverse">
            <!-- Mute/Unmute Button -->
            <button 
              @click="toggleMute"
              class="group p-3 rounded-2xl transition-all duration-300 border border-white/30"
              :class="isMuted ? 'bg-red-500/20 hover:bg-red-500/30' : 'bg-green-500/20 hover:bg-green-500/30'"
              :title="isMuted ? 'روشن کردن صدا' : 'خاموش کردن صدا'"
            >
              <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-300" :class="isMuted ? 'text-red-400' : 'text-green-400'" fill="currentColor" viewBox="0 0 24 24">
                <path v-if="!isMuted" d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
                <path v-else d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/>
              </svg>
            </button>
            
            <!-- Lives Display -->
            <div class="bg-white/20 backdrop-blur-sm rounded-2xl px-4 py-2 border border-white/30">
              <div class="flex items-center space-x-2 space-x-reverse">
                <span class="text-white font-bold">{{ gameStatus.hearts }}</span>
                <svg class="w-5 h-5 text-red-400 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Game Start Screen -->
      <div v-if="gameState === 'start'" class="text-center rtl font-persian">
        <!-- Game Logo -->
        <div class="w-32 h-32 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-2xl border border-white/30">
          <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M8 5v14l11-7z"/>
          </svg>
        </div>
        
        <h2 class="text-5xl font-bold mb-4 bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">بازی دیالرن</h2>
        <p class="text-xl text-blue-200 mb-12 max-w-2xl mx-auto">آماده‌اید تا دانش خود را درباره دیابت آزمایش کنید؟</p>
        
        <!-- Game Rules -->
        <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-8 mb-12 text-right rtl font-persian border border-white/20">
          <h3 class="text-3xl font-bold text-white mb-8 text-center">قوانین بازی</h3>
          <div class="grid md:grid-cols-2 gap-6">
            <div class="space-y-6">
              <div class="flex items-start space-x-4 space-x-reverse">
                <div class="w-12 h-12 bg-indigo-500/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                  <span class="text-indigo-400 font-bold text-lg">۱</span>
                </div>
                <div>
                  <h4 class="text-white font-semibold mb-2">مراحل بازی</h4>
                  <p class="text-blue-200">بازی شامل ۱۵ مرحله است (قابل افزایش تا ۳۰ مرحله)</p>
                </div>
              </div>
              
              <div class="flex items-start space-x-4 space-x-reverse">
                <div class="w-12 h-12 bg-purple-500/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                  <span class="text-purple-400 font-bold text-lg">۲</span>
                </div>
                <div>
                  <h4 class="text-white font-semibold mb-2">سوالات</h4>
                  <p class="text-blue-200">هر مرحله شامل ۷ سوال تصادفی است</p>
                </div>
              </div>
              
              <div class="flex items-start space-x-4 space-x-reverse">
                <div class="w-12 h-12 bg-yellow-500/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                  <span class="text-yellow-400 font-bold text-lg">۳</span>
                </div>
                <div>
                  <h4 class="text-white font-semibold mb-2">حق اشتباه</h4>
                  <p class="text-blue-200">در هر مرحله حق ۱ اشتباه دارید - اشتباه دوم = خروج از بازی</p>
                </div>
              </div>
            </div>
            
            <div class="space-y-6">
              <div class="flex items-start space-x-4 space-x-reverse">
                <div class="w-12 h-12 bg-red-500/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                  </svg>
                </div>
                <div>
                  <h4 class="text-white font-semibold mb-2">قلب‌ها</h4>
                  <p class="text-blue-200">شما ۶ قلب دارید - هر شکست یک قلب کم می‌شود</p>
                </div>
              </div>
              
              <div class="flex items-start space-x-4 space-x-reverse">
                <div class="w-12 h-12 bg-blue-500/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                  </svg>
                </div>
                <div>
                  <h4 class="text-white font-semibold mb-2">بازگردانی قلب</h4>
                  <p class="text-blue-200">قلب‌ها هر ۸ ساعت بازگردانده می‌شوند یا از فروشگاه خریداری کنید</p>
                </div>
              </div>
              
              <div class="flex items-start space-x-4 space-x-reverse">
                <div class="w-12 h-12 bg-green-500/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                </div>
                <div>
                  <h4 class="text-white font-semibold mb-2">امتیازات</h4>
                  <p class="text-blue-200">برای هر پاسخ صحیح امتیاز کسب کنید</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Start Game Button -->
        <div class="flex flex-col items-center space-y-6">
          <button 
            @click="startGame"
            :disabled="gameStatus.hearts === 0"
            class="group relative bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-16 py-5 rounded-3xl text-2xl font-bold transition-all duration-500 transform hover:scale-[1.05] shadow-2xl disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none overflow-hidden"
          >
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/50 to-purple-600/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10 flex items-center space-x-3 space-x-reverse">
              <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                <path d="M8 5v14l11-7z"/>
              </svg>
              <span v-if="gameStatus.hearts > 0">شروع بازی</span>
              <span v-else>قلب کافی ندارید</span>
            </div>
          </button>
          
          <!-- No Lives Message -->
          <div v-if="gameStatus.hearts === 0" class="bg-red-500/20 backdrop-blur-xl rounded-3xl p-8 border border-red-400/30 max-w-md mx-auto">
            <div class="text-center">
              <div class="w-16 h-16 bg-red-500/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
              </div>
              <h3 class="text-xl font-bold text-white mb-2">قلب‌های شما تمام شده!</h3>
              <p class="text-red-200 mb-6">برای بازگشت قلب‌ها ۸ ساعت صبر کنید یا از فروشگاه دارو خریداری کنید.</p>
              <button 
                @click="goToStore"
                class="group bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-8 py-3 rounded-2xl font-semibold transition-all duration-300 transform hover:scale-[1.02] shadow-lg relative overflow-hidden"
              >
                <div class="absolute inset-0 bg-gradient-to-r from-green-600/50 to-emerald-600/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative z-10 flex items-center space-x-2 space-x-reverse">
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7Z"/>
                  </svg>
                  <span>رفتن به فروشگاه</span>
                </div>
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Game Playing Screen -->
      <div v-else-if="gameState === 'playing'" class="text-center rtl font-persian">
        <!-- Answer Animation Overlay -->
        <div 
          v-if="showAnswerAnimation" 
          class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        >
          <div 
            class="text-8xl font-bold animate-bounce"
            :class="isCorrectAnswer ? 'text-green-500' : 'text-red-500'"
          >
            {{ isCorrectAnswer ? '✓' : '✗' }}
          </div>
        </div>

        <!-- Timer Display -->
        <div class="mb-6">
          <div class="relative w-24 h-24 mx-auto">
            <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 100 100">
              <circle
                cx="50"
                cy="50"
                r="45"
                stroke="#e5e7eb"
                stroke-width="8"
                fill="none"
              />
              <circle
                cx="50"
                cy="50"
                r="45"
                :stroke="timeLeft <= 5 ? '#ef4444' : timeLeft <= 10 ? '#f59e0b' : '#10b981'"
                stroke-width="8"
                fill="none"
                stroke-linecap="round"
                :stroke-dasharray="283"
                :stroke-dashoffset="283 - (timeLeft / questionTimeLimit) * 283"
                class="transition-all duration-1000 ease-linear"
              />
            </svg>
            <div class="absolute inset-0 flex items-center justify-center">
              <span 
                class="text-2xl font-bold"
                :class="timeLeft <= 5 ? 'text-red-500' : timeLeft <= 10 ? 'text-yellow-500' : 'text-green-500'"
              >
                {{ timeLeft }}
              </span>
            </div>
          </div>
        </div>

        <!-- Correct/Incorrect Counter -->
        <div class="flex justify-center space-x-8 mb-6">
          <div class="flex items-center space-x-2">
            <div class="w-4 h-4 bg-green-500 rounded-full"></div>
            <span class="text-green-600 font-semibold">صحیح: {{ correctAnswers }}</span>
          </div>
          <div class="flex items-center space-x-2">
            <div class="w-4 h-4 bg-red-500 rounded-full"></div>
            <span class="text-red-600 font-semibold">غلط: {{ incorrectAnswers }}</span>
          </div>
        </div>

        <!-- Stage Progress -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
          <div class="flex justify-between items-center mb-4">
            <div class="text-right">
              <h3 class="text-lg font-semibold text-gray-900">مرحله {{ currentStage }}</h3>
              <p class="text-gray-600">سوال {{ currentQuestionIndex + 1 }} از ۷</p>
            </div>
            <div class="text-left">
              <div class="flex items-center space-x-2 space-x-reverse">
                <span class="text-sm text-gray-600">اشتباه‌های این مرحله:</span>
                <div class="flex space-x-1 space-x-reverse">
                  <div v-for="i in 2" :key="i" class="w-4 h-4 rounded-full" :class="stageErrors >= i ? 'bg-red-500' : 'bg-gray-200'"></div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Progress Bar -->
          <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 h-2 rounded-full transition-all duration-300" :style="{ width: ((currentQuestionIndex) / 7) * 100 + '%' }"></div>
          </div>
        </div>
        
        <!-- Question Display -->
<div v-if="currentQuestion" class="bg-white rounded-xl p-6 shadow-lg mb-6 rtl font-persian">
  <div class="text-center mb-6 rtl">
    <h2 class="text-xl font-bold text-gray-800 mb-2">{{ currentQuestion.text }}</h2>
    <div class="text-sm text-gray-500">
      سوال {{ currentQuestionIndex + 1 }} از 7 - مرحله {{ currentStage }}
    </div>
    
    <!-- دکمه‌های کمکی -->
    <div class="flex justify-center space-x-4 space-x-reverse mt-3">
      <button 
        @click="skipQuestion" 
        :disabled="!canSkipQuestion || props.gameStatus.hearts < 1 || loading"
        class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm hover:bg-blue-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex flex-col items-center"
        title="رد کردن سوال (کاهش ۱ جان)"
      >
        <span>رد کردن سوال</span>
        <small>(-۱ جان)</small>
      </button>
      
      <button 
        @click="removeWrongAnswers" 
        :disabled="!canRemoveWrongAnswers || props.gameStatus.hearts < 2 || loading"
        class="px-3 py-1 bg-purple-100 text-purple-700 rounded-lg text-sm hover:bg-purple-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex flex-col items-center"
        title="حذف دو گزینه نادرست (کاهش ۲ جان)"
      >
        <span>حذف گزینه‌های نادرست</span>
        <small>(-۲ جان)</small>
      </button>
    </div>
  </div>
  
  <div class="space-y-3 rtl">
    <button
      v-for="(option, letter) in currentQuestion.options"
      :key="letter"
      @click="selectAnswer(String(letter))"
      :disabled="answerSelected || loading"
      :class="[
        'w-full p-4 text-right rounded-lg border-2 transition-all duration-200',
        answerSelected && selectedAnswer === String(letter)
          ? selectedAnswer === currentQuestion.correct_answer
            ? 'bg-green-100 border-green-500 text-green-800'
            : 'bg-red-100 border-red-500 text-red-800'
          : answerSelected && String(letter) === currentQuestion.correct_answer
            ? 'bg-green-100 border-green-500 text-green-800'
            : 'bg-gray-50 border-gray-200 hover:border-blue-300 hover:bg-blue-50',
        loading && 'opacity-50 cursor-not-allowed'
      ]"
    >
      <span class="font-bold text-blue-600 ml-2 inline-block">{{ letter }})</span>
      {{ option }}
    </button>
  </div>
          
          <div v-if="answerSelected" class="mt-6 p-4 bg-blue-50 rounded-lg rtl font-persian">
            <p class="text-sm text-blue-800">
              <strong>توضیح:</strong> {{ currentQuestion.explanation || 'توضیحی موجود نیست.' }}
            </p>
          </div>
          
          <div v-if="loading" class="mt-4 text-center rtl font-persian">
            <div class="inline-flex items-center px-4 py-2 bg-blue-100 rounded-lg">
              <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 ml-2"></div>
              <span class="text-blue-800">در حال بررسی پاسخ...</span>
            </div>
          </div>
        </div>
        
        <!-- انیمیشن پاسخ -->
        <div v-if="showAnswerAnimation" class="answer-animation-container">
          <div :class="['answer-animation', isCorrectAnswer ? 'correct-animation' : 'incorrect-animation']">
            <div class="animation-content">
              <div v-if="isCorrectAnswer" class="correct-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M20 6L9 17l-5-5"></path>
                </svg>
                <span>آفرین! پاسخ درست بود</span>
                <div class="diabetes-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2V2z"></path>
                    <path d="M6 8h12"></path>
                    <path d="M6 16h12"></path>
                  </svg>
                </div>
              </div>
              <div v-else class="incorrect-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M18 6L6 18"></path>
                  <path d="M6 6l12 12"></path>
                </svg>
                <span>اشکالی نداره! یاد گرفتی</span>
                <div class="diabetes-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                    <line x1="9" y1="9" x2="9.01" y2="9"></line>
                    <line x1="15" y1="9" x2="15.01" y2="9"></line>
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Game Over Screen -->
      <div v-else-if="gameState === 'gameOver'" class="text-center">
        <div class="bg-red-50 rounded-xl p-8 mb-6">
          <div class="text-6xl mb-4">💔</div>
          <h2 class="text-2xl font-bold text-red-800 mb-2">بازی تمام شد!</h2>
          <p class="text-red-600 mb-4">
            متأسفانه در مرحله {{ currentStage }} شکست خوردید.
          </p>
          <p class="text-sm text-gray-600 mb-2">
            تعداد اشتباهات: {{ stageErrors }} از 2
          </p>
          <p class="text-sm text-gray-600 mb-6">
            امتیاز کسب شده: {{ score }}
          </p>
          
          <div class="space-y-3">
            <button
              v-if="canPlayGame"
              @click="restartGame"
              :disabled="loading"
              class="w-full bg-green-600 text-white py-3 px-6 rounded-lg hover:bg-green-700 transition-colors font-medium disabled:opacity-50"
            >
              شروع مجدد ({{ gameStatus.hearts }} قلب باقی مانده)
            </button>
            
            <button
              v-else
              @click="goToStore"
              class="w-full bg-yellow-600 text-white py-3 px-6 rounded-lg hover:bg-yellow-700 transition-colors font-medium"
            >
              خرید قلب از فروشگاه
            </button>
            
            <button
              @click="goBack"
              class="w-full bg-gray-600 text-white py-3 px-6 rounded-lg hover:bg-gray-700 transition-colors font-medium"
            >
              بازگشت به داشبورد
            </button>
          </div>
        </div>
      </div>
      
      <!-- Stage Complete Screen -->
      <div v-else-if="gameState === 'stageComplete'" class="text-center">
        <div class="bg-green-50 rounded-xl p-8 mb-6">
          <div class="text-6xl mb-4">🎉</div>
          <h2 class="text-2xl font-bold text-green-800 mb-2">مرحله {{ currentStage }} تمام شد!</h2>
          <p class="text-green-600 mb-4">
            تبریک! شما این مرحله را با موفقیت پشت سر گذاشتید.
          </p>
          <p class="text-sm text-gray-600 mb-6">
            امتیاز کسب شده: {{ score }}
          </p>
          
          <div class="space-y-3">
            <button
               v-if="currentStage < props.gameStatus.total_stages"
               @click="nextStage"
               :disabled="loading"
               class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors font-medium disabled:opacity-50"
             >
               مرحله بعدی
             </button>
            
            <button
              v-else
              @click="gameState = 'gameComplete'"
              class="w-full bg-purple-600 text-white py-3 px-6 rounded-lg hover:bg-purple-700 transition-colors font-medium"
            >
              مشاهده نتیجه نهایی
            </button>
            
            <button
              @click="goBack"
              class="w-full bg-gray-600 text-white py-3 px-6 rounded-lg hover:bg-gray-700 transition-colors font-medium"
            >
              بازگشت به داشبورد
            </button>
          </div>
        </div>
      </div>
      
      <!-- Game Complete Screen -->
      <div v-else-if="gameState === 'gameComplete'" class="text-center">
        <div class="bg-purple-50 rounded-xl p-8 mb-6">
          <div class="text-6xl mb-4">👑</div>
          <h2 class="text-2xl font-bold text-purple-800 mb-2">تبریک! بازی تمام شد!</h2>
          <p class="text-purple-600 mb-4">
             شما تمام {{ props.gameStatus.total_stages }} مرحله را با موفقیت پشت سر گذاشتید!
           </p>
          <p class="text-sm text-gray-600 mb-6">
            امتیاز نهایی: {{ score }}
          </p>
          
          <div class="space-y-3">
            <button
              @click="goBack"
              class="w-full bg-purple-600 text-white py-3 px-6 rounded-lg hover:bg-purple-700 transition-colors font-medium"
            >
              بازگشت به داشبورد
            </button>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'

// Props
interface Props {
  gameStatus: {
    hearts: number
    max_hearts: number
    current_stage: number
    total_stages: number
    heart_regeneration_time: string | null
    can_play: boolean
  }
}

const props = defineProps<Props>()

// Emits
interface Emits {
  'update:gameStatus': [gameStatus: Props['gameStatus']]
  'heartStatusUpdated': [heartStatus: any]
}

const emit = defineEmits<Emits>()

// Game State
const gameState = ref<'start' | 'playing' | 'gameOver' | 'stageComplete' | 'gameComplete'>('start')
const currentStage = ref(1)
const currentQuestionIndex = ref(0)
const stageErrors = ref(0)
const selectedAnswer = ref<string | null>(null)
const answerSelected = ref(false)
const currentQuestion = ref<any>(null)
const gameSession = ref<any>(null)
const score = ref(0)
const loading = ref(false)

// Timer State
const timeLeft = ref(30)
const questionTimeLimit = ref(30)
const timerInterval = ref<number | null>(null)

// Answer Counter
const correctAnswers = ref(0)
const incorrectAnswers = ref(0)

// Animation State
const showAnswerAnimation = ref(false)
const isCorrectAnswer = ref(false)
const canSkipQuestion = ref(true)
const canRemoveWrongAnswers = ref(true)

// Audio State
const isMuted = ref(false)
const backgroundMusic = ref<HTMLAudioElement | null>(null)
const correctSound = ref<HTMLAudioElement | null>(null)
const incorrectSound = ref<HTMLAudioElement | null>(null)

// Helper functions
const getCSRFToken = () => {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
}

const makeAPICall = async (url: string, method: string = 'GET', body?: any) => {
  const options: RequestInit = {
    method,
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': getCSRFToken()
    }
  }
  
  if (body) {
    options.body = JSON.stringify(body)
  }
  
  const response = await fetch(url, options)
  
  // بررسی وضعیت response
  if (!response.ok) {
    throw new Error(`HTTP error! status: ${response.status}`)
  }
  
  // بررسی Content-Type برای اطمینان از JSON بودن
  const contentType = response.headers.get('content-type')
  if (!contentType || !contentType.includes('application/json')) {
    throw new Error('Response is not JSON')
  }
  
  try {
    return await response.json()
  } catch (error) {
    throw new Error(`Failed to parse JSON response: ${error instanceof Error ? error.message : 'Unknown error'}`)
  }
}

// Timer functions
const getQuestionTimeLimit = (hearts: number): number => {
  const timeLimits: { [key: number]: number } = {
    6: 30,
    5: 25,
    4: 20,
    3: 15,
    2: 10,
    1: 5
  }
  return timeLimits[hearts] || 5
}

const startTimer = () => {
  questionTimeLimit.value = getQuestionTimeLimit(props.gameStatus.hearts)
  timeLeft.value = questionTimeLimit.value
  
  if (timerInterval.value) {
    clearInterval(timerInterval.value)
  }
  
  timerInterval.value = setInterval(() => {
    timeLeft.value--
    
    if (timeLeft.value <= 0) {
      clearInterval(timerInterval.value as number)
      // Auto-submit wrong answer when time runs out
      handleTimeOut()
    }
  }, 1000)
}

const stopTimer = () => {
  if (timerInterval.value) {
    clearInterval(timerInterval.value as number)
    timerInterval.value = null
  }
}

const handleTimeOut = async () => {
  incorrectAnswers.value++
  showAnswerFeedback(false)
  
  // Wait for animation then proceed
  setTimeout(async () => {
    await nextStage()
  }, 2000)
}

const showAnswerFeedback = (correct: boolean) => {
  isCorrectAnswer.value = correct
  showAnswerAnimation.value = true
  
  // Play sound effect
  if (!isMuted.value) {
    if (correct && correctSound.value) {
      correctSound.value.currentTime = 0
      correctSound.value.play().catch(() => {})
    } else if (!correct && incorrectSound.value) {
      incorrectSound.value.currentTime = 0
      incorrectSound.value.play().catch(() => {})
    }
  }
  
  setTimeout(() => {
    showAnswerAnimation.value = false
  }, 2000)
}

// قابلیت‌های جدید
const skipQuestion = async () => {
  if (!canSkipQuestion.value || props.gameStatus.hearts < 1 || loading.value) return
  
  loading.value = true
  canSkipQuestion.value = false
  
  try {
    const data = await makeAPICall('/api/game/skip-question', 'POST')
    
    if (data.success) {
      // کاهش یک جان با emit رویداد
      emit('update:gameStatus', { ...props.gameStatus, hearts: props.gameStatus.hearts - 1 })
      
      // دریافت سوال جدید
      if (data.next_question) {
        currentQuestion.value = data.next_question
        currentQuestionIndex.value = data.question_number - 1
        resetQuestion()
        startTimer()
      }
    } else {
      alert(data.error || 'خطا در رد کردن سوال')
    }
  } catch (error) {
    console.error('Error skipping question:', error)
    alert('خطا در ارتباط با سرور')
  } finally {
    loading.value = false
  }
}

const removeWrongAnswers = async () => {
  if (!canRemoveWrongAnswers.value || props.gameStatus.hearts < 2 || loading.value) return
  
  loading.value = true
  canRemoveWrongAnswers.value = false
  
  try {
    const data = await makeAPICall('/api/game/remove-wrong-answers', 'POST')
    
    if (data.success) {
      // کاهش دو جان با emit رویداد
      emit('update:gameStatus', { ...props.gameStatus, hearts: props.gameStatus.hearts - 2 })
      
      // به‌روزرسانی سوال با حذف دو گزینه نادرست
      if (data.updated_question) {
        currentQuestion.value = data.updated_question
      }
    } else {
      alert(data.error || 'خطا در حذف گزینه‌های نادرست')
    }
  } catch (error) {
    console.error('Error removing wrong answers:', error)
    alert('خطا در ارتباط با سرور')
  } finally {
    loading.value = false
  }
}

// Audio functions
const initializeAudio = () => {
  // Create audio elements with placeholder URLs
  // In a real app, you would use actual audio files
  backgroundMusic.value = new Audio()
  correctSound.value = new Audio()
  incorrectSound.value = new Audio()
  
  // Set properties
  if (backgroundMusic.value) {
    backgroundMusic.value.loop = true
    backgroundMusic.value.volume = 0.3
  }
  
  if (correctSound.value) {
    correctSound.value.volume = 0.5
  }
  
  if (incorrectSound.value) {
    incorrectSound.value.volume = 0.5
  }
}

const startBackgroundMusic = () => {
  if (!isMuted.value && backgroundMusic.value) {
    backgroundMusic.value.play().catch(() => {
      // Handle autoplay restrictions
      console.log('Background music autoplay blocked')
    })
  }
}

const stopBackgroundMusic = () => {
  if (backgroundMusic.value) {
    backgroundMusic.value.pause()
    backgroundMusic.value.currentTime = 0
  }
}

const toggleMute = () => {
  isMuted.value = !isMuted.value
  
  if (isMuted.value) {
    stopBackgroundMusic()
  } else {
    if (gameState.value === 'playing') {
      startBackgroundMusic()
    }
  }
}

const goBack = () => {
  stopTimer()
  stopBackgroundMusic()
  router.visit('/dashboard')
}

// Initialize audio on component mount
onMounted(() => {
  initializeAudio()
})

// Cleanup on component unmount
onUnmounted(() => {
  stopTimer()
  stopBackgroundMusic()
})



const goToStore = () => {
  router.visit('/store')
}

const startGame = async () => {
  if (props.gameStatus.hearts > 0 && !loading.value) {
    loading.value = true
    try {
      const data = await makeAPICall('/api/game/start', 'POST')
      
      if (data.success) {
        gameSession.value = data
        currentStage.value = data.stage
        currentQuestionIndex.value = 0
        stageErrors.value = 0
        score.value = 0
        correctAnswers.value = 0
        incorrectAnswers.value = 0
        // بازنشانی وضعیت قابلیت‌های کمکی
        canSkipQuestion.value = true
        canRemoveWrongAnswers.value = true
        
        // Get first question
        await getCurrentQuestion()
        gameState.value = 'playing'
        startTimer()
        startBackgroundMusic()
      } else {
        alert(data.error || 'خطا در شروع بازی')
      }
    } catch (error) {
      console.error('Error starting game:', error)
      alert('خطا در اتصال به سرور')
    } finally {
      loading.value = false
    }
  }
}

const getCurrentQuestion = async () => {
  try {
    const data = await makeAPICall('/api/game/current-question')
    currentQuestion.value = data.question
    currentQuestionIndex.value = data.question_number - 1
    stageErrors.value = data.mistakes
    score.value = data.score
  } catch (error) {
    console.error('Error getting current question:', error)
  }
}

const selectAnswer = async (answerIndex: string) => {
  if (answerSelected.value || loading.value) return
  
  selectedAnswer.value = answerIndex
  answerSelected.value = true
  loading.value = true
  stopTimer()
  
  // Convert answer index to letter format (0->A, 1->B, 2->C, 3->D)
  const answerLetter = ['A', 'B', 'C', 'D'][parseInt(answerIndex)]
  
  try {
    const data = await makeAPICall('/api/game/answer', 'POST', {
      answer: answerLetter
    })
    
    // Update counters
    if (data.correct) {
      correctAnswers.value++
    } else {
      incorrectAnswers.value++
    }
    
    // Show answer feedback
    showAnswerFeedback(data.correct)
    
    // Update game state based on response
    stageErrors.value = data.mistakes || stageErrors.value
    score.value = data.score || score.value
    
    // Update heart status if provided
    if (data.heart_status) {
      // Emit heart status update to parent component
      emit('heartStatusUpdated', data.heart_status)
    }
    
    // Show result for 2 seconds
    setTimeout(() => {
      if (data.game_over) {
        gameState.value = 'gameOver'
      } else if (data.stage_completed) {
        gameState.value = 'stageComplete'
      } else if (data.next_question) {
        currentQuestion.value = data.next_question
        currentQuestionIndex.value = data.question_number - 1
        resetQuestion()
        startTimer()
      } else {
        // اصلاح باگ: اگر هیچ یک از شرایط بالا برقرار نباشد، باید بازی ادامه پیدا کند
        resetQuestion()
        startTimer()
      }
      loading.value = false
    }, 2000)
    
  } catch (error) {
    console.error('Error submitting answer:', error)
    alert('خطا در ارسال پاسخ')
    answerSelected.value = false
    selectedAnswer.value = null
    loading.value = false
    // اصلاح باگ: در صورت خطا، بازی باید ادامه پیدا کند
    resetQuestion()
    startTimer()
  }
}

// Removed unused nextQuestion function

const resetQuestion = () => {
  selectedAnswer.value = null
  answerSelected.value = false
}



const nextStage = async () => {
  try {
    // Start new game for next stage
    await startGame()
  } catch (error) {
    console.error('Error starting next stage:', error)
  }
}



const restartGame = () => {
  if (props.gameStatus.hearts > 0) {
    startGame()
  }
}

// Computed properties
const canPlayGame = computed(() => {
  return props.gameStatus.can_play && props.gameStatus.hearts > 0
})

// Removed unused heartRegenerationText computed

onMounted(() => {
  // Initialize game status
  currentStage.value = props.gameStatus.current_stage
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
  animation: fadeInUp 0.6s ease-out;
}

.animate-slideInRight {
  animation: slideInRight 0.8s ease-out;
}

.animate-scaleIn {
  animation: scaleIn 0.5s ease-out;
}

.animate-heartbeat {
  animation: heartbeat 2s ease-in-out infinite;
}

/* Custom hover effects */
.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}

.group:hover .group-hover\:opacity-100 {
  opacity: 1;
}
</style>