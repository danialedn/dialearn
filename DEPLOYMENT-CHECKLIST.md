# ✅ چک‌لیست دیپلوی - بازی آموزش دیابت

## 📋 قبل از دیپلوی

### ✅ فایل‌های پروژه
- [ ] تمام فایل‌های پروژه آپلود شده
- [ ] فایل `.env` از روی `.env.example` کپی و تنظیم شده
- [ ] فایل‌های `.git` حذف شده (برای امنیت)
- [ ] فایل‌های `node_modules` حذف شده

### ✅ تنظیمات .env
- [ ] `APP_NAME` تنظیم شده
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL` با دامنه صحیح تنظیم شده
- [ ] `APP_KEY` تولید شده
- [ ] تنظیمات دیتابیس صحیح
- [ ] تنظیمات cache (Redis توصیه می‌شود)
- [ ] تنظیمات mail

### ✅ دیتابیس
- [ ] دیتابیس ایجاد شده
- [ ] کاربر دیتابیس با مجوزهای لازم ایجاد شده
- [ ] اتصال به دیتابیس تست شده

## 🚀 مراحل دیپلوی

### ✅ نصب Dependencies
```bash
composer install --optimize-autoloader --no-dev
npm ci
npm run build
```
- [ ] Composer dependencies نصب شده
- [ ] NPM dependencies نصب شده
- [ ] فایل‌های production build شده

### ✅ تنظیمات Laravel
```bash
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force  # اختیاری
php artisan storage:link
```
- [ ] APP_KEY تولید شده
- [ ] Migrations اجرا شده
- [ ] Seeders اجرا شده (در صورت نیاز)
- [ ] Storage link ایجاد شده

### ✅ بهینه‌سازی
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
- [ ] Config cache شده
- [ ] Routes cache شده
- [ ] Views cache شده

### ✅ مجوزها
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```
- [ ] مجوزهای storage تنظیم شده
- [ ] مجوزهای bootstrap/cache تنظیم شده
- [ ] Owner فایل‌ها تنظیم شده

## 🌐 تنظیمات وب سرور

### ✅ Nginx/Apache
- [ ] Virtual host تنظیم شده
- [ ] Document root به پوشه `public` اشاره می‌کند
- [ ] SSL تنظیم شده (Let's Encrypt)
- [ ] Redirect HTTP به HTTPS

### ✅ PHP
- [ ] نسخه PHP 8.1+ نصب شده
- [ ] Extensions مورد نیاز نصب شده
- [ ] PHP-FPM تنظیم شده
- [ ] Memory limit مناسب تنظیم شده

### ✅ Services
- [ ] MySQL/MariaDB در حال اجرا
- [ ] Redis در حال اجرا (اختیاری)
- [ ] Nginx/Apache در حال اجرا
- [ ] PHP-FPM در حال اجرا

## 🔒 امنیت

### ✅ تنظیمات امنیتی
- [ ] فایل‌های `.env` و `.git` در دسترس عموم نیست
- [ ] Server tokens مخفی شده
- [ ] فایروال تنظیم شده
- [ ] SSH key-based authentication
- [ ] Regular security updates

### ✅ بک‌آپ
- [ ] اسکریپت بک‌آپ خودکار تنظیم شده
- [ ] بک‌آپ دیتابیس تست شده
- [ ] بک‌آپ فایل‌ها تست شده

## 🧪 تست‌ها

### ✅ تست عملکرد
- [ ] صفحه اصلی بارگذاری می‌شود
- [ ] ثبت‌نام کاربر جدید
- [ ] ورود کاربر
- [ ] تکمیل فرم اطلاعات جمعیت‌شناختی
- [ ] شروع بازی
- [ ] پاسخ دادن به سوالات
- [ ] مشاهده آمار
- [ ] خروج از حساب

### ✅ تست responsive
- [ ] موبایل
- [ ] تبلت
- [ ] دسکتاپ

### ✅ تست مرورگرها
- [ ] Chrome
- [ ] Firefox
- [ ] Safari
- [ ] Edge

## 📊 مانیتورینگ

### ✅ لاگ‌ها
- [ ] Laravel logs قابل دسترس
- [ ] Web server logs قابل دسترس
- [ ] Error monitoring تنظیم شده

### ✅ Performance
- [ ] سرعت بارگذاری صفحات
- [ ] استفاده از منابع سرور
- [ ] Cache hit ratio

## 🔧 تنظیمات اضافی

### ✅ Cron Jobs
```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```
- [ ] Laravel scheduler تنظیم شده

### ✅ Queue Workers (اختیاری)
- [ ] Queue worker service تنظیم شده
- [ ] Supervisor تنظیم شده

## 📞 پس از دیپلوی

### ✅ اعلان‌ها
- [ ] تیم توسعه مطلع شده
- [ ] مستندات به‌روزرسانی شده
- [ ] کاربران از تغییرات مطلع شده

### ✅ مانیتورینگ
- [ ] سیستم مانیتورینگ فعال
- [ ] آلارم‌های ضروری تنظیم شده
- [ ] Health checks فعال

---

## 🚨 در صورت بروز مشکل

### دستورات عیب‌یابی:
```bash
# بررسی لاگ‌ها
tail -f storage/logs/laravel.log

# پاک کردن cache
php artisan optimize:clear

# بازسازی cache
php artisan optimize

# بررسی وضعیت
php artisan about

# تست اتصال دیتابیس
php artisan tinker
>>> DB::connection()->getPdo();
```

### فایل‌های مهم برای بررسی:
- `storage/logs/laravel.log`
- `/var/log/nginx/error.log`
- `/var/log/php8.1-fpm.log`

---

**نکته**: این چک‌لیست را در هر دیپلوی مرور کنید تا از عدم فراموشی هیچ مرحله‌ای اطمینان حاصل کنید.