<?php $title = $page_title; ?>

<?php ob_start(); ?>

<!-- Header -->
<section class="history-header py-4 bg-gradient-cosmic text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="h2 fw-bold mb-2">Tarot Geçmişim</h1>
                <p class="mb-0 opacity-75">
                    <i class="fas fa-chart-line me-2"></i>
                    Toplam <?= $total_readings ?> okuma • 
                    <i class="fas fa-calendar me-2"></i>
                    Tüm tarot deneyiminiz
                </p>
            </div>
            <div class="col-lg-4 text-end">
                <a href="/tarot" class="btn btn-outline-light">
                    <i class="fas fa-plus me-2"></i>Yeni Fal
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Statistics -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card text-center">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-cards-blank fa-3x text-primary"></i>
                    </div>
                    <h3 class="stat-number fw-bold"><?= $total_readings ?></h3>
                    <p class="stat-label text-muted mb-0">Toplam Okuma</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="stat-card text-center">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-calendar-week fa-3x text-success"></i>
                    </div>
                    <h3 class="stat-number fw-bold">
                        <?php 
                        $thisWeek = array_filter($readings, function($r) {
                            return strtotime($r['created_at']) > strtotime('-7 days');
                        });
                        echo count($thisWeek);
                        ?>
                    </h3>
                    <p class="stat-label text-muted mb-0">Bu Hafta</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="stat-card text-center">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-star fa-3x text-warning"></i>
                    </div>
                    <h3 class="stat-number fw-bold">
                        <?php
                        $spreadTypes = array_count_values(array_column($readings, 'spread_type'));
                        $mostUsed = !empty($spreadTypes) ? array_keys($spreadTypes, max($spreadTypes))[0] : 'N/A';
                        echo ucfirst(str_replace('_', ' ', $mostUsed));
                        ?>
                    </h3>
                    <p class="stat-label text-muted mb-0">En Çok Kullanılan</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="stat-card text-center">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-clock fa-3x text-info"></i>
                    </div>
                    <h3 class="stat-number fw-bold">
                        <?php
                        $firstReading = !empty($readings) ? end($readings) : null;
                        $daysSince = $firstReading ? ceil((time() - strtotime($firstReading['created_at'])) / 86400) : 0;
                        echo $daysSince;
                        ?>
                    </h3>
                    <p class="stat-label text-muted mb-0">Gün Önce Başladı</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filters -->
<section class="py-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Yayılım Türü:</label>
                                <select class="form-select" id="spreadFilter" onchange="filterReadings()">
                                    <option value="">Tümü</option>
                                    <option value="three_card">3 Kart</option>
                                    <option value="celtic_cross">Celtic Cross</option>
                                    <option value="yes_no">Yes/No</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Tarih Aralığı:</label>
                                <select class="form-select" id="dateFilter" onchange="filterReadings()">
                                    <option value="">Tümü</option>
                                    <option value="today">Bugün</option>
                                    <option value="week">Bu Hafta</option>
                                    <option value="month">Bu Ay</option>
                                    <option value="year">Bu Yıl</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Sıralama:</label>
                                <select class="form-select" id="sortFilter" onchange="filterReadings()">
                                    <option value="newest">En Yeni</option>
                                    <option value="oldest">En Eski</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Readings List -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                
                <?php if (empty($readings)): ?>
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div class="empty-state-icon mb-4">
                        <i class="fas fa-cards-blank fa-5x text-muted"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-3">Henüz tarot okumanız yok</h3>
                    <p class="text-muted mb-4">İlk falınızı görmek için aşağıdaki butona tıklayın</p>
                    <a href="/tarot" class="btn btn-primary btn-lg">
                        <i class="fas fa-magic me-2"></i>İlk Falını Bak
                    </a>
                </div>
                
                <?php else: ?>
                
                <!-- Readings Grid -->
                <div class="readings-grid" id="readingsGrid">
                    <?php foreach ($readings as $reading): ?>
                    <div class="reading-item" 
                         data-spread="<?= $reading['spread_type'] ?>" 
                         data-date="<?= date('Y-m-d', strtotime($reading['created_at'])) ?>">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-header bg-gradient-cosmic text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0 fw-bold">
                                        <i class="fas fa-<?= $this->getSpreadIcon($reading['spread_type']) ?> me-2"></i>
                                        <?= htmlspecialchars($reading['spread_name']) ?>
                                    </h5>
                                    <span class="badge bg-light text-dark">
                                        <?= $reading['card_count'] ?> kart
                                    </span>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="reading-question mb-3">
                                    <h6 class="fw-bold text-primary mb-2">Soru:</h6>
                                    <p class="text-muted mb-0">
                                        "<?= mb_substr(htmlspecialchars($reading['question']), 0, 120) ?><?= strlen($reading['question']) > 120 ? '...' : '' ?>"
                                    </p>
                                </div>
                                
                                <div class="reading-preview mb-3">
                                    <h6 class="fw-bold text-success mb-2">Yorum Özeti:</h6>
                                    <p class="text-muted small mb-0">
                                        <?= mb_substr(strip_tags($reading['interpretation']), 0, 150) ?>...
                                    </p>
                                </div>
                                
                                <div class="reading-meta">
                                    <div class="row align-items-center">
                                        <div class="col-6">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                <?= date('d.m.Y', strtotime($reading['created_at'])) ?>
                                            </small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                <?= date('H:i', strtotime($reading['created_at'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-robot me-1"></i>
                                        AI: <?= ucfirst($reading['ai_provider']) ?>
                                    </small>
                                    <a href="/tarot/result/<?= $reading['id'] ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Görüntüle
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <nav class="mt-5" aria-label="Sayfa navigasyonu">
                    <ul class="pagination pagination-lg justify-content-center">
                        
                        <!-- Previous -->
                        <?php if ($current_page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="/tarot/history?page=<?= $current_page - 1 ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <!-- Page Numbers -->
                        <?php 
                        $start = max(1, $current_page - 2);
                        $end = min($total_pages, $current_page + 2);
                        
                        for ($i = $start; $i <= $end; $i++): ?>
                        <li class="page-item <?= $i === $current_page ? 'active' : '' ?>">
                            <a class="page-link" href="/tarot/history?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>
                        
                        <!-- Next -->
                        <?php if ($current_page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="/tarot/history?page=<?= $current_page + 1 ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                    </ul>
                </nav>
                <?php endif; ?>
                
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
/* Statistics Cards */
.stat-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    border: 1px solid rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.stat-icon {
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-number {
    font-size: 2.5rem;
    color: var(--primary-color);
}

.stat-label {
    font-size: 1rem;
    font-weight: 500;
}

/* Readings Grid */
.readings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2rem;
}

.reading-item {
    transition: all 0.3s ease;
}

.reading-item.hidden {
    display: none;
}

.hover-lift:hover {
    transform: translateY(-5px);
}

.reading-question {
    background: rgba(0,123,255,0.1);
    border-radius: 10px;
    padding: 1rem;
}

.reading-preview {
    background: rgba(40,167,69,0.1);
    border-radius: 10px;
    padding: 1rem;
}

/* Empty State */
.empty-state-icon {
    opacity: 0.5;
}

/* Cards */
.card {
    border-radius: 15px !important;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

/* Pagination */
.pagination .page-link {
    border-radius: 50px;
    margin: 0 5px;
    border: 2px solid #e9ecef;
    color: var(--primary-color);
    font-weight: 500;
}

.pagination .page-item.active .page-link {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

/* Responsive */
@media (max-width: 768px) {
    .readings-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .stat-card {
        padding: 1.5rem;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .reading-question, .reading-preview {
        padding: 0.75rem;
    }
}
</style>

<script>
// Filter functions
function filterReadings() {
    const spreadFilter = document.getElementById('spreadFilter').value;
    const dateFilter = document.getElementById('dateFilter').value;
    const sortFilter = document.getElementById('sortFilter').value;
    
    const readings = Array.from(document.querySelectorAll('.reading-item'));
    const now = new Date();
    
    // Filter by spread type
    readings.forEach(reading => {
        const spread = reading.getAttribute('data-spread');
        const date = new Date(reading.getAttribute('data-date'));
        
        let showSpread = !spreadFilter || spread === spreadFilter;
        let showDate = true;
        
        // Filter by date
        if (dateFilter) {
            switch (dateFilter) {
                case 'today':
                    showDate = date.toDateString() === now.toDateString();
                    break;
                case 'week':
                    const weekAgo = new Date(now - 7 * 24 * 60 * 60 * 1000);
                    showDate = date >= weekAgo;
                    break;
                case 'month':
                    showDate = date.getMonth() === now.getMonth() && date.getFullYear() === now.getFullYear();
                    break;
                case 'year':
                    showDate = date.getFullYear() === now.getFullYear();
                    break;
            }
        }
        
        // Show/hide reading
        if (showSpread && showDate) {
            reading.classList.remove('hidden');
        } else {
            reading.classList.add('hidden');
        }
    });
    
    // Sort readings
    const visibleReadings = readings.filter(r => !r.classList.contains('hidden'));
    if (sortFilter === 'oldest') {
        visibleReadings.reverse();
    }
    
    // Re-append in new order
    const grid = document.getElementById('readingsGrid');
    visibleReadings.forEach(reading => {
        grid.appendChild(reading);
    });
    
    // Update empty state
    updateEmptyState();
}

function updateEmptyState() {
    const visibleReadings = document.querySelectorAll('.reading-item:not(.hidden)');
    const grid = document.getElementById('readingsGrid');
    
    if (visibleReadings.length === 0) {
        if (!document.getElementById('noResults')) {
            const emptyState = document.createElement('div');
            emptyState.id = 'noResults';
            emptyState.className = 'text-center py-5 col-12';
            emptyState.innerHTML = `
                <div class="empty-state-icon mb-4">
                    <i class="fas fa-search fa-4x text-muted"></i>
                </div>
                <h3 class="h4 fw-bold mb-3">Arama sonucu bulunamadı</h3>
                <p class="text-muted mb-4">Filtrelerinizi değiştirmeyi deneyin</p>
                <button class="btn btn-outline-primary" onclick="clearFilters()">
                    <i class="fas fa-undo me-2"></i>Filtreleri Temizle
                </button>
            `;
            grid.appendChild(emptyState);
        }
    } else {
        const noResults = document.getElementById('noResults');
        if (noResults) {
            noResults.remove();
        }
    }
}

function clearFilters() {
    document.getElementById('spreadFilter').value = '';
    document.getElementById('dateFilter').value = '';
    document.getElementById('sortFilter').value = 'newest';
    filterReadings();
}

// Get spread icon
function getSpreadIcon(spreadType) {
    const icons = {
        'three_card': 'layer-group',
        'celtic_cross': 'plus',
        'yes_no': 'question-circle'
    };
    return icons[spreadType] || 'cards-blank';
}

// Smooth scrolling for pagination
document.querySelectorAll('.pagination a').forEach(link => {
    link.addEventListener('click', function(e) {
        if (this.href.includes('#')) return;
        
        setTimeout(() => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }, 100);
    });
});
</script>

<?php $content = ob_get_clean(); ?>