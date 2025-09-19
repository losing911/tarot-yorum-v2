<?php $title = $page_title; ?>
<?php include '../views/layouts/main.php'; ?>

<?php ob_start(); ?>

<!-- Zodiac Sign Header -->
<section class="zodiac-header py-5 bg-gradient-cosmic text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-3">
                    <div class="zodiac-icon-large me-4">
                        <i class="fas fa-<?= strtolower($sign['symbol']) ?> fa-4x text-white"></i>
                    </div>
                    <div>
                        <h1 class="display-4 fw-bold mb-2"><?= htmlspecialchars($sign['name']) ?></h1>
                        <p class="lead mb-0"><?= htmlspecialchars($sign['date_range']) ?></p>
                        <span class="badge bg-<?= $sign['element'] ?> text-white mt-2 px-3 py-2">
                            <?= ucfirst($sign['element']) ?> Elementi
                        </span>
                    </div>
                </div>
                <p class="lead"><?= htmlspecialchars($sign['description']) ?></p>
            </div>
            <div class="col-lg-4 text-center">
                <div class="sign-constellation">
                    <img src="/assets/images/constellations/<?= $sign['slug'] ?>.svg" alt="<?= $sign['name'] ?> Takımyıldızı" class="img-fluid" style="max-width: 200px;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Navigation Tabs -->
<section class="py-3 bg-light border-bottom">
    <div class="container">
        <ul class="nav nav-pills nav-fill" id="readingTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="daily-tab" data-bs-toggle="pill" data-bs-target="#daily" type="button" role="tab">
                    <i class="fas fa-sun me-2"></i>Günlük
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="weekly-tab" data-bs-toggle="pill" data-bs-target="#weekly" type="button" role="tab">
                    <i class="fas fa-calendar-week me-2"></i>Haftalık
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="monthly-tab" data-bs-toggle="pill" data-bs-target="#monthly" type="button" role="tab">
                    <i class="fas fa-calendar-alt me-2"></i>Aylık
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="traits-tab" data-bs-toggle="pill" data-bs-target="#traits" type="button" role="tab">
                    <i class="fas fa-user me-2"></i>Özellikler
                </button>
            </li>
        </ul>
    </div>
</section>

<!-- Content Tabs -->
<section class="py-5">
    <div class="container">
        <div class="tab-content" id="readingTabsContent">
            
            <!-- Daily Reading -->
            <div class="tab-pane fade show active" id="daily" role="tabpanel">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h3 class="h5 mb-0">
                                    <i class="fas fa-sun me-2"></i>
                                    Günlük Yorum - <?= date('d.m.Y') ?>
                                </h3>
                            </div>
                            <div class="card-body">
                                <?php if ($daily_reading): ?>
                                <div class="reading-content">
                                    <?= nl2br(htmlspecialchars($daily_reading['content'])) ?>
                                </div>
                                <div class="mt-4">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        Güncelleme: <?= date('d.m.Y H:i', strtotime($daily_reading['generated_at'])) ?>
                                    </small>
                                </div>
                                <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-spinner fa-spin fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">Günlük yorumunuz hazırlanıyor...</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <?php if ($daily_reading): ?>
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h4 class="h6 mb-0">Günlük Skorlar</h4>
                            </div>
                            <div class="card-body">
                                <div class="score-item mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span><i class="fas fa-heart text-danger me-2"></i>Aşk</span>
                                        <span class="fw-bold"><?= $daily_reading['love_score'] ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-danger" style="width: <?= $daily_reading['love_score'] ?>%"></div>
                                    </div>
                                </div>
                                
                                <div class="score-item mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span><i class="fas fa-briefcase text-primary me-2"></i>Kariyer</span>
                                        <span class="fw-bold"><?= $daily_reading['career_score'] ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-primary" style="width: <?= $daily_reading['career_score'] ?>%"></div>
                                    </div>
                                </div>
                                
                                <div class="score-item mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span><i class="fas fa-heart-pulse text-success me-2"></i>Sağlık</span>
                                        <span class="fw-bold"><?= $daily_reading['health_score'] ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success" style="width: <?= $daily_reading['health_score'] ?>%"></div>
                                    </div>
                                </div>
                                
                                <div class="score-item mb-0">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span><i class="fas fa-dollar-sign text-warning me-2"></i>Para</span>
                                        <span class="fw-bold"><?= $daily_reading['money_score'] ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-warning" style="width: <?= $daily_reading['money_score'] ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Weekly Reading -->
            <div class="tab-pane fade" id="weekly" role="tabpanel">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-info text-white">
                                <h3 class="h5 mb-0">
                                    <i class="fas fa-calendar-week me-2"></i>
                                    Haftalık Yorum - <?= date('d.m', strtotime('monday this week')) ?> / <?= date('d.m.Y', strtotime('sunday this week')) ?>
                                </h3>
                            </div>
                            <div class="card-body">
                                <?php if ($weekly_reading): ?>
                                <div class="reading-content">
                                    <?= nl2br(htmlspecialchars($weekly_reading['content'])) ?>
                                </div>
                                <div class="mt-4">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        Güncelleme: <?= date('d.m.Y H:i', strtotime($weekly_reading['generated_at'])) ?>
                                    </small>
                                </div>
                                <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-spinner fa-spin fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">Haftalık yorumunuz hazırlanıyor...</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <?php if ($weekly_reading): ?>
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h4 class="h6 mb-0">Haftalık Skorlar</h4>
                            </div>
                            <div class="card-body">
                                <div class="score-item mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span><i class="fas fa-heart text-danger me-2"></i>Aşk</span>
                                        <span class="fw-bold"><?= $weekly_reading['love_score'] ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-danger" style="width: <?= $weekly_reading['love_score'] ?>%"></div>
                                    </div>
                                </div>
                                
                                <div class="score-item mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span><i class="fas fa-briefcase text-primary me-2"></i>Kariyer</span>
                                        <span class="fw-bold"><?= $weekly_reading['career_score'] ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-primary" style="width: <?= $weekly_reading['career_score'] ?>%"></div>
                                    </div>
                                </div>
                                
                                <div class="score-item mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span><i class="fas fa-heart-pulse text-success me-2"></i>Sağlık</span>
                                        <span class="fw-bold"><?= $weekly_reading['health_score'] ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success" style="width: <?= $weekly_reading['health_score'] ?>%"></div>
                                    </div>
                                </div>
                                
                                <div class="score-item mb-0">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span><i class="fas fa-dollar-sign text-warning me-2"></i>Para</span>
                                        <span class="fw-bold"><?= $weekly_reading['money_score'] ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-warning" style="width: <?= $weekly_reading['money_score'] ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Monthly Reading -->
            <div class="tab-pane fade" id="monthly" role="tabpanel">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-success text-white">
                                <h3 class="h5 mb-0">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    Aylık Yorum - <?= date('F Y') ?>
                                </h3>
                            </div>
                            <div class="card-body">
                                <?php if ($monthly_reading): ?>
                                <div class="reading-content">
                                    <?= nl2br(htmlspecialchars($monthly_reading['content'])) ?>
                                </div>
                                <div class="mt-4">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        Güncelleme: <?= date('d.m.Y H:i', strtotime($monthly_reading['generated_at'])) ?>
                                    </small>
                                </div>
                                <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-spinner fa-spin fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">Aylık yorumunuz hazırlanıyor...</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <?php if ($monthly_reading): ?>
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h4 class="h6 mb-0">Aylık Skorlar</h4>
                            </div>
                            <div class="card-body">
                                <div class="score-item mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span><i class="fas fa-heart text-danger me-2"></i>Aşk</span>
                                        <span class="fw-bold"><?= $monthly_reading['love_score'] ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-danger" style="width: <?= $monthly_reading['love_score'] ?>%"></div>
                                    </div>
                                </div>
                                
                                <div class="score-item mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span><i class="fas fa-briefcase text-primary me-2"></i>Kariyer</span>
                                        <span class="fw-bold"><?= $monthly_reading['career_score'] ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-primary" style="width: <?= $monthly_reading['career_score'] ?>%"></div>
                                    </div>
                                </div>
                                
                                <div class="score-item mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span><i class="fas fa-heart-pulse text-success me-2"></i>Sağlık</span>
                                        <span class="fw-bold"><?= $monthly_reading['health_score'] ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success" style="width: <?= $monthly_reading['health_score'] ?>%"></div>
                                    </div>
                                </div>
                                
                                <div class="score-item mb-0">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span><i class="fas fa-dollar-sign text-warning me-2"></i>Para</span>
                                        <span class="fw-bold"><?= $monthly_reading['money_score'] ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-warning" style="width: <?= $monthly_reading['money_score'] ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Traits Tab -->
            <div class="tab-pane fade" id="traits" role="tabpanel">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-dark text-white">
                                <h3 class="h5 mb-0">
                                    <i class="fas fa-user me-2"></i>
                                    <?= $sign['name'] ?> Burcu Özellikleri
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="text-success">Güçlü Yanları</h5>
                                        <ul class="list-unstyled">
                                            <?php 
                                            $strengths = explode(',', $sign['strengths']);
                                            foreach ($strengths as $strength): ?>
                                            <li class="mb-2">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                <?= trim(htmlspecialchars($strength)) ?>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="text-warning">Gelişim Alanları</h5>
                                        <ul class="list-unstyled">
                                            <?php 
                                            $weaknesses = explode(',', $sign['weaknesses']);
                                            foreach ($weaknesses as $weakness): ?>
                                            <li class="mb-2">
                                                <i class="fas fa-exclamation-circle text-warning me-2"></i>
                                                <?= trim(htmlspecialchars($weakness)) ?>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-planet-ringed me-2 text-primary"></i>Yönetici Gezegen</h6>
                                        <p class="text-muted"><?= htmlspecialchars($sign['ruling_planet']) ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-shapes me-2 text-info"></i>Element</h6>
                                        <p class="text-muted"><?= ucfirst($sign['element']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h4 class="h6 mb-0">Uyumlu Burçlar</h4>
                            </div>
                            <div class="card-body">
                                <?php 
                                $compatible = explode(',', $sign['compatible_signs']);
                                foreach ($compatible as $compat): ?>
                                <span class="badge bg-primary me-2 mb-2"><?= trim(htmlspecialchars($compat)) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="card border-0 shadow-sm mt-3">
                            <div class="card-header bg-light">
                                <h4 class="h6 mb-0">Şanslı Unsurlar</h4>
                            </div>
                            <div class="card-body">
                                <p><strong>Sayı:</strong> <?= htmlspecialchars($sign['lucky_numbers']) ?></p>
                                <p><strong>Renk:</strong> <?= htmlspecialchars($sign['lucky_colors']) ?></p>
                                <p><strong>Gün:</strong> <?= htmlspecialchars($sign['lucky_day']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Other Signs Navigation -->
<section class="py-4 bg-light">
    <div class="container">
        <h4 class="text-center mb-4">Diğer Burçlar</h4>
        <div class="row g-3">
            <?php foreach ($all_signs as $other_sign): ?>
                <?php if ($other_sign['id'] != $sign['id']): ?>
                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                    <a href="/zodiac/<?= $other_sign['slug'] ?>" class="text-decoration-none">
                        <div class="text-center p-3 bg-white rounded shadow-sm hover-lift">
                            <i class="fas fa-<?= strtolower($other_sign['symbol']) ?> fa-2x text-<?= $other_sign['element'] ?> mb-2"></i>
                            <div class="small fw-bold"><?= htmlspecialchars($other_sign['name']) ?></div>
                        </div>
                    </a>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
.zodiac-icon-large {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}

.reading-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
}

.score-item .progress {
    border-radius: 10px;
    background: rgba(0,0,0,0.1);
}

.score-item .progress-bar {
    border-radius: 10px;
}

.nav-pills .nav-link {
    border-radius: 25px;
    padding: 12px 24px;
    margin: 0 5px;
    font-weight: 500;
    color: #666;
    border: 2px solid #e9ecef;
    background: white;
    transition: all 0.3s ease;
}

.nav-pills .nav-link.active,
.nav-pills .nav-link:hover {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-color: transparent;
    color: white;
    transform: translateY(-2px);
}

.hover-lift:hover {
    transform: translateY(-3px);
    transition: all 0.3s ease;
}

/* Element colors */
.text-fire { color: #ff6b35 !important; }
.text-earth { color: #8b4513 !important; }
.text-air { color: #87ceeb !important; }
.text-water { color: #4682b4 !important; }

.bg-fire { background-color: #ff6b35 !important; }
.bg-earth { background-color: #8b4513 !important; }
.bg-air { background-color: #87ceeb !important; }
.bg-water { background-color: #4682b4 !important; }
</style>

<?php $content = ob_get_clean(); ?>

<?php include '../views/layouts/main.php'; ?>