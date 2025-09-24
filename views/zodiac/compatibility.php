<?php $title = $page_title; ?>

<?php ob_start(); ?>

<!-- Compatibility Header -->
<section class="compatibility-header py-5 bg-gradient-cosmic text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">Burç Uyumluluğu</h1>
                <p class="lead mb-4">
                    <?= htmlspecialchars($sign1['name']) ?> ve <?= htmlspecialchars($sign2['name']) ?> 
                    burçları arasındaki astrolojik uyumluluğu keşfedin
                </p>
            </div>
            <div class="col-lg-4 text-center">
                <div class="compatibility-icons d-flex justify-content-center align-items-center">
                    <div class="sign-icon me-3">
                        <i class="fas fa-<?= strtolower($sign1['symbol']) ?> fa-4x text-<?= $sign1['element'] ?>"></i>
                        <div class="small mt-2"><?= $sign1['name'] ?></div>
                    </div>
                    <div class="heart-icon mx-3">
                        <i class="fas fa-heart fa-2x text-danger pulse"></i>
                    </div>
                    <div class="sign-icon ms-3">
                        <i class="fas fa-<?= strtolower($sign2['symbol']) ?> fa-4x text-<?= $sign2['element'] ?>"></i>
                        <div class="small mt-2"><?= $sign2['name'] ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Compatibility Overview -->
<?php if ($compatibility): ?>
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-white text-center py-4">
                        <h2 class="h3 fw-bold mb-3">Genel Uyumluluk</h2>
                        <div class="compatibility-score">
                            <div class="score-circle mx-auto mb-3" style="width: 120px; height: 120px;">
                                <div class="circle-progress" data-percentage="<?= $compatibility['overall_score'] ?>">
                                    <div class="circle-content">
                                        <span class="score-number"><?= $compatibility['overall_score'] ?>%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="score-label">
                                <?php if ($compatibility['overall_score'] >= 80): ?>
                                    <span class="badge bg-success fs-6 px-3 py-2">Mükemmel Uyum</span>
                                <?php elseif ($compatibility['overall_score'] >= 60): ?>
                                    <span class="badge bg-primary fs-6 px-3 py-2">İyi Uyum</span>
                                <?php elseif ($compatibility['overall_score'] >= 40): ?>
                                    <span class="badge bg-warning fs-6 px-3 py-2">Orta Uyum</span>
                                <?php else: ?>
                                    <span class="badge bg-danger fs-6 px-3 py-2">Zor Uyum</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="compatibility-description">
                            <p class="lead text-center mb-4"><?= htmlspecialchars($compatibility['description']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Detailed Scores -->
<?php if ($compatibility): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h2 class="h3 fw-bold text-center mb-5">Detaylı Uyumluluk Analizi</h2>
                <div class="row g-4">
                    <!-- Love Compatibility -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-circle bg-danger-soft text-danger me-3">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1">Aşk & Romantizm</h5>
                                        <div class="score-badge">
                                            <span class="badge bg-danger"><?= $compatibility['love_score'] ?>%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress mb-3" style="height: 12px;">
                                    <div class="progress-bar bg-danger" style="width: <?= $compatibility['love_score'] ?>%"></div>
                                </div>
                                <p class="text-muted small"><?= htmlspecialchars($compatibility['love_analysis']) ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Friendship Compatibility -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-circle bg-primary-soft text-primary me-3">
                                        <i class="fas fa-user-friends"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1">Arkadaşlık</h5>
                                        <div class="score-badge">
                                            <span class="badge bg-primary"><?= $compatibility['friendship_score'] ?>%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress mb-3" style="height: 12px;">
                                    <div class="progress-bar bg-primary" style="width: <?= $compatibility['friendship_score'] ?>%"></div>
                                </div>
                                <p class="text-muted small"><?= htmlspecialchars($compatibility['friendship_analysis']) ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Work Compatibility -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-circle bg-success-soft text-success me-3">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1">İş & Kariyer</h5>
                                        <div class="score-badge">
                                            <span class="badge bg-success"><?= $compatibility['work_score'] ?>%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress mb-3" style="height: 12px;">
                                    <div class="progress-bar bg-success" style="width: <?= $compatibility['work_score'] ?>%"></div>
                                </div>
                                <p class="text-muted small"><?= htmlspecialchars($compatibility['work_analysis']) ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Communication Compatibility -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-circle bg-info-soft text-info me-3">
                                        <i class="fas fa-comments"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1">İletişim</h5>
                                        <div class="score-badge">
                                            <span class="badge bg-info"><?= $compatibility['communication_score'] ?>%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress mb-3" style="height: 12px;">
                                    <div class="progress-bar bg-info" style="width: <?= $compatibility['communication_score'] ?>%"></div>
                                </div>
                                <p class="text-muted small"><?= htmlspecialchars($compatibility['communication_analysis']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- AI Analysis -->
<?php if ($ai_analysis): ?>
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-gradient-cosmic text-white text-center py-4">
                        <h2 class="h3 fw-bold mb-2">
                            <i class="fas fa-robot me-2"></i>
                            AI Astroloji Analizi
                        </h2>
                        <p class="mb-0 opacity-75">Yapay zeka destekli detaylı uyumluluk yorumu</p>
                    </div>
                    <div class="card-body p-4">
                        <div class="ai-analysis-content">
                            <?= nl2br(htmlspecialchars($ai_analysis)) ?>
                        </div>
                        <div class="mt-4 pt-3 border-top">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Bu analiz AI tarafından burç özelliklerine göre oluşturulmuştur.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Tips & Advice -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h2 class="h3 fw-bold text-center mb-5">İlişki Önerileri</h2>
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm text-center h-100">
                            <div class="card-body p-4">
                                <div class="icon-circle bg-success-soft text-success mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-lightbulb fa-2x"></i>
                                </div>
                                <h5 class="card-title">Güçlü Yanlar</h5>
                                <p class="text-muted small">Bu iki burç arasındaki doğal uyum alanları ve güçlü yönleri keşfedin.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm text-center h-100">
                            <div class="card-body p-4">
                                <div class="icon-circle bg-warning-soft text-warning mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                                </div>
                                <h5 class="card-title">Dikkat Edilecekler</h5>
                                <p class="text-muted small">Potansiyel çatışma alanları ve bunların nasıl yönetileceği.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm text-center h-100">
                            <div class="card-body p-4">
                                <div class="icon-circle bg-info-soft text-info mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-chart-line fa-2x"></i>
                                </div>
                                <h5 class="card-title">Gelişim Fırsatları</h5>
                                <p class="text-muted small">İlişkiyi güçlendirmek için yapılabilecekler.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Other Compatibility Tests -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="h3 fw-bold mb-4">Başka Uyumluluklar Test Et</h2>
                <p class="text-muted mb-4">Diğer burç kombinasyonlarını keşfet</p>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-grid">
                            <a href="/compatibility" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-search me-2"></i>
                                Yeni Uyumluluk Testi
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-grid">
                            <a href="/zodiac" class="btn btn-primary btn-lg">
                                <i class="fas fa-star me-2"></i>
                                Tüm Burçlar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Element colors */
.text-fire { color: #ff6b35 !important; }
.text-earth { color: #8b4513 !important; }
.text-air { color: #87ceeb !important; }
.text-water { color: #4682b4 !important; }

/* Compatibility icons */
.compatibility-icons {
    background: rgba(255,255,255,0.1);
    border-radius: 20px;
    padding: 2rem;
}

.sign-icon {
    text-align: center;
}

.pulse {
    animation: pulse 1.5s ease-in-out infinite alternate;
}

@keyframes pulse {
    from { transform: scale(1); }
    to { transform: scale(1.1); }
}

/* Score circle */
.score-circle {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.circle-progress {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: conic-gradient(
        var(--primary-color) 0deg,
        var(--primary-color) calc(var(--percentage) * 3.6deg),
        #e9ecef calc(var(--percentage) * 3.6deg),
        #e9ecef 360deg
    );
    display: flex;
    align-items: center;
    justify-content: center;
}

.circle-progress::before {
    content: '';
    position: absolute;
    width: 80%;
    height: 80%;
    background: white;
    border-radius: 50%;
}

.circle-content {
    position: relative;
    z-index: 2;
    text-align: center;
}

.score-number {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--primary-color);
}

/* Icon circles */
.icon-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-danger-soft { background-color: rgba(220, 53, 69, 0.1) !important; }
.bg-primary-soft { background-color: rgba(13, 110, 253, 0.1) !important; }
.bg-success-soft { background-color: rgba(25, 135, 84, 0.1) !important; }
.bg-info-soft { background-color: rgba(13, 202, 240, 0.1) !important; }
.bg-warning-soft { background-color: rgba(255, 193, 7, 0.1) !important; }

/* AI Analysis */
.ai-analysis-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
    background: linear-gradient(135deg, #f8f9fa, #ffffff);
    padding: 2rem;
    border-radius: 15px;
    border-left: 4px solid var(--primary-color);
}

/* Progress bars */
.progress {
    border-radius: 10px;
    background: rgba(0,0,0,0.1);
}

.progress-bar {
    border-radius: 10px;
    transition: width 0.8s ease;
}

/* Cards */
.card {
    border-radius: 15px !important;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .compatibility-icons {
        padding: 1rem;
    }
    
    .sign-icon i {
        font-size: 2.5rem !important;
    }
    
    .heart-icon i {
        font-size: 1.5rem !important;
    }
    
    .display-5 {
        font-size: 2rem;
    }
    
    .ai-analysis-content {
        padding: 1.5rem;
        font-size: 1rem;
    }
}
</style>

<script>
// Set CSS custom property for circle progress
document.addEventListener('DOMContentLoaded', function() {
    const progressCircles = document.querySelectorAll('.circle-progress');
    progressCircles.forEach(circle => {
        const percentage = circle.getAttribute('data-percentage');
        circle.style.setProperty('--percentage', percentage);
    });
    
    // Animate progress bars
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });
});
</script>

<?php $content = ob_get_clean(); ?>