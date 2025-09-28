# 🚀 راهنمای دیپلوی - بازی آموزش دیابت

## 📋 پیش‌نیازها

### سرور
- **PHP**: 8.1 یا بالاتر
- **Composer**: آخرین نسخه
- **Node.js**: 18 یا بالاتر
- **NPM**: آخرین نسخه
- **MySQL**: 8.0 یا بالاتر (یا MariaDB 10.3+)
- **Redis**: 6.0 یا بالاتر (اختیاری اما توصیه می‌شود)
- **Nginx/Apache**: برای وب سرور

### Extensions مورد نیاز PHP
```
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML
- GD (برای پردازش تصاویر)
```

## 🛠️ مراحل دیپلوی

### 1. آپلود فایل‌ها
```bash
# آپلود تمام فایل‌های پروژه به سرور
# مطمئن شوید که فایل‌های .git آپلود نشوند
```

### 2. تنظیم فایل .env
```bash
# کپی کردن فایل .env.example
cp .env.example .env

# ویرایش فایل .env با تنظیمات صحیح
nano .env
```

#### تنظیمات مهم .env:
```env
APP_NAME="Diabetes Learning Game"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dialearn_production
DB_USERNAME=your_db_username
DB_PASSWORD=your_secure_db_password

CACHE_STORE=redis
CACHE_PREFIX=dialearn

SESSION_DRIVER=database
SESSION_LIFETIME=1440

MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_email_password
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
```

### 3. اجرای اسکریپت دیپلوی
```bash
# اجرای اسکریپت خودکار
chmod +x deploy.sh
./deploy.sh
```

### 4. تنظیم وب سرور

#### Nginx Configuration:
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /path/to/your/project/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### Apache Configuration (.htaccess):
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### 5. تنظیم مجوزها
```bash
# تنظیم مجوزهای فایل‌ها
sudo chown -R www-data:www-data /path/to/your/project
sudo chmod -R 755 /path/to/your/project
sudo chmod -R 775 /path/to/your/project/storage
sudo chmod -R 775 /path/to/your/project/bootstrap/cache
```

### 6. تنظیم SSL (اختیاری اما توصیه می‌شود)
```bash
# استفاده از Let's Encrypt
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

## 🔧 بهینه‌سازی‌های اضافی

### 1. تنظیم Cron Jobs
```bash
# اضافه کردن به crontab
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

### 2. تنظیم Queue Worker (اختیاری)
```bash
# ایجاد systemd service
sudo nano /etc/systemd/system/dialearn-worker.service
```

```ini
[Unit]
Description=Diabetes Learning Game Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /path/to/your/project/artisan queue:work
StandardOutput=journal
StandardError=journal

[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl enable dialearn-worker
sudo systemctl start dialearn-worker
```

### 3. تنظیم Redis (اختیاری)
```bash
# نصب Redis
sudo apt install redis-server

# تنظیم Redis
sudo nano /etc/redis/redis.conf
# maxmemory 256mb
# maxmemory-policy allkeys-lru

sudo systemctl restart redis
```

## 🔍 بررسی و تست

### 1. بررسی وضعیت سرور
```bash
# بررسی لاگ‌ها
tail -f storage/logs/laravel.log

# بررسی وضعیت services
sudo systemctl status nginx
sudo systemctl status php8.1-fpm
sudo systemctl status mysql
sudo systemctl status redis
```

### 2. تست عملکرد
- بازدید از صفحه اصلی
- تست ثبت‌نام و ورود
- تست بازی
- بررسی responsive design
- تست سرعت بارگذاری

## 🛡️ امنیت

### 1. تنظیمات امنیتی
```bash
# مخفی کردن نسخه سرور
# در nginx.conf:
server_tokens off;

# در Apache:
ServerTokens Prod
ServerSignature Off
```

### 2. فایروال
```bash
# تنظیم UFW
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443
sudo ufw enable
```

### 3. بک‌آپ
```bash
# اسکریپت بک‌آپ خودکار
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u username -p database_name > backup_$DATE.sql
tar -czf backup_$DATE.tar.gz /path/to/project backup_$DATE.sql
```

## 🚨 عیب‌یابی

### مشکلات رایج:
1. **خطای 500**: بررسی لاگ‌های Laravel و وب سرور
2. **مشکل CSS/JS**: اجرای `npm run build` مجدد
3. **خطای Database**: بررسی تنظیمات .env و مجوزهای دیتابیس
4. **مشکل Session**: بررسی تنظیمات session driver
5. **خطای Cache**: اجرای `php artisan cache:clear`

### دستورات مفید:
```bash
# پاک کردن تمام cache ها
php artisan optimize:clear

# بازسازی cache ها
php artisan optimize

# بررسی وضعیت پروژه
php artisan about

# تست اتصال دیتابیس
php artisan tinker
>>> DB::connection()->getPdo();
```

## 📞 پشتیبانی

در صورت بروز مشکل:
1. بررسی لاگ‌های Laravel: `storage/logs/laravel.log`
2. بررسی لاگ‌های وب سرور
3. بررسی تنظیمات .env
4. اجرای دستورات عیب‌یابی

---

**نکته**: این راهنما برای محیط production طراحی شده است. برای محیط development از تنظیمات متفاوتی استفاده کنید.