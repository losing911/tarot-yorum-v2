# 🚀 Tarot-Yorum.fun Deployment Guide

## GitHub'a Yükleme

1. **GitHub'da yeni repository oluşturun:**
   - Repository adı: `tarot-yorum-fun`
   - Description: "Modern AI-powered Tarot and Astrology Platform"
   - Public veya Private seçin

2. **Remote repository'yi güncelleyin:**
   ```bash
   git remote remove origin
   git remote add origin https://github.com/YOUR_USERNAME/tarot-yorum-fun.git
   git branch -M main
   git push -u origin main
   ```

## Sunucuya SSH ile Bağlanma ve Kurulum

### 1. Sunucuya Bağlanın
```bash
ssh username@your-server-ip
# veya
ssh username@your-domain.com
```

### 2. Web Dizinine Gidin
```bash
cd /var/www/html
# veya hosting sağlayıcınızın belirttiği dizin
cd /home/username/public_html
```

### 3. Repository'yi Klonlayın
```bash
git clone https://github.com/YOUR_USERNAME/tarot-yorum-fun.git
cd tarot-yorum-fun
```

### 4. Otomatik Kurulum Scripti Çalıştırın
```bash
chmod +x deploy.sh
./deploy.sh
```

### 5. Manuel Kurulum (Eğer script çalışmazsa)

#### a) Konfigürasyon Dosyasını Oluşturun:
```bash
cp config/config.example.php config/config.php
nano config/config.php  # Veritabanı bilgilerini düzenleyin
```

#### b) Veritabanını Oluşturun:
```bash
mysql -u root -p -e "CREATE DATABASE tarot_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

#### c) Tabloları Oluşturun:
```bash
mysql -u root -p tarot_db < database/migrations/001_create_users_table.sql
mysql -u root -p tarot_db < database/migrations/002_create_zodiac_signs_table.sql
mysql -u root -p tarot_db < database/migrations/003_create_tarot_cards_table.sql
mysql -u root -p tarot_db < database/migrations/004_create_additional_tables.sql
mysql -u root -p tarot_db < database/migrations/005_insert_sample_data.sql
mysql -u root -p tarot_db < database/migrations/006_create_site_settings_table.sql
```

#### d) Dosya İzinlerini Ayarlayın:
```bash
chmod -R 755 .
chmod -R 777 uploads/
chmod -R 777 cache/
chmod -R 777 logs/
```

#### e) Gerekli Klasörleri Oluşturun:
```bash
mkdir -p uploads/{profiles,cards,blog}
mkdir -p cache/{views,data}
mkdir -p logs
mkdir -p sessions
```

### 6. Web Sunucusu Konfigürasyonu

#### Apache (.htaccess zaten mevcut):
```apache
# Eğer Virtual Host kullanıyorsanız:
<VirtualHost *:80>
    ServerName tarot-yorum.fun
    DocumentRoot /var/www/html/tarot-yorum-fun
    
    <Directory /var/www/html/tarot-yorum-fun>
        AllowOverride All
        Require all granted
    </Directory>
    
    FallbackResource /index.php
</VirtualHost>
```

#### Nginx:
```nginx
server {
    listen 80;
    server_name tarot-yorum.fun;
    root /var/www/html/tarot-yorum-fun;
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
    
    location ~ /\.ht {
        deny all;
    }
}
```

### 7. SSL Sertifikası (Let's Encrypt)
```bash
# Ubuntu/Debian için:
sudo apt install certbot python3-certbot-apache
sudo certbot --apache -d tarot-yorum.fun

# veya Nginx için:
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d tarot-yorum.fun
```

### 8. Güvenlik Ayarları

#### a) Admin Şifresini Değiştirin:
- `/admin` paneline gidin
- Kullanıcı: `admin` / Şifre: `admin123`
- Profil > Şifre Değiştir

#### b) Config Dosyasını Güvenli Hale Getirin:
```bash
# Güçlü secret key oluşturun
openssl rand -base64 32

# config.php'de SECRET_KEY değerini güncelleyin
nano config/config.php
```

#### c) Debug Modunu Kapatın:
```php
// config.php içinde:
define('DEBUG_MODE', false);
```

### 9. Cron Jobs (Opsiyonel)
```bash
# Günlük horoskop güncellemeleri için:
crontab -e

# Ekleyin:
0 6 * * * cd /var/www/html/tarot-yorum-fun && php scripts/update_horoscopes.php
```

### 10. Monitoring ve Maintenance

#### a) Log Dosyalarını Kontrol Edin:
```bash
tail -f logs/error.log
tail -f logs/access.log
```

#### b) Veritabanı Yedekleri:
```bash
# Günlük yedek scripti oluşturun:
nano /scripts/backup_db.sh

#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u root -p tarot_db > /backups/tarot_db_$DATE.sql
find /backups -name "tarot_db_*.sql" -mtime +7 -delete
```

## Güncelleme Süreci

### Kod Güncellemeleri:
```bash
cd /var/www/html/tarot-yorum-fun
git pull origin main
# Gerekirse migration'ları çalıştırın
# Gerekirse cache'i temizleyin
```

## Sorun Giderme

### 1. 500 Internal Server Error:
```bash
# Error logları kontrol edin:
tail -f logs/error.log
tail -f /var/log/apache2/error.log  # Apache için
tail -f /var/log/nginx/error.log    # Nginx için

# PHP error logları:
tail -f /var/log/php/error.log
```

### 2. Veritabanı Bağlantı Hatası:
```bash
# Bağlantıyı test edin:
mysql -u root -p tarot_db -e "SELECT 1;"

# Config dosyasını kontrol edin:
cat config/config.php
```

### 3. Dosya İzin Sorunları:
```bash
# İzinleri düzeltin:
chown -R www-data:www-data .  # Apache için
chown -R nginx:nginx .        # Nginx için
chmod -R 755 .
chmod -R 777 uploads/ cache/ logs/ sessions/
```

### 4. CSS/JS Yüklenmiyor:
```bash
# .htaccess kontrolü:
cat .htaccess

# Web sunucusu konfigürasyonu kontrol edin
```

## Performans Optimizasyonu

### 1. PHP OpCache:
```bash
# php.ini'de etkinleştirin:
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=4000
```

### 2. Database Indexing:
```sql
-- Gerekli indexler migration dosyalarında mevcut
-- Ek performans için query optimizasyonu yapın
```

### 3. CDN Kullanımı:
- Static dosyalar için CloudFlare veya benzer CDN kullanın
- Image optimization için WebP formatını destekleyin

## Monitoring Araçları

1. **Server Monitoring**: htop, iotop
2. **Database Monitoring**: MySQL Workbench, phpMyAdmin
3. **Log Analysis**: LogWatch, GoAccess
4. **Uptime Monitoring**: UptimeRobot, Pingdom

---

🎉 **Deployment tamamlandıktan sonra siteniz hazır!**
- Ana sayfa: `https://your-domain.com`
- Admin paneli: `https://your-domain.com/admin`
- Default admin: `admin` / `admin123` (mutlaka değiştirin!)

📞 **Destek**: Sorun yaşarsanız GitHub Issues bölümünden yardım alabilirsiniz.