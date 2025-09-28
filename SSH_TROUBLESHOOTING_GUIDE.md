# راهنمای حل مشکل SSH Connection

## وضعیت فعلی
✅ **سرور در دسترس است** - پورت 22 باز است  
❌ **اتصال SSH با خطا مواجه می‌شود** - "Connection closed by remote host"

## مشکل اصلی
سرور SSH اتصال را قطع می‌کند. این ممکن است به دلایل زیر باشد:
1. تنظیمات امنیتی سرور
2. محدودیت‌های فایروال
3. تنظیمات SSH daemon روی سرور

## راه‌حل‌های پیشنهادی

### 1. استفاده از SSH Config جدید
فایل `ssh_config` را کپی کرده و در مسیر زیر قرار دهید:
```
c:\Users\سایتم رو آماده کن\.ssh\config
```

### 2. تست اتصال مستقیم
```bash
ssh -v -o StrictHostKeyChecking=no -o UserKnownHostsFile=NUL root@93.127.180.88
```

### 3. تست با تنظیمات مختلف
```bash
# تست با پورت مختلف (اگر SSH روی پورت دیگری است)
ssh -p 2222 root@93.127.180.88

# تست با protocol version 1
ssh -1 root@93.127.180.88

# تست با cipher مختلف
ssh -c aes128-ctr root@93.127.180.88
```

### 4. بررسی تنظیمات سرور
احتمالاً نیاز است روی سرور این موارد بررسی شوند:

#### در فایل `/etc/ssh/sshd_config`:
```bash
PermitRootLogin yes
PasswordAuthentication yes
PubkeyAuthentication no
AllowUsers root
Port 22
```

#### راه‌اندازی مجدد SSH service:
```bash
sudo systemctl restart ssh
# یا
sudo service ssh restart
```

### 5. بررسی فایروال سرور
```bash
# Ubuntu/Debian
sudo ufw allow 22
sudo ufw status

# CentOS/RHEL
sudo firewall-cmd --permanent --add-port=22/tcp
sudo firewall-cmd --reload
```

## اطلاعات اتصال
- **IP**: 93.127.180.88
- **User**: root
- **Password**: 7h5Za9mM8K
- **Port**: 22

## تست‌های انجام شده
✅ دسترسی شبکه به سرور  
✅ پورت 22 باز است  
❌ SSH daemon مشکل دارد  

## توصیه
با مدیر سرور تماس بگیرید تا تنظیمات SSH daemon را بررسی کند.