#!/bin/bash

# Tarot-Yorum.fun Deployment Script
# Bu script sunucuda otomatik kurulum yapar

echo "🔮 Tarot-Yorum.fun Deployment Script"
echo "====================================="

# Renk kodları
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Hata kontrolü
set -e

# Fonksiyonlar
print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

# Sistem kontrolü
print_info "Sistem gereksinimleri kontrol ediliyor..."

# PHP kontrolü
if ! command -v php &> /dev/null; then
    print_error "PHP kurulu değil!"
    exit 1
fi

PHP_VERSION=$(php -r "echo PHP_VERSION;")
print_success "PHP version: $PHP_VERSION"

# MySQL kontrolü
if ! command -v mysql &> /dev/null; then
    print_error "MySQL kurulu değil!"
    exit 1
fi

print_success "MySQL kurulu"

# Veritabanı konfigürasyonu
print_info "Veritabanı konfigürasyonu..."

read -p "MySQL kullanıcı adı [root]: " DB_USER
DB_USER=${DB_USER:-root}

read -s -p "MySQL şifresi: " DB_PASS
echo

read -p "Veritabanı adı [tarot_db]: " DB_NAME
DB_NAME=${DB_NAME:-tarot_db}

read -p "Veritabanı host [localhost]: " DB_HOST
DB_HOST=${DB_HOST:-localhost}

# Veritabanı bağlantısını test et
print_info "Veritabanı bağlantısı test ediliyor..."

if mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" -e ";" 2>/dev/null; then
    print_success "Veritabanı bağlantısı başarılı"
else
    print_error "Veritabanı bağlantısı başarısız!"
    exit 1
fi

# Veritabanını oluştur
print_info "Veritabanı oluşturuluyor..."

mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" -e "CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null || {
    print_error "Veritabanı oluşturulamadı!"
    exit 1
}

print_success "Veritabanı oluşturuldu: $DB_NAME"

# Migration dosyalarını çalıştır
print_info "Veritabanı tabloları oluşturuluyor..."

for migration in database/migrations/*.sql; do
    if [[ -f "$migration" ]]; then
        print_info "Çalıştırılıyor: $(basename "$migration")"
        mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$migration" || {
            print_warning "Migration hatası: $(basename "$migration")"
        }
    fi
done

print_success "Veritabanı tabloları oluşturuldu"

# Konfigürasyon dosyasını oluştur
print_info "Konfigürasyon dosyası oluşturuluyor..."

if [[ ! -f "config/config.php" ]]; then
    cp "config/config.example.php" "config/config.php" 2>/dev/null || {
        print_warning "config.example.php bulunamadı, manuel konfigürasyon gerekli"
    }
fi

# Konfigürasyon güncellemesi
if [[ -f "config/config.php" ]]; then
    # Veritabanı bilgilerini güncelle
    sed -i "s/define('DB_HOST', '.*');/define('DB_HOST', '$DB_HOST');/" config/config.php
    sed -i "s/define('DB_NAME', '.*');/define('DB_NAME', '$DB_NAME');/" config/config.php
    sed -i "s/define('DB_USER', '.*');/define('DB_USER', '$DB_USER');/" config/config.php
    sed -i "s/define('DB_PASS', '.*');/define('DB_PASS', '$DB_PASS');/" config/config.php
    
    print_success "Konfigürasyon dosyası güncellendi"
fi

# Dosya izinlerini ayarla
print_info "Dosya izinleri ayarlanıyor..."

# Yazılabilir klasörler
chmod -R 755 . 2>/dev/null || print_warning "Chmod komutu çalıştırılamadı"
chmod -R 777 uploads/ 2>/dev/null || print_warning "uploads/ klasörü bulunamadı"
chmod -R 777 cache/ 2>/dev/null || print_warning "cache/ klasörü bulunamadı"
chmod -R 777 logs/ 2>/dev/null || print_warning "logs/ klasörü bulunamadı"

print_success "Dosya izinleri ayarlandı"

# Gerekli klasörleri oluştur
print_info "Gerekli klasörler oluşturuluyor..."

mkdir -p uploads/{profiles,cards,blog} 2>/dev/null
mkdir -p cache/{views,data} 2>/dev/null
mkdir -p logs 2>/dev/null
mkdir -p sessions 2>/dev/null

print_success "Klasörler oluşturuldu"

# Web sunucusu kontrolü
print_info "Web sunucusu kontrolleri..."

if command -v apache2 &> /dev/null; then
    print_success "Apache web sunucusu tespit edildi"
    print_info "Virtual host konfigürasyonu için README.md dosyasına bakın"
elif command -v nginx &> /dev/null; then
    print_success "Nginx web sunucusu tespit edildi"
    print_info "Server block konfigürasyonu için README.md dosyasına bakın"
else
    print_warning "Web sunucusu tespit edilemedi"
    print_info "Development için: php -S localhost:8000 -t . index.php"
fi

# Test bağlantısı
print_info "Kurulum testi yapılıyor..."

if php -r "
require_once 'config/config.php';
require_once 'config/database.php';
try {
    \$db = new Database();
    \$pdo = \$db->getConnection();
    echo 'Veritabanı bağlantısı: BAŞARILI\n';
} catch (Exception \$e) {
    echo 'Veritabanı bağlantısı: HATA - ' . \$e->getMessage() . '\n';
    exit(1);
}
" 2>/dev/null; then
    print_success "Kurulum testi başarılı!"
else
    print_error "Kurulum testi başarısız!"
    exit 1
fi

# Güvenlik önerileri
echo ""
print_info "🔐 GÜVENLİK ÖNERİLERİ"
echo "===================="
print_warning "1. Admin şifresini değiştirin (admin/admin123)"
print_warning "2. config/config.php dosyasını güvenli hale getirin"
print_warning "3. SSL sertifikası kurun (HTTPS)"
print_warning "4. Güvenlik duvarı ayarlarını kontrol edin"
print_warning "5. Düzenli olarak yedek alın"

echo ""
print_success "🎉 KURULUM TAMAMLANDI!"
echo "===================="
print_info "Admin Paneli: http://yourdomain.com/admin"
print_info "Kullanıcı Adı: admin"
print_info "Şifre: admin123"
echo ""
print_info "Daha fazla bilgi için README.md dosyasını okuyun."
echo ""
print_success "Tarot-Yorum.fun platformu kullanıma hazır! 🔮"