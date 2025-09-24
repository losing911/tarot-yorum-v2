<?php $title = $page_title; ?>

<?php ob_start(); ?>

<!-- Reading Header -->
<section class="reading-header py-4 bg-gradient-cosmic text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center">
                    <div class="zodiac-icon me-3">
                        <i class="fas fa-<?= strtolower($sign['symbol']) ?> fa-3x text-white"></i>
                    </div>
                    <div>
                        <h1 class="h2 fw-bold mb-1"><?= htmlspecialchars($sign['name']) ?></h1>
                        <p class="mb-0 opacity-75">
                            <?php if ($reading_type === 'daily'): ?>
                                <i class="fas fa-sun me-2"></i>Günlük Yorum - <?= date('d.m.Y') ?>
                            <?php elseif ($reading_type === 'weekly'): ?>
                                <i class="fas fa-calendar-week me-2"></i>Haftalık Yorum - <?= $week_range ?>
                            <?php elseif ($reading_type === 'monthly'): ?>
                                <i class="fas fa-calendar-alt me-2"></i>Aylık Yorum - <?= $month_name ?>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-end">
                <a href="/zodiac/<?= $sign['slug'] ?>" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-2"></i>Burç Sayfasına Dön
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Reading Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-white border-0 pt-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="h4 fw-bold mb-1">
                                    <?php if ($reading_type === 'daily'): ?>
                                        <i class="fas fa-sun text-warning me-2"></i>Günlük Astroloji Yorumu
                                    <?php elseif ($reading_type === 'weekly'): ?>
                                        <i class="fas fa-calendar-week text-info me-2"></i>Haftalık Astroloji Yorumu
                                    <?php elseif ($reading_type === 'monthly'): ?>
                                        <i class="fas fa-calendar-alt text-success me-2"></i>Aylık Astroloji Yorumu
                                    <?php endif; ?>
                                </h2>
                                <p class="text-muted mb-0">AI destekli kişiselleştirilmiş yorum</p>
                            </div>
                            <div class="reading-type-badge">
                                <span class="badge bg-primary px-3 py-2">
                                    <?= ucfirst($reading_type) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <?php if ($reading): ?>
                        
                        <!-- Reading Content -->
                        <div class="reading-content mb-4">
                            <div class="content-text">
                                <?= nl2br(htmlspecialchars($reading['content'])) ?>
                            </div>
                        </div>
                        
                        <!-- Update Info -->
                        <div class="reading-meta pt-3 border-top">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        Son güncelleme: <?= date('d.m.Y H:i', strtotime($reading['generated_at'])) ?>
                                    </small>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <small class="text-muted">
                                        <i class="fas fa-robot me-1"></i>
                                        AI tarafından oluşturuldu
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <?php else: ?>
                        
                        <!-- Loading State -->
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary mb-3" role="status">
                                <span class="visually-hidden">Yükleniyor...</span>
                            </div>
                            <h5 class="text-muted">Yorumunuz Hazırlanıyor</h5>
                            <p class="text-muted">AI destekli kişiselleştirilmiş yorumunuz oluşturuluyor...</p>
                        </div>
                        
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Social Share -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Paylaş</h5>
                        <p class="text-muted small">Bu yorumu sosyal medyada paylaş</p>
                        <div class="d-flex gap-3">
                            <a href="#" class="btn btn-outline-primary btn-sm" onclick="shareOnFacebook()">
                                <i class="fab fa-facebook-f me-2"></i>Facebook
                            </a>
                            <a href="#" class="btn btn-outline-info btn-sm" onclick="shareOnTwitter()">
                                <i class="fab fa-twitter me-2"></i>Twitter
                            </a>
                            <a href="#" class="btn btn-outline-success btn-sm" onclick="shareOnWhatsApp()">
                                <i class="fab fa-whatsapp me-2"></i>WhatsApp
                            </a>
                            <button class="btn btn-outline-secondary btn-sm" onclick="copyLink()">
                                <i class="fas fa-link me-2"></i>Linki Kopyala
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                
                <?php if ($reading): ?>
                <!-- Scores Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar me-2 text-primary"></i>
                            Skorlar
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Love Score -->
                        <div class="score-item mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-medium">
                                    <i class="fas fa-heart text-danger me-2"></i>Aşk & İlişkiler
                                </span>
                                <span class="fw-bold text-danger"><?= $reading['love_score'] ?>%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-danger" role="progressbar" 
                                     style="width: <?= $reading['love_score'] ?>%" 
                                     aria-valuenow="<?= $reading['love_score'] ?>" 
                                     aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Career Score -->
                        <div class="score-item mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-medium">
                                    <i class="fas fa-briefcase text-primary me-2"></i>Kariyer & İş
                                </span>
                                <span class="fw-bold text-primary"><?= $reading['career_score'] ?>%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-primary" role="progressbar" 
                                     style="width: <?= $reading['career_score'] ?>%" 
                                     aria-valuenow="<?= $reading['career_score'] ?>" 
                                     aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Health Score -->
                        <div class="score-item mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-medium">
                                    <i class="fas fa-heart-pulse text-success me-2"></i>Sağlık & Enerji
                                </span>
                                <span class="fw-bold text-success"><?= $reading['health_score'] ?>%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: <?= $reading['health_score'] ?>%" 
                                     aria-valuenow="<?= $reading['health_score'] ?>" 
                                     aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Money Score -->
                        <div class="score-item mb-0">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-medium">
                                    <i class="fas fa-dollar-sign text-warning me-2"></i>Finans & Para
                                </span>
                                <span class="fw-bold text-warning"><?= $reading['money_score'] ?>%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-warning" role="progressbar" 
                                     style="width: <?= $reading['money_score'] ?>%" 
                                     aria-valuenow="<?= $reading['money_score'] ?>" 
                                     aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Quick Navigation -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-compass me-2 text-info"></i>
                            Hızlı Erişim
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="/zodiac/<?= $sign['slug'] ?>/daily" 
                               class="btn btn-outline-primary btn-sm <?= $reading_type === 'daily' ? 'active' : '' ?>">
                                <i class="fas fa-sun me-2"></i>Günlük
                            </a>
                            <a href="/zodiac/<?= $sign['slug'] ?>/weekly" 
                               class="btn btn-outline-info btn-sm <?= $reading_type === 'weekly' ? 'active' : '' ?>">
                                <i class="fas fa-calendar-week me-2"></i>Haftalık
                            </a>
                            <a href="/zodiac/<?= $sign['slug'] ?>/monthly" 
                               class="btn btn-outline-success btn-sm <?= $reading_type === 'monthly' ? 'active' : '' ?>">
                                <i class="fas fa-calendar-alt me-2"></i>Aylık
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Related Content -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-sparkles me-2 text-warning"></i>
                            İlgini Çekebilir
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="/tarot" class="list-group-item list-group-item-action border-0 px-0">
                                <i class="fas fa-cards-blank me-2 text-primary"></i>
                                <span>Tarot Falı Bak</span>
                            </a>
                            <a href="/compatibility" class="list-group-item list-group-item-action border-0 px-0">
                                <i class="fas fa-heart me-2 text-danger"></i>
                                <span>Uyumluluk Testi</span>
                            </a>
                            <a href="/blog" class="list-group-item list-group-item-action border-0 px-0">
                                <i class="fas fa-newspaper me-2 text-info"></i>
                                <span>Astroloji Blog</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.reading-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
}

.content-text {
    background: linear-gradient(135deg, #f8f9fa, #ffffff);
    padding: 2rem;
    border-radius: 15px;
    border-left: 4px solid var(--primary-color);
}

.score-item .progress {
    border-radius: 10px;
    background: rgba(0,0,0,0.1);
}

.score-item .progress-bar {
    border-radius: 10px;
    transition: width 0.6s ease;
}

.zodiac-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}

.reading-type-badge .badge {
    font-size: 0.9rem;
    border-radius: 20px;
}

.card {
    border-radius: 15px !important;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

/* Animation for progress bars */
@keyframes progressAnimation {
    from { width: 0%; }
}

.progress-bar {
    animation: progressAnimation 1s ease-out;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .content-text {
        padding: 1.5rem;
        font-size: 1rem;
    }
    
    .reading-header .h2 {
        font-size: 1.5rem;
    }
    
    .d-flex.gap-3 {
        flex-wrap: wrap;
    }
    
    .d-flex.gap-3 .btn {
        margin-bottom: 0.5rem;
    }
}
</style>

<script>
// Social sharing functions
function shareOnFacebook() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(document.title);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400');
}

function shareOnTwitter() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(document.title);
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${title}`, '_blank', 'width=600,height=400');
}

function shareOnWhatsApp() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(document.title);
    window.open(`https://wa.me/?text=${title} ${url}`, '_blank');
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        alert('Link kopyalandı!');
    }, function(err) {
        console.error('Link kopyalanamadı: ', err);
    });
}

// Initialize progress bar animations
document.addEventListener('DOMContentLoaded', function() {
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