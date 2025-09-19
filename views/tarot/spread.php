<?php $title = $page_title; ?>
<?php include '../views/layouts/main.php'; ?>

<?php ob_start(); ?>

<!-- Spread Header -->
<section class="spread-header py-4 bg-gradient-cosmic text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center">
                    <div class="spread-icon me-3">
                        <i class="fas fa-<?= $spread['icon'] ?> fa-3x text-white"></i>
                    </div>
                    <div>
                        <h1 class="h2 fw-bold mb-1"><?= htmlspecialchars($spread['name']) ?></h1>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-cards-blank me-2"></i><?= $spread['card_count'] ?> kart • 
                            <i class="fas fa-clock me-2"></i><?= $spread['estimated_time'] ?> dakika
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-end">
                <a href="/tarot" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-2"></i>Geri Dön
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Spread Description -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h3 class="h4 fw-bold mb-3">Bu Yayılım Hakkında</h3>
                <p class="lead text-muted"><?= htmlspecialchars($spread['description']) ?></p>
                <div class="spread-topics mt-3">
                    <h6 class="fw-bold mb-2">İdeal Konular:</h6>
                    <?php 
                    $topics = explode(',', $spread['suitable_topics']);
                    foreach ($topics as $topic): ?>
                    <span class="badge bg-primary me-2 mb-2"><?= trim(htmlspecialchars($topic)) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Reading Interface -->
<section class="py-5">
    <div class="container">
        <div class="reading-interface">
            
            <!-- Step 1: Question -->
            <div class="reading-step active" id="step1">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="card border-0 shadow-lg">
                            <div class="card-header bg-primary text-white text-center py-4">
                                <h3 class="h4 fw-bold mb-0">
                                    <i class="fas fa-question-circle me-2"></i>
                                    Sorunuzu Sorun
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <div class="step-content">
                                    <p class="text-muted mb-4 text-center">
                                        Kartların size rehberlik edebilmesi için net ve açık bir soru sorun. 
                                        Kişisel bilgilerinizi paylaşmayın.
                                    </p>
                                    
                                    <div class="mb-4">
                                        <label for="question" class="form-label fw-bold">Sorunuz:</label>
                                        <textarea class="form-control form-control-lg" 
                                                  id="question" 
                                                  rows="4" 
                                                  maxlength="500"
                                                  placeholder="Örnek: Kariyerimde hangi yönde ilerlememem gerekiyor?"
                                                  required></textarea>
                                        <div class="form-text">
                                            <span id="charCount">0</span>/500 karakter
                                        </div>
                                    </div>
                                    
                                    <div class="question-examples mb-4">
                                        <h6 class="fw-bold mb-3">Örnek Sorular:</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="example-question" onclick="setExampleQuestion(this)">
                                                    <i class="fas fa-heart text-danger me-2"></i>
                                                    <span>İlişkimde hangi konulara odaklanmalıyım?</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="example-question" onclick="setExampleQuestion(this)">
                                                    <i class="fas fa-briefcase text-primary me-2"></i>
                                                    <span>Kariyerimde beni bekleyen fırsatlar neler?</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="example-question" onclick="setExampleQuestion(this)">
                                                    <i class="fas fa-dollar-sign text-success me-2"></i>
                                                    <span>Mali durumumda dikkat etmem gerekenler?</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="example-question" onclick="setExampleQuestion(this)">
                                                    <i class="fas fa-user text-info me-2"></i>
                                                    <span>Kişisel gelişimim için neler yapmalıyım?</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="text-center">
                                        <button class="btn btn-primary btn-lg" onclick="nextStep(2)" id="questionNext" disabled>
                                            <i class="fas fa-arrow-right me-2"></i>Kartları Seç
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Step 2: Card Selection -->
            <div class="reading-step" id="step2">
                <div class="row">
                    <div class="col-lg-10 mx-auto">
                        <div class="card border-0 shadow-lg">
                            <div class="card-header bg-info text-white text-center py-4">
                                <h3 class="h4 fw-bold mb-2">
                                    <i class="fas fa-hand-pointer me-2"></i>
                                    Kartlarınızı Seçin
                                </h3>
                                <p class="mb-0 opacity-75">
                                    <?= $spread['card_count'] ?> kart seçmeniz gerekiyor • 
                                    <span id="selectedCount">0</span> / <?= $spread['card_count'] ?> seçildi
                                </p>
                            </div>
                            <div class="card-body p-4">
                                <div class="card-selection-area">
                                    <div class="cards-grid" id="cardsGrid">
                                        <!-- Cards will be loaded here -->
                                    </div>
                                    
                                    <div class="selection-progress mt-4">
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-info" id="selectionProgress" style="width: 0%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="selected-cards mt-4" id="selectedCards" style="display: none;">
                                        <h6 class="fw-bold mb-3">Seçilen Kartlar:</h6>
                                        <div class="row g-3" id="selectedCardsDisplay"></div>
                                    </div>
                                    
                                    <div class="text-center mt-4">
                                        <button class="btn btn-outline-secondary me-3" onclick="previousStep(1)">
                                            <i class="fas fa-arrow-left me-2"></i>Geri
                                        </button>
                                        <button class="btn btn-primary btn-lg" onclick="generateReading()" id="generateBtn" disabled>
                                            <i class="fas fa-magic me-2"></i>Falı Oluştur
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Step 3: Loading -->
            <div class="reading-step" id="step3">
                <div class="row">
                    <div class="col-lg-6 mx-auto">
                        <div class="card border-0 shadow-lg">
                            <div class="card-body text-center p-5">
                                <div class="loading-animation mb-4">
                                    <div class="crystal-ball">
                                        <div class="crystal-shine"></div>
                                    </div>
                                </div>
                                <h3 class="h4 fw-bold mb-3">Falınız Hazırlanıyor</h3>
                                <p class="text-muted mb-4">AI kartlarınızı analiz ediyor ve size özel yorumunuzu oluşturuyor...</p>
                                <div class="progress mb-3">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                         id="loadingProgress" style="width: 0%"></div>
                                </div>
                                <div class="loading-steps">
                                    <div class="loading-step" id="loadingStep1">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Kartlar seçildi
                                    </div>
                                    <div class="loading-step" id="loadingStep2">
                                        <i class="fas fa-spinner fa-spin me-2"></i>
                                        AI analiz ediyor
                                    </div>
                                    <div class="loading-step" id="loadingStep3">
                                        <i class="fas fa-clock me-2 text-muted"></i>
                                        Yorum oluşturuluyor
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

<style>
/* Reading Steps */
.reading-step {
    display: none;
}

.reading-step.active {
    display: block;
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Example Questions */
.example-question {
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.example-question:hover {
    background: #e9ecef;
    border-color: var(--primary-color);
    transform: translateY(-2px);
}

/* Cards Grid */
.cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    gap: 1rem;
    max-height: 400px;
    overflow-y: auto;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 15px;
}

.tarot-card-mini {
    aspect-ratio: 2/3;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    border: 3px solid #ddd;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.tarot-card-mini:hover {
    transform: translateY(-5px) scale(1.05);
    border-color: var(--primary-color);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.tarot-card-mini.selected {
    border-color: #28a745;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    transform: scale(0.95);
}

.tarot-card-mini.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    filter: grayscale(100%);
}

.card-back {
    width: 100%;
    height: 100%;
    background: 
        radial-gradient(circle at 30% 20%, rgba(255,215,0,0.3) 0%, transparent 50%),
        radial-gradient(circle at 70% 80%, rgba(255,255,255,0.2) 0%, transparent 50%),
        linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

/* Selected Cards Display */
.selected-card-preview {
    background: white;
    border-radius: 10px;
    padding: 1rem;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.selected-card-mini {
    width: 60px;
    height: 90px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-radius: 8px;
    margin: 0 auto 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
}

/* Loading Animation */
.loading-animation {
    perspective: 1000px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.crystal-ball {
    width: 100px;
    height: 100px;
    background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.8), rgba(138,43,226,0.3));
    border-radius: 50%;
    position: relative;
    animation: float 3s ease-in-out infinite;
    box-shadow: 
        0 0 30px rgba(138,43,226,0.5),
        inset 0 0 30px rgba(255,255,255,0.3);
}

.crystal-shine {
    position: absolute;
    top: 20%;
    left: 30%;
    width: 20px;
    height: 20px;
    background: rgba(255,255,255,0.8);
    border-radius: 50%;
    animation: shine 2s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotateY(0deg); }
    50% { transform: translateY(-10px) rotateY(180deg); }
}

@keyframes shine {
    0%, 100% { opacity: 0.7; transform: scale(1); }
    50% { opacity: 1; transform: scale(1.2); }
}

.loading-step {
    padding: 0.5rem 0;
    transition: all 0.3s ease;
}

.loading-step.completed {
    color: #28a745;
}

.loading-step.active {
    color: var(--primary-color);
    font-weight: bold;
}

/* Character Counter */
#charCount {
    font-weight: bold;
}

.char-warning {
    color: #ffc107 !important;
}

.char-danger {
    color: #dc3545 !important;
}

/* Responsive */
@media (max-width: 768px) {
    .cards-grid {
        grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
        gap: 0.5rem;
        padding: 0.5rem;
    }
    
    .card-back {
        font-size: 1rem;
    }
    
    .crystal-ball {
        width: 80px;
        height: 80px;
    }
    
    .example-question {
        padding: 0.75rem;
        font-size: 0.9rem;
    }
}
</style>

<script>
let selectedCards = [];
const maxCards = <?= $spread['card_count'] ?>;
let allCards = [];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadCards();
    setupQuestionInput();
});

// Question input setup
function setupQuestionInput() {
    const questionInput = document.getElementById('question');
    const charCount = document.getElementById('charCount');
    const nextBtn = document.getElementById('questionNext');
    
    questionInput.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length;
        
        // Update character counter color
        charCount.className = '';
        if (length > 400) {
            charCount.classList.add('char-danger');
        } else if (length > 300) {
            charCount.classList.add('char-warning');
        }
        
        // Enable/disable next button
        nextBtn.disabled = length < 10;
    });
}

// Set example question
function setExampleQuestion(element) {
    const questionText = element.querySelector('span').textContent;
    document.getElementById('question').value = questionText;
    document.getElementById('question').dispatchEvent(new Event('input'));
}

// Load tarot cards
async function loadCards() {
    try {
        const response = await fetch('/tarot/api/cards');
        const data = await response.json();
        
        if (data.cards) {
            allCards = data.cards;
            renderCards();
        }
    } catch (error) {
        console.error('Error loading cards:', error);
        document.getElementById('cardsGrid').innerHTML = `
            <div class="col-12 text-center p-4">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Kartlar yüklenirken hata oluştu. Lütfen sayfayı yenileyin.
                </div>
            </div>
        `;
    }
}

// Render cards grid
function renderCards() {
    const grid = document.getElementById('cardsGrid');
    
    // Shuffle cards for random display
    const shuffledCards = [...allCards].sort(() => Math.random() - 0.5);
    
    grid.innerHTML = shuffledCards.map(card => `
        <div class="tarot-card-mini" data-card-id="${card.id}" onclick="selectCard(${card.id})">
            <div class="card-back">
                <i class="fas fa-star"></i>
            </div>
        </div>
    `).join('');
}

// Select card
function selectCard(cardId) {
    const cardElement = document.querySelector(`[data-card-id="${cardId}"]`);
    
    if (cardElement.classList.contains('selected')) {
        // Deselect card
        selectedCards = selectedCards.filter(id => id !== cardId);
        cardElement.classList.remove('selected');
    } else if (selectedCards.length < maxCards) {
        // Select card
        selectedCards.push(cardId);
        cardElement.classList.add('selected');
    }
    
    updateSelectionDisplay();
}

// Update selection display
function updateSelectionDisplay() {
    const count = selectedCards.length;
    const progress = (count / maxCards) * 100;
    
    // Update counter
    document.getElementById('selectedCount').textContent = count;
    
    // Update progress bar
    document.getElementById('selectionProgress').style.width = progress + '%';
    
    // Update generate button
    document.getElementById('generateBtn').disabled = count !== maxCards;
    
    // Show/hide selected cards display
    const selectedCardsEl = document.getElementById('selectedCards');
    if (count > 0) {
        selectedCardsEl.style.display = 'block';
        renderSelectedCards();
    } else {
        selectedCardsEl.style.display = 'none';
    }
    
    // Disable unselected cards if max reached
    document.querySelectorAll('.tarot-card-mini').forEach(card => {
        if (count >= maxCards && !card.classList.contains('selected')) {
            card.classList.add('disabled');
        } else {
            card.classList.remove('disabled');
        }
    });
}

// Render selected cards
function renderSelectedCards() {
    const container = document.getElementById('selectedCardsDisplay');
    
    container.innerHTML = selectedCards.map((cardId, index) => {
        const card = allCards.find(c => c.id == cardId);
        return `
            <div class="col-auto">
                <div class="selected-card-preview">
                    <div class="selected-card-mini">
                        ${index + 1}
                    </div>
                    <small class="text-muted">${card ? card.name : 'Kart'}</small>
                </div>
            </div>
        `;
    }).join('');
}

// Navigation functions
function nextStep(step) {
    document.querySelectorAll('.reading-step').forEach(el => el.classList.remove('active'));
    document.getElementById(`step${step}`).classList.add('active');
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function previousStep(step) {
    nextStep(step);
}

// Generate reading
async function generateReading() {
    if (selectedCards.length !== maxCards) {
        alert('Lütfen tüm kartları seçin.');
        return;
    }
    
    const question = document.getElementById('question').value.trim();
    if (!question) {
        alert('Lütfen bir soru yazın.');
        return;
    }
    
    // Show loading step
    nextStep(3);
    
    // Animate progress
    let progress = 0;
    const progressInterval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress > 90) progress = 90;
        document.getElementById('loadingProgress').style.width = progress + '%';
    }, 200);
    
    // Update loading steps
    setTimeout(() => {
        document.getElementById('loadingStep1').classList.add('completed');
        document.getElementById('loadingStep2').classList.add('active');
    }, 1000);
    
    setTimeout(() => {
        document.getElementById('loadingStep2').innerHTML = '<i class="fas fa-check-circle text-success me-2"></i>AI analiz tamamlandı';
        document.getElementById('loadingStep2').classList.remove('active');
        document.getElementById('loadingStep2').classList.add('completed');
        document.getElementById('loadingStep3').classList.add('active');
    }, 3000);
    
    try {
        const response = await fetch('/tarot/reading', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': '<?= $csrf_token ?>'
            },
            body: JSON.stringify({
                spread_type: '<?= $spread['type'] ?>',
                selected_cards: selectedCards,
                question: question
            })
        });
        
        const data = await response.json();
        
        clearInterval(progressInterval);
        document.getElementById('loadingProgress').style.width = '100%';
        
        if (data.success) {
            setTimeout(() => {
                window.location.href = `/tarot/result/${data.reading_id}`;
            }, 1000);
        } else {
            throw new Error(data.error || 'Okuma oluşturulamadı');
        }
        
    } catch (error) {
        clearInterval(progressInterval);
        alert('Hata: ' + error.message);
        previousStep(2);
    }
}
</script>

<?php $content = ob_get_clean(); ?>

<?php include '../views/layouts/main.php'; ?>