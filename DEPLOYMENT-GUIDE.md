# راهنمای Deploy کردن پروژه DiaLearn

## مراحل آماده‌سازی (انجام شده)

✅ **مرحله ۱: تنظیم .env برای Production**
- فایل `.env.production` ایجاد شده است
- تنظیمات امنیتی و بهینه‌سازی اعمال شده

✅ **مرحله ۲: بهینه‌سازی Composer**
- Dependencies بدون dev packages نصب شده
- Autoloader بهینه شده

✅ **مرحله ۳: Build کردن Assets**
- Assets با Vite برای production بیلد شده
- فایل‌های CSS و JS بهینه شده

✅ **مرحله ۴: کش کردن Laravel**
- Config، Routes و Views کش شده
- عملکرد بهبود یافته

## مراحل Deploy به سرور

### روش ۱: آپلود مستقیم فایل‌ها

1. **آپلود فایل‌ها به سرور:**
   - تمام فایل‌های پروژه را به سرور آپلود کنید
   - مطمئن شوید که پوشه `vendor` و `node_modules` آپلود نشده‌اند

2. **نصب Dependencies در سرور:**
   ```bash
   composer install --optimize-autoloader --no-dev
   npm install
   npm run build
   ```

3. **تنظیم Environment:**
   ```bash
   cp .env.production .env
   php artisan key:generate
   ```

4. **تنظیم مجوزها:**
   ```bash
   chmod +x set-permissions.sh
   ./set-permissions.sh
   ```

5. **تنظیم دیتابیس:**
   ```bash
   php artisan migrate
   php artisan db:seed  # اگر نیاز باشد
   ```

### روش ۲: استفاده از Git

1. **Push کردن به Repository:**
   ```bash
   git add .
   git commit -m "Production ready build"
   git push origin main
   ```

2. **Clone در سرور:**
   ```bash
   git clone https://github.com/yourusername/dialearn.git
   cd dialearn
   ```

3. **ادامه مراحل مشابه روش ۱**

### روش ۳: ایجاد فایل ZIP

برای ایجاد فایل ZIP آماده برای آپلود:

```bash
# حذف فایل‌های غیرضروری
rm -rf node_modules
rm -rf .git
rm -rf tests
rm -rf storage/logs/*

# ایجاد ZIP
zip -r dialearn-production.zip . -x "*.git*" "node_modules/*" "tests/*"
```

## تنظیمات مهم در سرور

### 1. تنظیم Web Server (Apache/Nginx)

**Apache (.htaccess):**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

**Nginx:**
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/dialearn/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 2. تنظیم SSL (اختیاری اما توصیه شده)

```bash
# با استفاده از Let's Encrypt
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com
```

### 3. تنظیم Cron Jobs

```bash
# اضافه کردن به crontab
* * * * * cd /path/to/dialearn && php artisan schedule:run >> /dev/null 2>&1
```

## چک‌لیست نهایی

- [ ] فایل `.env` با تنظیمات production تنظیم شده
- [ ] Database connection تست شده
- [ ] Mail settings تنظیم شده
- [ ] SSL certificate نصب شده
- [ ] Backup strategy تعریف شده
- [ ] Monitoring tools نصب شده
- [ ] Error logging تنظیم شده

## نکات امنیتی

1. **مجوزهای فایل:**
   - فایل‌ها: 644
   - پوشه‌ها: 755
   - storage و cache: 775

2. **محافظت از فایل‌های حساس:**
   - `.env` نباید از طریق web قابل دسترسی باشد
   - پوشه `storage` محافظت شده باشد

3. **تنظیمات امنیتی:**
   - `APP_DEBUG=false`
   - `APP_ENV=production`
   - استفاده از HTTPS

## عیب‌یابی

### مشکلات رایج:

1. **خطای 500:**
   - بررسی logs در `storage/logs/laravel.log`
   - بررسی مجوزهای فایل‌ها

2. **Assets لود نمی‌شوند:**
   - بررسی `APP_URL` در `.env`
   - اجرای `npm run build`

3. **خطای Database:**
   - بررسی تنظیمات DB در `.env`
   - اجرای `php artisan migrate`

## پشتیبانی

برای مشکلات بیشتر، لاگ‌های زیر را بررسی کنید:
- `storage/logs/laravel.log`
- Web server logs (Apache/Nginx)
- PHP error logs