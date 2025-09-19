<?php $title = $page_title; ?>
<?php include '../views/layouts/main.php'; ?>

<?php ob_start(); ?>

<!-- Reading Header -->
<section class="reading-header py-4 bg-gradient-cosmic text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="h2 fw-bold mb-2">Tarot Falı Sonucunuz</h1>
                <p class="mb-0 opacity-75">
                    <i class="fas fa-calendar me-2"></i><?= date('d.m.Y H:i', strtotime($reading['created_at'])) ?> •
                    <i class="fas fa-cards-blank me-2"></i><?= htmlspecialchars($spread['name']) ?>
                </p>
            </div>
            <div class="col-lg-4 text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="/tarot" class="btn btn-outline-light">
                        <i class="fas fa-home me-2"></i>Ana Sayfa
                    </a>
                    <button class="btn btn-light" onclick="shareReading()">
                        <i class="fas fa-share-alt me-2"></i>Paylaş
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Question Section -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold mb-3">
                            <i class="fas fa-question-circle text-primary me-2"></i>
                            Sorunuz
                        </h5>
                        <p class="lead text-muted mb-0">"<?= htmlspecialchars($reading['question']) ?>"</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cards Layout -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h2 class="h3 fw-bold text-center mb-4">Seçilen Kartlar</h2>
                
                <!-- Spread Layout -->
                <div class="spread-layout <?= $spread['type'] ?>-layout mb-5">
                    <?php foreach ($cards as $index => $card): ?>
                    <div class="card-position position-<?= $index + 1 ?>" data-card-index="<?= $index ?>">
                        <div class="tarot-card-large" onclick="showCardDetail(<?= $index ?>)">
                            <div class="card-front">
                                <div class="card-header">
                                    <span class="card-number"><?= $index + 1 ?></span>
                                    <span class="card-name"><?= htmlspecialchars($card['name']) ?></span>
                                </div>
                                <div class="card-symbol">
                                    <i class="fas fa-<?= $card['suit_icon'] ?> fa-3x"></i>
                                </div>
                                <div class="card-suit"><?= htmlspecialchars($card['suit']) ?></div>
                                <div class="card-arcana">
                                    <span class="badge bg-<?= $card['arcana'] === 'Major' ? 'warning' : 'info' ?>">
                                        <?= htmlspecialchars($card['arcana']) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Position meaning for Celtic Cross -->
                        <?php if ($spread['type'] === 'celtic_cross'): ?>
                        <div class="position-meaning mt-2 text-center">
                            <small class="text-muted fw-bold">
                                <?= $this->getCelticCrossPosition($index + 1) ?>
                            </small>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- AI Interpretation -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-gradient-cosmic text-white text-center py-4">
                        <h2 class="h3 fw-bold mb-2">
                            <i class="fas fa-robot me-2"></i>
                            AI Tarot Yorumu
                        </h2>
                        <p class="mb-0 opacity-75">Kartlarınız analiz edildi ve size özel yorum hazırlandı</p>
                    </div>
                    <div class="card-body p-4">
                        <div class="interpretation-content">
                            <?= nl2br(htmlspecialchars($reading['interpretation'])) ?>
                        </div>
                        
                        <div class="interpretation-meta mt-4 pt-3 border-top">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fas fa-brain me-1"></i>
                                        AI Provider: <?= ucfirst($reading['ai_provider']) ?>
                                    </small>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        Oluşturulma: <?= date('d.m.Y H:i', strtotime($reading['created_at'])) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Individual Card Meanings -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h2 class="h3 fw-bold text-center mb-5">Kart Anlamları</h2>
                
                <div class="row g-4">
                    <?php foreach ($cards as $index => $card): ?>
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="card-mini me-3">
                                        <div class="mini-card bg-gradient-cosmic text-white">
                                            <span class="fw-bold"><?= $index + 1 ?></span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold mb-1"><?= htmlspecialchars($card['name']) ?></h5>
                                        <div class="card-badges mb-2">
                                            <span class="badge bg-<?= $card['arcana'] === 'Major' ? 'warning' : 'info' ?> me-1">
                                                <?= htmlspecialchars($card['arcana']) ?>
                                            </span>
                                            <span class="badge bg-secondary">
                                                <?= htmlspecialchars($card['suit']) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-meaning mb-3">
                                    <h6 class="fw-bold text-success mb-2">
                                        <i class="fas fa-arrow-up me-1"></i>Düz Anlam
                                    </h6>
                                    <p class="text-muted small mb-3"><?= htmlspecialchars($card['meaning_upright']) ?></p>
                                    
                                    <h6 class="fw-bold text-warning mb-2">
                                        <i class="fas fa-arrow-down me-1"></i>Ters Anlam
                                    </h6>
                                    <p class="text-muted small mb-3"><?= htmlspecialchars($card['meaning_reversed']) ?></p>
                                </div>
                                
                                <div class="keywords">
                                    <h6 class="fw-bold mb-2">Anahtar Kelimeler:</h6>
                                    <div class="keyword-tags">
                                        <?php 
                                        $keywords = explode(',', $card['keywords']);
                                        foreach (array_slice($keywords, 0, 3) as $keyword): ?>
                                        <span class="badge bg-light text-dark me-1"><?= trim(htmlspecialchars($keyword)) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Action Buttons -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h3 class="h4 fw-bold mb-4">Ne Yapmak İstersiniz?</h3>
                
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="d-grid">
                            <a href="/tarot" class="btn btn-primary btn-lg">
                                <i class="fas fa-redo me-2"></i>
                                Yeni Fal
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="d-grid">
                            <button class="btn btn-outline-primary btn-lg" onclick="printReading()">
                                <i class="fas fa-print me-2"></i>
                                Yazdır
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="d-grid">
                            <button class="btn btn-outline-secondary btn-lg" onclick="shareReading()">
                                <i class="fas fa-share-alt me-2"></i>
                                Paylaş
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="d-grid">
                            <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="/tarot/history" class="btn btn-outline-info btn-lg">
                                <i class="fas fa-history me-2"></i>
                                Geçmiş
                            </a>
                            <?php else: ?>
                            <a href="/register" class="btn btn-outline-info btn-lg">
                                <i class="fas fa-user-plus me-2"></i>
                                Kayıt Ol
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Card Detail Modal -->
<div class="modal fade" id="cardDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-cosmic text-white">
                <h5 class="modal-title" id="cardDetailTitle">
                    <i class="fas fa-cards-blank me-2"></i>Kart Detayı
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="cardDetailContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
/* Spread Layouts */
.spread-layout {
    display: grid;
    gap: 1rem;
    justify-items: center;
    padding: 2rem;
    min-height: 300px;
}

/* Three Card Spread */
.three-card-layout {
    grid-template-columns: repeat(3, 1fr);
    max-width: 600px;
    margin: 0 auto;
}

/* Celtic Cross Layout */
.celtic-cross-layout {
    grid-template-columns: repeat(4, 1fr);
    grid-template-rows: repeat(4, 1fr);
    max-width: 800px;
    margin: 0 auto;
}

.celtic-cross-layout .position-1 { grid-column: 2; grid-row: 2; }
.celtic-cross-layout .position-2 { grid-column: 2; grid-row: 2; transform: rotate(90deg); z-index: 2; }
.celtic-cross-layout .position-3 { grid-column: 2; grid-row: 1; }
.celtic-cross-layout .position-4 { grid-column: 2; grid-row: 3; }
.celtic-cross-layout .position-5 { grid-column: 1; grid-row: 2; }
.celtic-cross-layout .position-6 { grid-column: 3; grid-row: 2; }
.celtic-cross-layout .position-7 { grid-column: 4; grid-row: 4; }
.celtic-cross-layout .position-8 { grid-column: 4; grid-row: 3; }
.celtic-cross-layout .position-9 { grid-column: 4; grid-row: 2; }
.celtic-cross-layout .position-10 { grid-column: 4; grid-row: 1; }

/* Yes/No Layout */
.yes-no-layout {
    grid-template-columns: 1fr;
    max-width: 200px;
    margin: 0 auto;
}

/* Tarot Card Styles */
.tarot-card-large {
    width: 120px;
    height: 180px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.tarot-card-large:hover {
    transform: translateY(-10px) scale(1.05);
}

.card-front {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    border: 3px solid #ffd700;
    color: white;
    display: flex;
    flex-direction: column;
    padding: 0.75rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    position: relative;
    overflow: hidden;
}

.card-front::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 20%, rgba(255,255,255,0.2) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(255,215,0,0.1) 0%, transparent 50%);
    pointer-events: none;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.7rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
    position: relative;
    z-index: 1;
}

.card-number {
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
}

.card-name {
    font-size: 0.6rem;
    text-align: center;
    flex: 1;
    margin: 0 0.25rem;
}

.card-symbol {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 1;
}

.card-suit {
    text-align: center;
    font-size: 0.7rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
    position: relative;
    z-index: 1;
}

.card-arcana {
    position: relative;
    z-index: 1;
}

/* Card Mini */
.card-mini {
    width: 50px;
    height: 50px;
}

.mini-card {
    width: 100%;
    height: 100%;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

/* Interpretation */
.interpretation-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
    background: linear-gradient(135deg, #f8f9fa, #ffffff);
    padding: 2rem;
    border-radius: 15px;
    border-left: 4px solid var(--primary-color);
}

/* Card Meanings */
.card-meaning h6 {
    font-size: 0.9rem;
}

.keyword-tags .badge {
    font-size: 0.75rem;
}

/* Position meanings for Celtic Cross */
.position-meaning {
    font-size: 0.8rem;
    max-width: 120px;
}

/* Responsive */
@media (max-width: 768px) {
    .spread-layout {
        padding: 1rem;
        gap: 0.5rem;
    }
    
    .tarot-card-large {
        width: 80px;
        height: 120px;
    }
    
    .card-front {
        padding: 0.5rem;
    }
    
    .card-symbol i {
        font-size: 1.5rem !important;
    }
    
    .card-name {
        font-size: 0.5rem;
    }
    
    .three-card-layout {
        grid-template-columns: repeat(3, 80px);
    }
    
    .celtic-cross-layout {
        grid-template-columns: repeat(4, 80px);
        grid-template-rows: repeat(4, 120px);
    }
    
    .interpretation-content {
        padding: 1.5rem;
        font-size: 1rem;
    }
}

/* Print Styles */
@media print {
    .btn, .modal, .reading-header .btn, nav, footer {
        display: none !important;
    }
    
    .container {
        max-width: 100% !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
    
    .bg-gradient-cosmic {
        background: #333 !important;
        color: white !important;
    }
}
</style>

<script>
const cards = <?= json_encode($cards) ?>;

// Show card detail modal
function showCardDetail(index) {
    const card = cards[index];
    const modal = new bootstrap.Modal(document.getElementById('cardDetailModal'));
    
    document.getElementById('cardDetailTitle').innerHTML = `
        <i class="fas fa-cards-blank me-2"></i>
        ${card.name} - Pozisyon ${index + 1}
    `;
    
    document.getElementById('cardDetailContent').innerHTML = `
        <div class="row">
            <div class="col-md-4 text-center">
                <div class="card-detail-visual">
                    <div class="tarot-card-large mx-auto mb-3">
                        <div class="card-front">
                            <div class="card-header">
                                <span class="card-number">${index + 1}</span>
                                <span class="card-name">${card.name}</span>
                            </div>
                            <div class="card-symbol">
                                <i class="fas fa-${card.suit_icon} fa-3x"></i>
                            </div>
                            <div class="card-suit">${card.suit}</div>
                            <div class="card-arcana">
                                <span class="badge bg-${card.arcana === 'Major' ? 'warning' : 'info'}">
                                    ${card.arcana}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <h5 class="fw-bold mb-3">${card.name}</h5>
                
                <div class="mb-4">
                    <h6 class="fw-bold text-success mb-2">
                        <i class="fas fa-arrow-up me-1"></i>Düz Pozisyon
                    </h6>
                    <p class="text-muted">${card.meaning_upright}</p>
                </div>
                
                <div class="mb-4">
                    <h6 class="fw-bold text-warning mb-2">
                        <i class="fas fa-arrow-down me-1"></i>Ters Pozisyon
                    </h6>
                    <p class="text-muted">${card.meaning_reversed}</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="fw-bold mb-2">Anahtar Kelimeler:</h6>
                    <p class="text-muted">${card.keywords}</p>
                </div>
                
                ${card.element ? `
                <div class="mb-3">
                    <h6 class="fw-bold mb-2">Element:</h6>
                    <span class="badge bg-info">${card.element}</span>
                </div>
                ` : ''}
            </div>
        </div>
    `;
    
    modal.show();
}

// Share reading
function shareReading() {
    if (navigator.share) {
        navigator.share({
            title: 'Tarot Falı Sonucum',
            text: 'Tarot falımı kontrol et!',
            url: window.location.href
        });
    } else {
        // Fallback to copy link
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Link kopyalandı!');
        });
    }
}

// Print reading
function printReading() {
    window.print();
}

// Add position meanings for Celtic Cross
function getCelticCrossPosition(position) {
    const positions = {
        1: 'Mevcut Durum',
        2: 'Karşılaştığın Zorluk',
        3: 'Uzak Geçmiş',
        4: 'Yakın Gelecek',
        5: 'Olası Sonuç',
        6: 'Yakın Geçmiş',
        7: 'Yaklaşımın',
        8: 'Çevrenden Etkiler',
        9: 'Umutların ve Korkuların',
        10: 'Son Sonuç'
    };
    
    return positions[position] || `Pozisyon ${position}`;
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Add position meanings for Celtic Cross
    <?php if ($spread['type'] === 'celtic_cross'): ?>
    document.querySelectorAll('.position-meaning').forEach((element, index) => {
        element.innerHTML = `<small class="text-muted fw-bold">${getCelticCrossPosition(index + 1)}</small>`;
    });
    <?php endif; ?>
});
</script>

<?php $content = ob_get_clean(); ?>

<?php include '../views/layouts/main.php'; ?>