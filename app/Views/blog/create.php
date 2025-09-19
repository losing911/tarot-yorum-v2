<?php $this->layout('layouts.main', ['page_title' => $page_title, 'meta_description' => $meta_description]) ?>

<?php $this->section('content') ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h2 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        Yeni Blog Yazısı Oluştur
                    </h2>
                </div>
                <div class="card-body">
                    <form id="blogForm" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        
                        <!-- Title and Slug -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <label for="title" class="form-label">Başlık *</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                                <div class="invalid-feedback">
                                    Başlık alanı zorunludur.
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="category" class="form-label">Kategori *</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Seçiniz</option>
                                    <option value="astroloji">Astroloji</option>
                                    <option value="tarot">Tarot</option>
                                    <option value="numeroloji">Numeroloji</option>
                                    <option value="ruhsal-gelisim">Ruhsal Gelişim</option>
                                    <option value="burc-yorumlari">Burç Yorumları</option>
                                    <option value="meditasyon">Meditasyon</option>
                                    <option value="kristal-terapi">Kristal Terapi</option>
                                    <option value="feng-shui">Feng Shui</option>
                                </select>
                                <div class="invalid-feedback">
                                    Kategori seçimi zorunludur.
                                </div>
                            </div>
                        </div>

                        <!-- Excerpt -->
                        <div class="mb-4">
                            <label for="excerpt" class="form-label">Özet *</label>
                            <textarea class="form-control" id="excerpt" name="excerpt" rows="3" 
                                      placeholder="Yazınızın kısa özeti (SEO için önemli)" required></textarea>
                            <div class="form-text">
                                Arama sonuçlarında gösterilecek kısa açıklama (150-160 karakter ideal)
                            </div>
                            <div class="invalid-feedback">
                                Özet alanı zorunludur.
                            </div>
                        </div>

                        <!-- AI Assistant Section -->
                        <div class="ai-assistant-section mb-4">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-robot me-2"></i>
                                        AI İçerik Asistanı
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="ai_topic" class="form-label">Konu</label>
                                            <input type="text" class="form-control" id="ai_topic" 
                                                   placeholder="Örn: Akrep burcu özellikleri">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="ai_type" class="form-label">Öneri Türü</label>
                                            <select class="form-select" id="ai_type">
                                                <option value="title">Başlık Önerileri</option>
                                                <option value="outline">İçerik Taslağı</option>
                                                <option value="intro">Giriş Paragrafı</option>
                                                <option value="content">Ana İçerik</option>
                                                <option value="conclusion">Sonuç Paragrafı</option>
                                                <option value="meta_description">Meta Açıklama</option>
                                                <option value="keywords">Anahtar Kelimeler</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-3 d-flex align-items-end">
                                            <button type="button" class="btn btn-info w-100" id="generateAI">
                                                <i class="fas fa-magic me-1"></i>
                                                Oluştur
                                            </button>
                                        </div>
                                    </div>
                                    <div id="aiResult" class="mt-3" style="display: none;">
                                        <div class="alert alert-light border">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <strong>AI Önerisi:</strong>
                                                    <div id="aiContent" class="mt-2"></div>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-primary" id="useAI">
                                                    Kullan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content Editor -->
                        <div class="mb-4">
                            <label for="content" class="form-label">İçerik *</label>
                            <div class="content-editor-toolbar mb-2">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="formatText('bold')">
                                        <i class="fas fa-bold"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="formatText('italic')">
                                        <i class="fas fa-italic"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="formatText('underline')">
                                        <i class="fas fa-underline"></i>
                                    </button>
                                </div>
                                <div class="btn-group btn-group-sm ms-2" role="group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="insertHeading('h2')">
                                        H2
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="insertHeading('h3')">
                                        H3
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="insertList('ul')">
                                        <i class="fas fa-list-ul"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="insertList('ol')">
                                        <i class="fas fa-list-ol"></i>
                                    </button>
                                </div>
                            </div>
                            <textarea class="form-control content-editor" id="content" name="content" rows="15" 
                                      placeholder="Yazınızın içeriğini buraya yazın..." required></textarea>
                            <div class="form-text">
                                HTML etiketleri kullanabilirsiniz. Görsel eklemek için img tagı kullanın.
                            </div>
                            <div class="invalid-feedback">
                                İçerik alanı zorunludur.
                            </div>
                        </div>

                        <!-- SEO Settings -->
                        <div class="seo-settings mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-search me-2"></i>
                                SEO Ayarları
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="meta_title" class="form-label">Meta Başlık</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                           placeholder="SEO için özel başlık">
                                    <div class="form-text">
                                        Boş bırakılırsa yazı başlığı kullanılır (55-60 karakter ideal)
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="meta_keywords" class="form-label">Anahtar Kelimeler</label>
                                    <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" 
                                           placeholder="anahtar, kelime, virgül, ile, ayrılmış">
                                    <div class="form-text">
                                        Virgülle ayrılmış anahtar kelimeler
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="meta_description" class="form-label">Meta Açıklama</label>
                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="2" 
                                              placeholder="SEO için özel açıklama"></textarea>
                                    <div class="form-text">
                                        Boş bırakılırsa özet kullanılır (150-160 karakter ideal)
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Settings -->
                        <div class="additional-settings mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-cog me-2"></i>
                                Ek Ayarlar
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="featured_image" class="form-label">Öne Çıkan Görsel URL</label>
                                    <input type="url" class="form-control" id="featured_image" name="featured_image" 
                                           placeholder="https://example.com/image.jpg">
                                    <div class="form-text">
                                        Yazının başında gösterilecek görsel
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="status" class="form-label">Durum</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="draft">Taslak</option>
                                        <option value="published">Yayında</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3 d-flex align-items-end">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured">
                                        <label class="form-check-label" for="is_featured">
                                            Öne çıkarılsın
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="/blog" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Geri Dön
                            </a>
                            <div>
                                <button type="button" class="btn btn-outline-primary me-2" id="previewBtn">
                                    <i class="fas fa-eye me-1"></i>
                                    Önizleme
                                </button>
                                <button type="submit" class="btn btn-primary" id="publishBtn">
                                    <i class="fas fa-save me-1"></i>
                                    Kaydet ve Yayınla
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Yazı Önizlemesi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="previewContent">
                    <!-- Preview content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('blogForm').dispatchEvent(new Event('submit'))">
                    Kaydet ve Yayınla
                </button>
            </div>
        </div>
    </div>
</div>

<?php $this->stop() ?>

<?php $this->section('styles') ?>
<style>
.content-editor {
    font-family: 'Georgia', serif;
    line-height: 1.6;
}

.content-editor-toolbar {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #dee2e6;
}

.ai-assistant-section .card {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.seo-settings,
.additional-settings {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

#aiResult {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.preview-container {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.preview-meta {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 5px;
    margin-bottom: 1rem;
    border-left: 4px solid #007bff;
}

@media (max-width: 768px) {
    .content-editor-toolbar .btn-group {
        margin-bottom: 0.5rem;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .d-flex.justify-content-between > div {
        text-align: center;
    }
}
</style>
<?php $this->stop() ?>

<?php $this->section('scripts') ?>
<script>
// Form handling
document.getElementById('blogForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = document.getElementById('publishBtn');
    const originalText = submitBtn.innerHTML;
    
    // Validation
    if (!form.checkValidity()) {
        e.stopPropagation();
        form.classList.add('was-validated');
        return;
    }
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Kaydediliyor...';
    
    try {
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);
        
        const response = await fetch('/blog/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('Blog yazısı başarıyla oluşturuldu!', 'success');
            setTimeout(() => {
                window.location.href = `/blog/${result.slug}`;
            }, 1000);
        } else {
            showToast(result.error || 'Bir hata oluştu', 'error');
        }
    } catch (error) {
        console.error('Form submission error:', error);
        showToast('Bir hata oluştu. Lütfen tekrar deneyin.', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// AI content generation
document.getElementById('generateAI')?.addEventListener('click', async function() {
    const topic = document.getElementById('ai_topic').value.trim();
    const type = document.getElementById('ai_type').value;
    
    if (!topic) {
        showToast('Lütfen bir konu girin', 'warning');
        return;
    }
    
    const btn = this;
    const originalText = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Oluşturuluyor...';
    
    try {
        const response = await fetch('/blog/ai-suggestion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                topic: topic,
                type: type,
                csrf_token: document.querySelector('input[name="csrf_token"]').value
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            document.getElementById('aiContent').innerHTML = result.suggestion.replace(/\n/g, '<br>');
            document.getElementById('aiResult').style.display = 'block';
        } else {
            showToast(result.error || 'AI önerisi oluşturulamadı', 'error');
        }
    } catch (error) {
        console.error('AI generation error:', error);
        showToast('Bir hata oluştu. Lütfen tekrar deneyin.', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
});

// Use AI suggestion
document.getElementById('useAI')?.addEventListener('click', function() {
    const aiContent = document.getElementById('aiContent').textContent;
    const type = document.getElementById('ai_type').value;
    
    switch(type) {
        case 'title':
            document.getElementById('title').value = aiContent;
            break;
        case 'intro':
        case 'content':
        case 'conclusion':
            const contentTextarea = document.getElementById('content');
            const currentContent = contentTextarea.value;
            contentTextarea.value = currentContent + (currentContent ? '\n\n' : '') + aiContent;
            break;
        case 'meta_description':
            document.getElementById('meta_description').value = aiContent;
            break;
        case 'keywords':
            document.getElementById('meta_keywords').value = aiContent;
            break;
        case 'outline':
            const contentArea = document.getElementById('content');
            contentArea.value = aiContent;
            break;
    }
    
    document.getElementById('aiResult').style.display = 'none';
    showToast('AI önerisi eklendi!', 'success');
});

// Content editor functions
function formatText(command) {
    const textarea = document.getElementById('content');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);
    
    if (!selectedText) {
        showToast('Lütfen formatlamak istediğiniz metni seçin', 'warning');
        return;
    }
    
    let formattedText = '';
    
    switch(command) {
        case 'bold':
            formattedText = `<strong>${selectedText}</strong>`;
            break;
        case 'italic':
            formattedText = `<em>${selectedText}</em>`;
            break;
        case 'underline':
            formattedText = `<u>${selectedText}</u>`;
            break;
    }
    
    textarea.value = textarea.value.substring(0, start) + formattedText + textarea.value.substring(end);
    textarea.focus();
}

function insertHeading(level) {
    const textarea = document.getElementById('content');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end) || 'Başlık Metni';
    
    const headingText = `<${level}>${selectedText}</${level}>`;
    
    textarea.value = textarea.value.substring(0, start) + headingText + textarea.value.substring(end);
    textarea.focus();
}

function insertList(type) {
    const textarea = document.getElementById('content');
    const start = textarea.selectionStart;
    
    const listText = type === 'ul' ? 
        '<ul>\n<li>Liste öğesi 1</li>\n<li>Liste öğesi 2</li>\n<li>Liste öğesi 3</li>\n</ul>' :
        '<ol>\n<li>Numaralı liste öğesi 1</li>\n<li>Numaralı liste öğesi 2</li>\n<li>Numaralı liste öğesi 3</li>\n</ol>';
    
    textarea.value = textarea.value.substring(0, start) + listText + textarea.value.substring(start);
    textarea.focus();
}

// Auto-fill meta fields
document.getElementById('title')?.addEventListener('input', function() {
    const title = this.value;
    const metaTitle = document.getElementById('meta_title');
    
    if (!metaTitle.value || metaTitle.value === metaTitle.defaultValue) {
        metaTitle.value = title;
    }
});

document.getElementById('excerpt')?.addEventListener('input', function() {
    const excerpt = this.value;
    const metaDescription = document.getElementById('meta_description');
    
    if (!metaDescription.value || metaDescription.value === metaDescription.defaultValue) {
        metaDescription.value = excerpt;
    }
});

// Preview functionality
document.getElementById('previewBtn')?.addEventListener('click', function() {
    const formData = new FormData(document.getElementById('blogForm'));
    const data = Object.fromEntries(formData);
    
    // Validate required fields
    if (!data.title || !data.content || !data.excerpt) {
        showToast('Önizleme için başlık, içerik ve özet alanları dolu olmalı', 'warning');
        return;
    }
    
    // Generate preview HTML
    const previewHTML = `
        <div class="preview-container">
            <div class="preview-meta">
                <strong>Kategori:</strong> ${data.category || 'Belirtilmemiş'} |
                <strong>Durum:</strong> ${data.status === 'published' ? 'Yayında' : 'Taslak'} |
                <strong>Öne Çıkan:</strong> ${data.is_featured ? 'Evet' : 'Hayır'}
            </div>
            
            ${data.featured_image ? `<img src="${data.featured_image}" class="img-fluid mb-3" alt="Öne çıkan görsel">` : ''}
            
            <h1 class="mb-3">${data.title}</h1>
            
            <div class="alert alert-light border-start border-primary border-4 mb-4">
                <strong>Özet:</strong> ${data.excerpt}
            </div>
            
            <div class="content-preview">
                ${data.content.replace(/\n/g, '<br>')}
            </div>
            
            ${data.meta_keywords ? `
            <div class="mt-4">
                <strong>Etiketler:</strong>
                ${data.meta_keywords.split(',').map(keyword => 
                    `<span class="badge bg-light text-dark me-1">${keyword.trim()}</span>`
                ).join('')}
            </div>
            ` : ''}
        </div>
    `;
    
    document.getElementById('previewContent').innerHTML = previewHTML;
    
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
});

// Character count for meta fields
function addCharacterCount(fieldId, maxLength) {
    const field = document.getElementById(fieldId);
    if (!field) return;
    
    const counter = document.createElement('small');
    counter.className = 'text-muted character-count';
    field.parentNode.appendChild(counter);
    
    function updateCount() {
        const length = field.value.length;
        const remaining = maxLength - length;
        counter.textContent = `${length}/${maxLength} karakter`;
        
        if (remaining < 0) {
            counter.className = 'text-danger character-count';
        } else if (remaining < 20) {
            counter.className = 'text-warning character-count';
        } else {
            counter.className = 'text-muted character-count';
        }
    }
    
    field.addEventListener('input', updateCount);
    updateCount();
}

// Initialize character counters
document.addEventListener('DOMContentLoaded', function() {
    addCharacterCount('meta_title', 60);
    addCharacterCount('meta_description', 160);
    addCharacterCount('excerpt', 200);
});

// Auto-save functionality
let autoSaveTimeout;
function autoSave() {
    clearTimeout(autoSaveTimeout);
    autoSaveTimeout = setTimeout(() => {
        const formData = new FormData(document.getElementById('blogForm'));
        const data = Object.fromEntries(formData);
        
        // Save to localStorage
        localStorage.setItem('blog_draft', JSON.stringify(data));
        
        // Show auto-save indicator
        const indicator = document.createElement('small');
        indicator.className = 'text-success';
        indicator.textContent = 'Otomatik kaydedildi ✓';
        indicator.style.position = 'fixed';
        indicator.style.top = '20px';
        indicator.style.right = '20px';
        indicator.style.zIndex = '9999';
        indicator.style.background = 'white';
        indicator.style.padding = '5px 10px';
        indicator.style.borderRadius = '5px';
        indicator.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
        
        document.body.appendChild(indicator);
        
        setTimeout(() => {
            indicator.remove();
        }, 2000);
    }, 3000);
}

// Add auto-save listeners
document.querySelectorAll('#blogForm input, #blogForm textarea, #blogForm select').forEach(field => {
    field.addEventListener('input', autoSave);
});

// Restore from auto-save on page load
document.addEventListener('DOMContentLoaded', function() {
    const saved = localStorage.getItem('blog_draft');
    if (saved && confirm('Kaydedilmiş taslak bulundu. Geri yüklemek ister misiniz?')) {
        const data = JSON.parse(saved);
        
        Object.keys(data).forEach(key => {
            const field = document.querySelector(`[name="${key}"]`);
            if (field) {
                if (field.type === 'checkbox') {
                    field.checked = data[key] === 'on';
                } else {
                    field.value = data[key];
                }
            }
        });
    }
});
</script>
<?php $this->stop() ?>