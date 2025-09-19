<?php $title = $page_title; ?>
<?php include '../views/layouts/main.php'; ?>

<?php ob_start(); ?>

<!-- Hero Section -->
<section class="hero-section py-5 bg-gradient-cosmic text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">Burç Yorumları</h1>
                <p class="lead mb-4">Günlük, haftalık ve aylık burç yorumları ile geleceğinizi keşfedin. AI destekli kişiselleştirilmiş astroloji rehberi.</p>
                <div class="d-flex gap-3">
                    <a href="#zodiac-signs" class="btn btn-outline-light btn-lg">Burcunu Seç</a>
                    <a href="/compatibility" class="btn btn-light btn-lg">Uyumluluk Testi</a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="zodiac-wheel position-relative mx-auto" style="width: 300px; height: 300px;">
                    <img src="/assets/images/zodiac-wheel.svg" alt="Zodiac Wheel" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Zodiac Signs Grid -->
<section id="zodiac-signs" class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="h1 fw-bold text-gradient">12 Burç</h2>
                <p class="lead text-muted">Burcunuzu seçin ve günlük astroloji yorumlarınızı keşfedin</p>
            </div>
        </div>
        
        <div class="row g-4">
            <?php foreach ($zodiac_signs as $sign): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card zodiac-card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body text-center p-4">
                        <div class="zodiac-icon mb-3">
                            <i class="fas fa-<?= strtolower($sign['symbol']) ?> fa-3x text-<?= $sign['element'] ?>"></i>
                        </div>
                        <h5 class="card-title fw-bold"><?= htmlspecialchars($sign['name']) ?></h5>
                        <p class="text-muted small mb-3"><?= htmlspecialchars($sign['date_range']) ?></p>
                        <div class="element-badge mb-3">
                            <span class="badge bg-<?= $sign['element'] ?> text-white">
                                <?= ucfirst($sign['element']) ?>
                            </span>
                        </div>
                        
                        <?php if (isset($today_readings[$sign['id']])): ?>
                        <div class="daily-preview mb-3">
                            <h6 class="small text-primary mb-2">Günlük Yorum</h6>
                            <p class="small text-muted mb-0">
                                <?= mb_substr(strip_tags($today_readings[$sign['id']]['content']), 0, 80) ?>...
                            </p>
                        </div>
                        <?php endif; ?>
                        
                        <div class="d-grid gap-2">
                            <a href="/zodiac/<?= $sign['slug'] ?>" class="btn btn-primary btn-sm">
                                Detaylı Yorum
                            </a>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="/zodiac/<?= $sign['slug'] ?>/daily" class="btn btn-outline-secondary">Günlük</a>
                                <a href="/zodiac/<?= $sign['slug'] ?>/weekly" class="btn btn-outline-secondary">Haftalık</a>
                                <a href="/zodiac/<?= $sign['slug'] ?>/monthly" class="btn btn-outline-secondary">Aylık</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="h1 fw-bold">Astroloji Hizmetlerimiz</h2>
                <p class="lead text-muted">Kapsamlı astroloji deneyimi için özel olarak tasarlanmış hizmetler</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="feature-card text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-calendar-day fa-3x text-primary"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Günlük Yorumlar</h4>
                    <p class="text-muted">Her gün güncellenen özel burç yorumları ile gününüzü planlayın</p>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="feature-card text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-heart fa-3x text-danger"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Uyumluluk Analizi</h4>
                    <p class="text-muted">Sevdiğinizle aranızdaki astrolojik uyumluluğu keşfedin</p>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="feature-card text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-chart-line fa-3x text-success"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Detaylı Analizler</h4>
                    <p class="text-muted">AI destekli kapsamlı astroloji analizleri ve öneriler</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-gradient-cosmic text-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="h1 fw-bold mb-3">Kişiselleştirilmiş Deneyim</h2>
                <p class="lead mb-4">Üye olun ve özel astroloji analizlerinize erişin. Günlük bildirimler, favori burçlar ve daha fazlası!</p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="/register" class="btn btn-light btn-lg">Ücretsiz Üye Ol</a>
                    <a href="/tarot" class="btn btn-outline-light btn-lg">Tarot Falı</a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Element Colors */
.text-fire { color: #ff6b35 !important; }
.text-earth { color: #8b4513 !important; }
.text-air { color: #87ceeb !important; }
.text-water { color: #4682b4 !important; }

.bg-fire { background-color: #ff6b35 !important; }
.bg-earth { background-color: #8b4513 !important; }
.bg-air { background-color: #87ceeb !important; }
.bg-water { background-color: #4682b4 !important; }

/* Zodiac Cards */
.zodiac-card {
    transition: all 0.3s ease;
    border-radius: 15px !important;
}

.zodiac-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

.zodiac-icon {
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.element-badge .badge {
    font-size: 0.8rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
}

.daily-preview {
    background: rgba(0,123,255,0.1);
    border-radius: 10px;
    padding: 1rem;
}

/* Feature Cards */
.feature-card {
    background: white;
    border-radius: 15px;
    border: 1px solid rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.feature-icon {
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .zodiac-wheel {
        width: 200px !important;
        height: 200px !important;
    }
    
    .display-4 {
        font-size: 2.5rem;
    }
    
    .btn-group-sm .btn {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
}
</style>

<?php $content = ob_get_clean(); ?>

<?php include '../views/layouts/main.php'; ?>