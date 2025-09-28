#!/bin/bash

# Diabetes Learning Game - Production Deployment Script
# این اسکریپت برای دیپلوی پروژه در محیط production استفاده می‌شود

echo "🚀 شروع فرآیند دیپلوی..."

# 1. بررسی وجود فایل .env
if [ ! -f .env ]; then
    echo "❌ فایل .env یافت نشد. لطفاً از .env.example کپی کنید و تنظیمات را انجام دهید."
    exit 1
fi

# 2. نصب dependencies
echo "📦 نصب Composer dependencies..."
composer install --optimize-autoloader --no-dev

# 3. ساخت فایل‌های frontend
echo "🎨 ساخت فایل‌های frontend..."
npm ci
npm run build

# 4. تولید APP_KEY (اگر خالی باشد)
if grep -q "APP_KEY=$" .env; then
    echo "🔑 تولید APP_KEY..."
    php artisan key:generate
fi

# 5. اجرای migrations
echo "🗄️ اجرای migrations..."
php artisan migrate --force

# 6. اجرای seeders (اختیاری)
read -p "آیا می‌خواهید seeders را اجرا کنید؟ (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "🌱 اجرای seeders..."
    php artisan db:seed --force
fi

# 7. بهینه‌سازی Laravel
echo "⚡ بهینه‌سازی Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. تنظیم مجوزها
echo "🔒 تنظیم مجوزها..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 9. پاک‌سازی cache های قدیمی
echo "🧹 پاک‌سازی cache..."
php artisan cache:clear

echo "✅ دیپلوی با موفقیت تکمیل شد!"
echo "🌐 پروژه آماده استفاده است."

# نمایش اطلاعات مهم
echo ""
echo "📋 اطلاعات مهم:"
echo "- مطمئن شوید که تنظیمات database در .env صحیح است"
echo "- مطمئن شوید که APP_URL در .env درست تنظیم شده"
echo "- برای محیط production، APP_DEBUG باید false باشد"
echo "- مطمئن شوید که Redis یا cache driver مناسب تنظیم شده"