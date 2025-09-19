# 🔮 Tarot-Yorum.fun

Modern ve yapay zeka destekli tarot falı ve astroloji platformu. Kullanıcılar tarot kartları çekebilir, günlük burç yorumları okuyabilir ve kişisel fallarını görebilirler.

## 🌟 Özellikler

### 🎴 Tarot Sistemi
- **Çoklu Dağılım Türleri**: Tek kart, üç kart, keltik haçı
- **Yapay Zeka Yorumları**: Akıllı kart yorumlama sistemi
- **Okuma Geçmişi**: Kullanıcı fal geçmişi takibi
- **İnteraktif Kart Seçimi**: Dinamik kart çekme deneyimi

### ⭐ Astroloji & Burç Sistemi
- **Günlük Horoskoplar**: 12 burç için günlük yorumlar
- **Burç Uyumluluğu**: Detaylı uyumluluk analizleri
- **Haftalık/Aylık Yorumlar**: Kapsamlı dönemsel analizler
- **Kişiselleştirilmiş Öneriler**: Kullanıcı bazlı özel yorumlar

### 📝 Blog & İçerik Sistemi
- **AI Destekli İçerik**: Otomatik makale üretimi
- **Kategori Yönetimi**: Aşk, kariyer, sağlık kategorileri
- **Yorum Sistemi**: Kullanıcı etkileşimi
- **SEO Optimizasyonu**: Arama motoru dostu yapı

### 👥 Kullanıcı Yönetimi
- **Güvenli Kayıt/Giriş**: E-posta doğrulama sistemi
- **Profil Yönetimi**: Kişisel bilgi ve tercihler
- **Fal Geçmişi**: Tüm okumalar kayıtlı
- **Favori Sistemi**: Beğenilen içerikleri kaydetme

### 🔧 Admin Paneli
- **Kapsamlı Dashboard**: Site istatistikleri ve analizler
- **Kullanıcı Yönetimi**: Tüm kullanıcı işlemleri
- **İçerik Moderasyonu**: Blog ve yorum yönetimi
- **Sistem Ayarları**: Platform konfigürasyonu

## 🎨 Tasarım

- **Siyah-Vişne Renk Paleti**: Mistik ve zarif görünüm
- **Responsive Tasarım**: Tüm cihazlarda uyumlu
- **Smooth Animasyonlar**: Akıcı kullanıcı deneyimi
- **Bootstrap Framework**: Modern UI bileşenleri

## 🛠️ Teknoloji Stack

- **Backend**: PHP 8.2+, MVC Architecture
- **Veritabanı**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Güvenlik**: CSRF koruması, güvenli session yönetimi
- **API**: RESTful yapı, JSON responses

## 📋 Kurulum

### Gereksinimler
- PHP 8.2 veya üstü
- MySQL 5.7+ veya MariaDB 10.3+
- Apache/Nginx web sunucusu
- Composer (opsiyonel)

### Hızlı Kurulum

```bash
# Repository'yi klonlayın
git clone https://github.com/yourusername/tarot-yorum-fun.git
cd tarot-yorum-fun

# Veritabanını oluşturun
mysql -u root -p < database/migrations/001_create_database.sql

# Tabloları oluşturun
mysql -u root -p tarot_db < database/migrations/002_create_tables.sql
mysql -u root -p tarot_db < database/migrations/003_create_indexes.sql
mysql -u root -p tarot_db < database/migrations/004_create_triggers.sql
mysql -u root -p tarot_db < database/migrations/005_insert_sample_data.sql

# Konfigürasyon dosyasını düzenleyin
cp config/config.example.php config/config.php
# Veritabanı bilgilerini güncelleyin

# Web sunucusunu başlatın (development)
php -S localhost:8000 -t . index.php
```

### Veritabanı Konfigürasyonu

`config/config.php` dosyasında veritabanı ayarlarınızı güncelleyin:

```php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'tarot_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

## 👤 Admin Hesabı

**Kullanıcı Adı**: `admin`  
**Şifre**: `admin123`  
**E-posta**: `admin@tarot-yorum.fun`

> ⚠️ **Güvenlik**: Production ortamında admin şifresini mutlaka değiştirin!

## 📁 Proje Yapısı

```
tarot-yorum-fun/
├── app/
│   ├── Controllers/        # MVC Controllers
│   ├── Models/            # Database Models
│   ├── Core/              # Core system files
│   ├── Services/          # Business logic services
│   └── Helpers/           # Helper functions
├── config/                # Configuration files
├── database/
│   └── migrations/        # Database migration files
├── views/                 # View templates
│   ├── layouts/          # Layout templates
│   ├── home/             # Homepage views
│   ├── auth/             # Authentication views
│   ├── zodiac/           # Zodiac views
│   ├── tarot/            # Tarot views
│   └── admin/            # Admin panel views
├── assets/
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   └── images/           # Image assets
└── uploads/              # User uploads
```

## 🔐 Güvenlik Özellikleri

- **CSRF Protection**: Form güvenliği
- **XSS Prevention**: Çıktı filtreleme
- **SQL Injection Protection**: Prepared statements
- **Secure Sessions**: Güvenli session yönetimi
- **Password Hashing**: BCrypt şifreleme
- **Input Validation**: Girdi doğrulama

## 🌐 Production Deployment

### Apache Konfigürasyonu

```apache
<VirtualHost *:80>
    ServerName tarot-yorum.fun
    DocumentRoot /var/www/tarot-yorum-fun
    
    <Directory /var/www/tarot-yorum-fun>
        AllowOverride All
        Require all granted
    </Directory>
    
    # Redirect all requests to index.php
    FallbackResource /index.php
</VirtualHost>
```

### Nginx Konfigürasyonu

```nginx
server {
    listen 80;
    server_name tarot-yorum.fun;
    root /var/www/tarot-yorum-fun;
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

## 🤝 Katkıda Bulunma

1. Fork yapın
2. Feature branch oluşturun (`git checkout -b feature/amazing-feature`)
3. Değişikliklerinizi commit edin (`git commit -m 'Add amazing feature'`)
4. Branch'inizi push edin (`git push origin feature/amazing-feature`)
5. Pull Request oluşturun

## 📄 Lisans

Bu proje MIT lisansı altında lisanslanmıştır. Detaylar için [LICENSE](LICENSE) dosyasına bakın.

## 📞 İletişim

- **Website**: [tarot-yorum.fun](https://tarot-yorum.fun)
- **Email**: info@tarot-yorum.fun
- **GitHub**: [github.com/yourusername/tarot-yorum-fun](https://github.com/yourusername/tarot-yorum-fun)

## 🙏 Teşekkürler

Bu projeyi mümkün kılan tüm açık kaynak topluluğuna teşekkürler!

---

⭐ **Bu projeyi beğendiyseniz yıldız vermeyi unutmayın!**