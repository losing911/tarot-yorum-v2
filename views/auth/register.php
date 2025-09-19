<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card card-custom shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="text-primary fw-bold">
                            <i class="bi bi-person-plus me-2"></i>Kayıt Ol
                        </h2>
                        <p class="text-muted">Ücretsiz hesabınızı oluşturun ve ruhani yolculuğunuza başlayın</p>
                    </div>

                    <?php if (isset($_SESSION['errors'])): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($_SESSION['errors'] as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php unset($_SESSION['errors']); ?>
                    <?php endif; ?>

                    <form method="POST" action="/register">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">Ad <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control" 
                                       id="first_name" 
                                       name="first_name" 
                                       value="<?= htmlspecialchars($_SESSION['old']['first_name'] ?? '') ?>"
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Soyad <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control" 
                                       id="last_name" 
                                       name="last_name" 
                                       value="<?= htmlspecialchars($_SESSION['old']['last_name'] ?? '') ?>"
                                       required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Kullanıcı Adı <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="username" 
                                   name="username" 
                                   value="<?= htmlspecialchars($_SESSION['old']['username'] ?? '') ?>"
                                   required>
                            <div class="form-text">3-50 karakter arası, sadece harf, rakam ve alt çizgi kullanın</div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-posta Adresi <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="<?= htmlspecialchars($_SESSION['old']['email'] ?? '') ?>"
                                   required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Şifre <span class="text-danger">*</span></label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password" 
                                       name="password" 
                                       required>
                                <div class="form-text">En az 8 karakter</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Şifre Tekrar <span class="text-danger">*</span></label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="text-primary mb-3">
                            <i class="bi bi-star me-2"></i>Doğum Bilgileri (İsteğe Bağlı)
                        </h6>
                        <p class="text-muted small mb-3">Bu bilgiler daha kişisel burç yorumları için kullanılacaktır</p>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="birth_date" class="form-label">Doğum Tarihi</label>
                                <input type="date" 
                                       class="form-control" 
                                       id="birth_date" 
                                       name="birth_date" 
                                       value="<?= htmlspecialchars($_SESSION['old']['birth_date'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="birth_time" class="form-label">Doğum Saati</label>
                                <input type="time" 
                                       class="form-control" 
                                       id="birth_time" 
                                       name="birth_time" 
                                       value="<?= htmlspecialchars($_SESSION['old']['birth_time'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="birth_place" class="form-label">Doğum Yeri</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="birth_place" 
                                   name="birth_place" 
                                   placeholder="Şehir, Ülke" 
                                   value="<?= htmlspecialchars($_SESSION['old']['birth_place'] ?? '') ?>">
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label" for="terms">
                                <a href="/terms" target="_blank">Kullanım Şartları</a> ve 
                                <a href="/privacy" target="_blank">Gizlilik Politikası</a>'nı okudum ve kabul ediyorum.
                                <span class="text-danger">*</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-gradient btn-lg w-100 mb-3">
                            <i class="bi bi-person-plus me-2"></i>Hesap Oluştur
                        </button>

                        <div class="text-center">
                            <p class="text-muted mb-0">
                                Zaten hesabınız var mı? 
                                <a href="/login" class="text-decoration-none">Giriş yapın</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Features -->
            <div class="row mt-4">
                <div class="col-md-4 text-center mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-magic"></i>
                    </div>
                    <h6 class="mt-2">AI Tarot Falı</h6>
                    <p class="text-muted small">Yapay zeka destekli kişisel tarot yorumları</p>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-stars"></i>
                    </div>
                    <h6 class="mt-2">Günlük Burç</h6>
                    <p class="text-muted small">Kişiselleştirilmiş günlük burç yorumları</p>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-people"></i>
                    </div>
                    <h6 class="mt-2">Topluluk</h6>
                    <p class="text-muted small">Deneyimlerinizi paylaşın ve blog yazın</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php unset($_SESSION['old']); ?>

<script>
    // Password strength indicator
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const strength = calculatePasswordStrength(password);
        // You can add password strength indicator here
    });
    
    // Birth date validation
    document.getElementById('birth_date').addEventListener('change', function() {
        const birthDate = new Date(this.value);
        const today = new Date();
        const age = today.getFullYear() - birthDate.getFullYear();
        
        if (age < 13 || age > 100) {
            this.setCustomValidity('Lütfen geçerli bir doğum tarihi giriniz.');
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Password confirmation validation
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmation = this.value;
        
        if (password !== confirmation) {
            this.setCustomValidity('Şifreler eşleşmiyor.');
        } else {
            this.setCustomValidity('');
        }
    });
    
    function calculatePasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        return strength;
    }
</script>

<style>
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
</style>