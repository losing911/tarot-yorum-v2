<?php $title = $page_title; ?>
<?php include '../views/layouts/main.php'; ?>

<?php ob_start(); ?>

<!-- Hero Section -->
<section class="hero-section py-5 bg-gradient-cosmic text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">Tarot Falı</h1>
                <p class="lead mb-4">AI destekli profesyonel tarot okumalarıyla geleceğinizi keşfedin. Celtic Cross, 3 kart ve Yes/No yayılımları ile detaylı yorumlar.</p>
                <div class="d-flex gap-3">
                    <a href="#spreads" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-cards-blank me-2"></i>Fala Başla
                    </a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/tarot/daily" class="btn btn-light btn-lg">
                        <i class="fas fa-star me-2"></i>Günlük Kart
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="tarot-cards-animation">
                    <div class="card-stack">
                        <div class="tarot-card card-1"></div>
                        <div class="tarot-card card-2"></div>
                        <div class="tarot-card card-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Reading -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="h1 fw-bold text-gradient mb-4">Hızlı Tarot Okuma</h2>
                <p class="lead text-muted mb-4">Acil bir sorunuz mu var? Tek kart çekerek hızlı bir rehberlik alın.</p>
                
                <div class="quick-reading-card mb-4">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="card-title fw-bold mb-3">Günün Rehber Kartı</h5>
                                    <p class="text-muted mb-3">Bugün sizin için en uygun rehberlik kartını çekin</p>
                                    <button class="btn btn-primary" onclick="drawQuickCard()">
                                        <i class="fas fa-magic me-2"></i>Kart Çek
                                    </button>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="quick-card-placeholder" id="quickCardPlaceholder">
                                        <i class="fas fa-cards-blank fa-4x text-muted"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tarot Spreads -->
<section id="spreads" class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="h1 fw-bold">Tarot Yayılımları</h2>
                <p class="lead text-muted">İhtiyacınıza göre farklı yayılım türlerini seçin</p>
            </div>
        </div>
        
        <div class="row g-4">
            <?php foreach ($spreads as $spread): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card spread-card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-header bg-gradient-cosmic text-white text-center py-4">
                        <div class="spread-icon mb-3">
                            <i class="fas fa-<?= $spread['icon'] ?> fa-3x"></i>
                        </div>
                        <h4 class="card-title fw-bold mb-0"><?= htmlspecialchars($spread['name']) ?></h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="spread-info mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-primary"><?= $spread['card_count'] ?> Kart</span>
                                <span class="badge bg-info"><?= $spread['difficulty'] ?></span>
                            </div>
                        </div>
                        
                        <p class="text-muted mb-4"><?= htmlspecialchars($spread['description']) ?></p>
                        
                        <div class="spread-features mb-4">
                            <h6 class="fw-bold mb-2">İdeal Konular:</h6>
                            <div class="features-list">
                                <?php 
                                $topics = explode(',', $spread['suitable_topics']);
                                foreach ($topics as $topic): ?>
                                <span class="badge bg-light text-dark me-1 mb-1"><?= trim(htmlspecialchars($topic)) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <a href="/tarot/spread/<?= $spread['type'] ?>" class="btn btn-primary">
                                <i class="fas fa-play me-2"></i>Başla
                            </a>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-center">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            Yaklaşık <?= $spread['estimated_time'] ?> dakika
                        </small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Recent Readings -->
<?php if (!empty($recent_readings)): ?>
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="h3 fw-bold text-center mb-4">Son Okumalarım</h2>
                <div class="recent-readings">
                    <?php foreach ($recent_readings as $reading): ?>
                    <div class="card border-0 shadow-sm mb-3 hover-lift">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="fw-bold mb-1"><?= htmlspecialchars($reading['spread_name']) ?></h6>
                                    <p class="text-muted small mb-1">
                                        <?= mb_substr(htmlspecialchars($reading['question']), 0, 60) ?>...
                                    </p>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?= date('d.m.Y H:i', strtotime($reading['created_at'])) ?>
                                    </small>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <a href="/tarot/result/<?= $reading['id'] ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Görüntüle
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <div class="text-center mt-4">
                        <a href="/tarot/history" class="btn btn-outline-secondary">
                            <i class="fas fa-history me-2"></i>Tüm Geçmişi Gör
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Features Section -->
<section class="py-5 bg-gradient-cosmic text-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="h1 fw-bold">Neden Bizim Tarot?</h2>
                <p class="lead">Modern teknoloji ile geleneksel tarot bilgisini birleştiriyoruz</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6 text-center">
                <div class="feature-item">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-robot fa-3x"></i>
                    </div>
                    <h5 class="fw-bold mb-2">AI Destekli</h5>
                    <p class="small opacity-75">Yapay zeka ile kişiselleştirilmiş yorumlar</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 text-center">
                <div class="feature-item">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-book fa-3x"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Profesyonel</h5>
                    <p class="small opacity-75">Geleneksel tarot bilgisi temelli</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 text-center">
                <div class="feature-item">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-lock fa-3x"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Gizli & Güvenli</h5>
                    <p class="small opacity-75">Verileriniz tamamen güvende</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 text-center">
                <div class="feature-item">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-mobile-alt fa-3x"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Mobil Uyumlu</h5>
                    <p class="small opacity-75">Her yerden erişilebilir</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Card Modal -->
<div class="modal fade" id="quickCardModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-cosmic text-white">
                <h5 class="modal-title">
                    <i class="fas fa-star me-2"></i>Günün Rehber Kartı
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div id="quickCardContent">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Yükleniyor...</span>
                    </div>
                    <p>Kartınız hazırlanıyor...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                <button type="button" class="btn btn-primary" onclick="drawQuickCard()">Yeni Kart Çek</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Tarot Cards Animation */
.tarot-cards-animation {
    perspective: 1000px;
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-stack {
    position: relative;
    width: 200px;
    height: 280px;
}

.tarot-card {
    position: absolute;
    width: 120px;
    height: 180px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    border: 3px solid #ffd700;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    transition: all 0.8s ease;
}

.card-1 {
    transform: rotate(-15deg) translateX(-20px);
    animation: cardFloat1 3s ease-in-out infinite;
}

.card-2 {
    transform: rotate(0deg);
    animation: cardFloat2 3s ease-in-out infinite 0.5s;
}

.card-3 {
    transform: rotate(15deg) translateX(20px);
    animation: cardFloat3 3s ease-in-out infinite 1s;
}

@keyframes cardFloat1 {
    0%, 100% { transform: rotate(-15deg) translateX(-20px) translateY(0px); }
    50% { transform: rotate(-15deg) translateX(-20px) translateY(-10px); }
}

@keyframes cardFloat2 {
    0%, 100% { transform: rotate(0deg) translateY(0px); }
    50% { transform: rotate(0deg) translateY(-15px); }
}

@keyframes cardFloat3 {
    0%, 100% { transform: rotate(15deg) translateX(20px) translateY(0px); }
    50% { transform: rotate(15deg) translateX(20px) translateY(-10px); }
}

/* Spread Cards */
.spread-card {
    transition: all 0.3s ease;
    border-radius: 20px !important;
}

.spread-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.1) !important;
}

.spread-icon {
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.features-list .badge {
    font-size: 0.75rem;
}

/* Quick Card */
.quick-card-placeholder {
    width: 80px;
    height: 120px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 10px;
    border: 2px dashed #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    transition: all 0.3s ease;
}

.quick-card-placeholder:hover {
    transform: scale(1.05);
}

.quick-card-result {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: 3px solid #ffd700;
    color: white;
    font-weight: bold;
    animation: cardReveal 0.8s ease-out;
}

@keyframes cardReveal {
    from {
        transform: rotateY(90deg);
        opacity: 0;
    }
    to {
        transform: rotateY(0deg);
        opacity: 1;
    }
}

/* Feature Items */
.feature-item {
    padding: 2rem;
    transition: all 0.3s ease;
}

.feature-item:hover {
    transform: translateY(-5px);
}

.feature-icon {
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Responsive */
@media (max-width: 768px) {
    .tarot-cards-animation {
        height: 200px;
    }
    
    .card-stack {
        width: 150px;
        height: 200px;
    }
    
    .tarot-card {
        width: 80px;
        height: 120px;
    }
    
    .display-4 {
        font-size: 2.5rem;
    }
}
</style>

<script>
function drawQuickCard() {
    const modal = new bootstrap.Modal(document.getElementById('quickCardModal'));
    const content = document.getElementById('quickCardContent');
    
    // Show loading
    content.innerHTML = `
        <div class="spinner-border text-primary mb-3" role="status">
            <span class="visually-hidden">Yükleniyor...</span>
        </div>
        <p>Kartınız hazırlanıyor...</p>
    `;
    
    modal.show();
    
    // Fetch random card
    fetch('/tarot/api/random-cards/1')
        .then(response => response.json())
        .then(data => {
            if (data.cards && data.cards.length > 0) {
                const card = data.cards[0];
                displayQuickCard(card);
            } else {
                throw new Error('Kart alınamadı');
            }
        })
        .catch(error => {
            content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Kart çekilemedi. Lütfen tekrar deneyin.
                </div>
            `;
        });
}

function displayQuickCard(card) {
    const content = document.getElementById('quickCardContent');
    
    content.innerHTML = `
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="quick-card-result mx-auto mb-3" style="width: 120px; height: 180px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                    <i class="fas fa-${card.suit_icon} fa-2x mb-2"></i>
                    <div class="text-center">
                        <div class="fw-bold">${card.name}</div>
                        <small>${card.suit}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-8 text-start">
                <h5 class="fw-bold mb-3">${card.name}</h5>
                <p class="text-muted mb-3">${card.meaning_upright}</p>
                <div class="d-flex gap-2 mb-3">
                    <span class="badge bg-primary">${card.arcana}</span>
                    <span class="badge bg-info">${card.element || 'Element'}</span>
                </div>
                <p class="small text-muted">
                    <strong>Anahtar Kelimeler:</strong> ${card.keywords}
                </p>
            </div>
        </div>
    `;
}

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>

<?php $content = ob_get_clean(); ?>

<?php include '../views/layouts/main.php'; ?>