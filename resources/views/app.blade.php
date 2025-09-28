<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        <title inertia>{{ config('app.name', 'Laravel') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        
        <!-- Persian Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- PWA Manifest -->
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#6366f1">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="دیالرن">

        @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
        
        <!-- Offline Storage Script -->
        <script src="/js/offline-storage.js"></script>
        
        <!-- Service Worker Registration -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register('/sw.js')
                        .then(function(registration) {
                            console.log('ServiceWorker registration successful');
                        })
                        .catch(function(err) {
                            console.log('ServiceWorker registration failed: ', err);
                        });
                });
            }
            
            // Request notification permission
            async function requestNotificationPermission() {
                if ('Notification' in window && 'serviceWorker' in navigator) {
                    const permission = await Notification.requestPermission();
                    if (permission === 'granted') {
                        console.log('Notification permission granted');
                        return true;
                    }
                }
                return false;
            }

            // Show offline installation notification
            function showOfflineInstallNotification() {
                if ('Notification' in window && Notification.permission === 'granted') {
                    const notification = new Notification('دیالرن - نصب آفلاین', {
                        body: 'اپلیکیشن دیالرن برای استفاده آفلاین آماده است!',
                        icon: '/icon-192.svg',
                        badge: '/favicon.svg',
                        tag: 'offline-install',
                        requireInteraction: true,
                        actions: [
                            {
                                action: 'open',
                                title: 'باز کردن اپلیکیشن'
                            },
                            {
                                action: 'close',
                                title: 'بستن'
                            }
                        ]
                    });

                    notification.onclick = function() {
                        window.focus();
                        notification.close();
                    };

                    // Auto close after 10 seconds
                    setTimeout(() => {
                        notification.close();
                    }, 10000);
                }
            }

            // PWA Install Prompt
            let deferredPrompt;
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                
                // Request notification permission when install prompt appears
                requestNotificationPermission();
                
                // Show install button or banner
                const installBanner = document.createElement('div');
                installBanner.innerHTML = `
                    <div style="position: fixed; bottom: 20px; right: 20px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; padding: 1rem; border-radius: 12px; box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3); z-index: 1000; font-family: 'Vazirmatn', sans-serif;">
                        <div style="margin-bottom: 0.5rem; font-weight: 600;">نصب اپلیکیشن دیالرن</div>
                        <div style="font-size: 0.9rem; margin-bottom: 1rem; opacity: 0.9;">برای دسترسی آسان‌تر، اپلیکیشن را نصب کنید</div>
                        <button onclick="installPWA()" style="background: rgba(255,255,255,0.2); border: none; color: white; padding: 0.5rem 1rem; border-radius: 8px; margin-left: 0.5rem; cursor: pointer;">نصب</button>
                        <button onclick="this.parentElement.parentElement.remove()" style="background: rgba(255,255,255,0.1); border: none; color: white; padding: 0.5rem 1rem; border-radius: 8px; cursor: pointer;">بعداً</button>
                    </div>
                `;
                document.body.appendChild(installBanner);
            });
            
            function installPWA() {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('User accepted the install prompt');
                            
                            // Register background sync for offline install notification
                            if ('serviceWorker' in navigator && 'sync' in window.ServiceWorkerRegistration.prototype) {
                                navigator.serviceWorker.ready.then(function(registration) {
                                    return registration.sync.register('offline-install-notification');
                                }).then(function() {
                                    console.log('Background sync registered for offline install notification');
                                }).catch(function(error) {
                                    console.log('Background sync registration failed:', error);
                                    // Fallback to immediate notification
                                    setTimeout(() => {
                                        showOfflineInstallNotification();
                                    }, 2000);
                                });
                            } else {
                                // Fallback for browsers without background sync
                                setTimeout(() => {
                                    showOfflineInstallNotification();
                                }, 2000);
                            }
                            
                            // Also send message to service worker for immediate notification
                            if ('serviceWorker' in navigator) {
                                navigator.serviceWorker.ready.then(function(registration) {
                                    if (registration.active) {
                                        registration.active.postMessage({
                                            type: 'SHOW_OFFLINE_INSTALL_NOTIFICATION'
                                        });
                                    }
                                });
                            }
                        }
                        deferredPrompt = null;
                    });
                }
                // Remove install banner
                const banner = document.querySelector('[onclick="installPWA()"]').closest('div').parentElement;
                if (banner) banner.remove();
            }
        </script>

        <!-- Offline Questions Manager -->
        <script src="/js/offline-questions.js?v=20241227c"></script>
        
        <!-- Auto-cache questions when online -->
        <script>
            // Auto-cache questions when the app loads and user is online
            window.addEventListener('load', async () => {
                if (navigator.onLine && window.offlineQuestionsManager) {
                    try {
                        const isCached = await window.offlineQuestionsManager.isQuestionsCached();
                        if (!isCached) {
                            console.log('شروع دانلود سوالات برای استفاده آفلاین...');
                            await window.offlineQuestionsManager.storeAllQuestions();
                            console.log('سوالات برای استفاده آفلاین آماده شدند');
                            
                            // Show notification to user
                            if ('Notification' in window && Notification.permission === 'granted') {
                                new Notification('دیالرن', {
                                    body: 'سوالات برای استفاده آفلاین آماده شدند',
                                    icon: '/icon-192x192.png',
                                    badge: '/icon-192x192.png'
                                });
                            }
                        } else {
                            console.log('سوالات قبلاً برای استفاده آفلاین ذخیره شده‌اند');
                        }
                    } catch (error) {
                        console.error('خطا در دانلود سوالات آفلاین:', error);
                    }
                }
            });

            // Update cache when coming back online
            window.addEventListener('online', async () => {
                if (window.offlineQuestionsManager) {
                    try {
                        console.log('اتصال برقرار شد، به‌روزرسانی سوالات...');
                        await window.offlineQuestionsManager.storeAllQuestions();
                        console.log('سوالات به‌روزرسانی شدند');
                    } catch (error) {
                        console.error('خطا در به‌روزرسانی سوالات:', error);
                    }
                }
            });
        </script>
    </body>
</html>
