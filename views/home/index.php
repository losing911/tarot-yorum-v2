<!-- Hero Section -->
<section class="hero-section gradient-bg text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    🔮 Yapay Zeka ile<br>
                    <span class="text-warning">Geleceğini Keşfet</span>
                </h1>
                <p class="lead mb-4">
                    AI destekli tarot falı, günlük burç yorumları ve kişisel astroloji rehberin. 
                    Sorularını sor, kartlarını çek ve geleceğine dair ipuçları al.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="/tarot" class="btn btn-warning btn-lg">
                        <i class="bi bi-magic me-2"></i>Hemen Tarot Bak
                    </a>
                    <a href="/zodiac" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-star me-2"></i>Burç Yorumları
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="position-relative">
                    <div class="floating-cards">
                        <div class="card-float" style="animation-delay: 0s;">🌟</div>
                        <div class="card-float" style="animation-delay: 1s;">🔮</div>
                        <div class="card-float" style="animation-delay: 2s;">⭐</div>
                        <div class="card-float" style="animation-delay: 3s;">🌙</div>
                    </div>
                    <img src="/assets/images/tarot-hero.png" alt="Tarot Cards" class="img-fluid" style="max-height: 400px;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="h2 text-primary fw-bold">50K+</div>
                <p class="text-muted">Mutlu Kullanıcı</p>
            </div>
            <div class="col-md-3 mb-4">
                <div class="h2 text-primary fw-bold">100K+</div>
                <p class="text-muted">Tarot Falı</p>
            </div>
            <div class="col-md-3 mb-4">
                <div class="h2 text-primary fw-bold">365</div>
                <p class="text-muted">Günlük Burç Yorumu</p>
            </div>
            <div class="col-md-3 mb-4">
                <div class="h2 text-primary fw-bold">99%</div>
                <p class="text-muted">Memnuniyet</p>
            </div>
        </div>
    </div>
</section>

<!-- Zodiac Signs Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-primary mb-3">
                <i class="bi bi-stars me-3"></i>Burç Yorumları
            </h2>
            <p class="lead text-muted">Burcunuza özel günlük, haftalık ve aylık yorumları keşfedin</p>
        </div>
        
        <div class="zodiac-grid">
            <?php foreach ($zodiac_signs as $sign): ?>
                <a href="/zodiac/<?= $sign['sign'] ?>" class="zodiac-card">
                    <div class="zodiac-symbol"><?= $sign['symbol'] ?></div>
                    <h5 class="fw-bold mb-2"><?= htmlspecialchars($sign['name']) ?></h5>
                    <p class="text-muted small mb-2"><?= htmlspecialchars($sign['date_range']) ?></p>
                    <p class="text-muted small"><?= htmlspecialchars($sign['element']) ?></p>
                </a>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="/zodiac" class="btn btn-gradient btn-lg">
                <i class="bi bi-arrow-right me-2"></i>Tüm Burçları Gör
            </a>
        </div>
    </div>
</section>

<!-- AdSense Banner -->
<?php if (defined('GOOGLE_ADSENSE_ENABLED') && GOOGLE_ADSENSE_ENABLED): ?>
<div class="adsense-container">
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="<?= GOOGLE_ADSENSE_CLIENT ?>"
         data-ad-slot="1234567890"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
    <script>
         (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
</div>
<?php endif; ?>

<!-- Today's Horoscope Section -->
<?php if (!empty($today_horoscope)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold text-primary mb-3">
                <i class="bi bi-sun me-3"></i>Bugünün Burç Yorumları
            </h2>
            <p class="lead text-muted">Tüm burçlar için güncel yorumlar</p>
        </div>
        
        <div class="row">
            <?php foreach (array_slice($today_horoscope, 0, 6) as $horoscope): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card card-custom h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <span class="zodiac-symbol me-3" style="font-size: 2rem;">
                                    <?= $horoscope['sign']['symbol'] ?>
                                </span>
                                <div>
                                    <h5 class="card-title mb-1"><?= htmlspecialchars($horoscope['sign']['name']) ?></h5>
                                    <small class="text-muted"><?= date('d.m.Y') ?></small>
                                </div>
                            </div>
                            <p class="card-text"><?= htmlspecialchars($horoscope['summary']) ?></p>
                            <div class="row text-center mt-3">
                                <div class="col-3">
                                    <small class="text-muted d-block">Aşk</small>
                                    <div class="text-danger">
                                        <?php for ($i = 0; $i < 5; $i++): ?>
                                            <i class="bi bi-heart<?= $i < ($horoscope['reading']['love_score'] / 20) ? '-fill' : '' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <small class="text-muted d-block">Kariyer</small>
                                    <div class="text-primary">
                                        <?php for ($i = 0; $i < 5; $i++): ?>
                                            <i class="bi bi-star<?= $i < ($horoscope['reading']['career_score'] / 20) ? '-fill' : '' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <small class="text-muted d-block">Sağlık</small>
                                    <div class="text-success">
                                        <?php for ($i = 0; $i < 5; $i++): ?>
                                            <i class="bi bi-heart-pulse<?= $i < ($horoscope['reading']['health_score'] / 20) ? '-fill' : '' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <small class="text-muted d-block">Para</small>
                                    <div class="text-warning">
                                        <?php for ($i = 0; $i < 5; $i++): ?>
                                            <i class="bi bi-coin"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                            <a href="/zodiac/<?= $horoscope['sign']['slug'] ?>" class="btn btn-outline-primary btn-sm mt-3">
                                Detaylı Oku <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Featured Tarot Readings -->
<?php if (!empty($recent_readings)): ?>
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold text-primary mb-3">
                <i class="bi bi-magic me-3"></i>Son Tarot Falları
            </h2>
            <p class="lead text-muted">Topluluk üyelerinin paylaştığı son tarot falları</p>
        </div>
        
        <div class="row">
            <?php foreach ($recent_readings as $reading): ?>
                <div class="col-lg-4 mb-4">
                    <div class="card card-custom h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0"><?= htmlspecialchars($reading['display_name']) ?></h6>
                                    <small class="text-muted"><?= date('d.m.Y H:i', strtotime($reading['created_at'])) ?></small>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <span class="badge bg-secondary"><?= ucfirst(str_replace('_', ' ', $reading['spread_type'])) ?></span>
                            </div>
                            
                            <p class="card-text"><?= htmlspecialchars($reading['summary']) ?></p>
                            
                            <div class="d-flex gap-1 mb-3">
                                <?php foreach (array_slice($reading['cards_drawn'], 0, 3) as $card): ?>
                                    <div class="bg-light rounded p-2 text-center flex-fill">
                                        <small class="text-muted d-block"><?= htmlspecialchars($card['name']) ?></small>
                                        <?php if ($card['reversed']): ?>
                                            <small class="text-danger">Ters</small>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <a href="/tarot/reading/<?= $reading['id'] ?>" class="btn btn-outline-primary btn-sm">
                                Detayını Gör <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="/tarot" class="btn btn-gradient btn-lg">
                <i class="bi bi-magic me-2"></i>Sen de Tarot Bak
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Blog Section -->
<?php if (!empty($featured_posts)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold text-primary mb-3">
                <i class="bi bi-journal-text me-3"></i>Blog Yazıları
            </h2>
            <p class="lead text-muted">Astroloji, tarot ve ruhani gelişim hakkında en güncel yazılar</p>
        </div>
        
        <div class="row">
            <?php foreach (array_slice($featured_posts, 0, 3) as $post): ?>
                <div class="col-lg-4 mb-4">
                    <div class="card card-custom h-100">
                        <?php if ($post['featured_image']): ?>
                            <img src="<?= htmlspecialchars($post['featured_image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($post['title']) ?>" style="height: 200px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-primary"><?= htmlspecialchars($post['category_name']) ?></span>
                                <small class="text-muted"><?= date('d.m.Y', strtotime($post['published_at'])) ?></small>
                            </div>
                            <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
                            <p class="card-text text-muted"><?= htmlspecialchars($post['excerpt']) ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-person me-1"></i>
                                    <?= htmlspecialchars($post['first_name'] . ' ' . $post['last_name']) ?>
                                </small>
                                <a href="/blog/<?= $post['slug'] ?>" class="btn btn-outline-primary btn-sm">
                                    Oku <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="/blog" class="btn btn-gradient btn-lg">
                <i class="bi bi-arrow-right me-2"></i>Tüm Yazıları Gör
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="py-5 gradient-bg text-white">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="display-5 fw-bold mb-4">
                    🌟 Ruhani Yolculuğuna Başla
                </h2>
                <p class="lead mb-4">
                    Ücretsiz hesabın oluştur, kişisel burç yorumlarına erişim sağla ve tarot geçmişini takip et. 
                    Topluluk üyeleri ile deneyimlerini paylaş.
                </p>
                <?php if (!$current_user): ?>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="/register" class="btn btn-warning btn-lg">
                            <i class="bi bi-star me-2"></i>Ücretsiz Kayıt Ol
                        </a>
                        <a href="/login" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Giriş Yap
                        </a>
                    </div>
                <?php else: ?>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="/tarot" class="btn btn-warning btn-lg">
                            <i class="bi bi-magic me-2"></i>Tarot Falı Bak
                        </a>
                        <a href="/profile" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-person me-2"></i>Profilim
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
    .floating-cards {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
    }
    
    .card-float {
        position: absolute;
        font-size: 2rem;
        animation: float 4s ease-in-out infinite;
        opacity: 0.7;
    }
    
    .card-float:nth-child(1) { top: 10%; left: 10%; }
    .card-float:nth-child(2) { top: 20%; right: 15%; }
    .card-float:nth-child(3) { bottom: 30%; left: 20%; }
    .card-float:nth-child(4) { bottom: 10%; right: 10%; }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    .hero-section {
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('/assets/images/stars-pattern.png') repeat;
        opacity: 0.1;
        pointer-events: none;
    }
</style>