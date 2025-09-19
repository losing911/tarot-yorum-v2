<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="admin-dashboard">
    <!-- Admin Header -->
    <div class="admin-header bg-dark text-white py-3 mb-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h3 mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Admin Dashboard
                    </h1>
                </div>
                <div class="col-md-6 text-end">
                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle" type="button" id="adminMenu" data-bs-toggle="dropdown">
                            <i class="fas fa-user-shield me-2"></i>
                            <?= htmlspecialchars($_SESSION['username']) ?>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/profile"><i class="fas fa-user me-2"></i>Profil</a></li>
                            <li><a class="dropdown-item" href="/admin/settings"><i class="fas fa-cog me-2"></i>Ayarlar</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt me-2"></i>Çıkış</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Admin Navigation -->
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card admin-nav">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="/admin" class="list-group-item list-group-item-action active">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                            <a href="/admin/users" class="list-group-item list-group-item-action">
                                <i class="fas fa-users me-2"></i>Kullanıcılar
                                <?php if ($data['stats']['pending_comments'] > 0): ?>
                                    <span class="badge bg-warning ms-auto"><?= $data['stats']['pending_comments'] ?></span>
                                <?php endif; ?>
                            </a>
                            <a href="/admin/content" class="list-group-item list-group-item-action">
                                <i class="fas fa-edit me-2"></i>İçerik Yönetimi
                            </a>
                            <a href="/admin/comments" class="list-group-item list-group-item-action">
                                <i class="fas fa-comments me-2"></i>Yorumlar
                                <?php if ($data['stats']['pending_comments'] > 0): ?>
                                    <span class="badge bg-danger ms-auto"><?= $data['stats']['pending_comments'] ?></span>
                                <?php endif; ?>
                            </a>
                            <a href="/admin/analytics" class="list-group-item list-group-item-action">
                                <i class="fas fa-chart-bar me-2"></i>Analytics
                            </a>
                            <a href="/admin/settings" class="list-group-item list-group-item-action">
                                <i class="fas fa-cog me-2"></i>Ayarlar
                            </a>
                            <div class="mt-3 px-3">
                                <button class="btn btn-primary w-100" onclick="createBackup()">
                                    <i class="fas fa-download me-2"></i>Yedek Al
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card stats-card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title mb-0"><?= number_format($data['stats']['total_users']) ?></h5>
                                        <p class="card-text">Toplam Kullanıcı</p>
                                    </div>
                                    <div class="stats-icon">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                                <small class="text-light">
                                    <i class="fas fa-arrow-up me-1"></i>
                                    +<?= $data['stats']['new_users_month'] ?> bu ay
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card stats-card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title mb-0"><?= number_format($data['stats']['published_posts']) ?></h5>
                                        <p class="card-text">Yayınlanan Yazılar</p>
                                    </div>
                                    <div class="stats-icon">
                                        <i class="fas fa-edit fa-2x"></i>
                                    </div>
                                </div>
                                <small class="text-light">
                                    Toplam <?= $data['stats']['total_posts'] ?> yazı
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card stats-card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title mb-0"><?= number_format($data['stats']['total_readings']) ?></h5>
                                        <p class="card-text">Tarot Falı</p>
                                    </div>
                                    <div class="stats-icon">
                                        <i class="fas fa-magic fa-2x"></i>
                                    </div>
                                </div>
                                <small class="text-light">
                                    +<?= $data['stats']['readings_month'] ?> bu ay
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card stats-card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title mb-0"><?= number_format($data['stats']['active_users']) ?></h5>
                                        <p class="card-text">Aktif Kullanıcı</p>
                                    </div>
                                    <div class="stats-icon">
                                        <i class="fas fa-user-check fa-2x"></i>
                                    </div>
                                </div>
                                <small class="text-light">
                                    Son 30 günde aktif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row mb-4">
                    <div class="col-lg-8 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chart-line me-2"></i>
                                    Son 30 Gün Aktivitesi
                                </h5>
                            </div>
                            <div class="card-body">
                                <canvas id="activityChart" height="100"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-tags me-2"></i>
                                    Popüler Kategoriler
                                </h5>
                            </div>
                            <div class="card-body">
                                <canvas id="categoryChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Son Kayıt Olan Kullanıcılar
                                </h5>
                                <a href="/admin/users" class="btn btn-sm btn-outline-primary">Tümü</a>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Kullanıcı</th>
                                                <th>Email</th>
                                                <th>Tarih</th>
                                                <th>Durum</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data['recent_users'] as $user): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                            <?= strtoupper(substr($user['username'], 0, 1)) ?>
                                                        </div>
                                                        <?= htmlspecialchars($user['username']) ?>
                                                    </div>
                                                </td>
                                                <td><?= htmlspecialchars($user['email']) ?></td>
                                                <td><?= formatTurkishDate($user['created_at']) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'warning' ?>">
                                                        <?= $user['status'] === 'active' ? 'Aktif' : 'Beklemede' ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-comments me-2"></i>
                                    Bekleyen Yorumlar
                                </h5>
                                <a href="/admin/comments" class="btn btn-sm btn-outline-warning">Tümü</a>
                            </div>
                            <div class="card-body p-0">
                                <?php if (empty($data['pending_comments'])): ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                                        <p class="mb-0">Bekleyen yorum yok!</p>
                                    </div>
                                <?php else: ?>
                                    <div class="list-group list-group-flush">
                                        <?php foreach ($data['pending_comments'] as $comment): ?>
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1"><?= htmlspecialchars($comment['post_title']) ?></h6>
                                                    <p class="mb-1 text-muted small">
                                                        <?= htmlspecialchars(substr($comment['content'], 0, 100)) ?>...
                                                    </p>
                                                    <small class="text-muted">
                                                        <?= $comment['username'] ?? 'Misafir' ?> - 
                                                        <?= formatTurkishDate($comment['created_at']) ?>
                                                    </small>
                                                </div>
                                                <div class="ms-2">
                                                    <button class="btn btn-sm btn-success me-1" onclick="approveComment(<?= $comment['id'] ?>)">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="rejectComment(<?= $comment['id'] ?>)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Info -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-server me-2"></i>
                                    Sistem Bilgileri
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>PHP Sürümü:</strong></td>
                                                <td><?= $data['system_info']['php_version'] ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Veritabanı:</strong></td>
                                                <td><?= $data['system_info']['database_version'] ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Sunucu:</strong></td>
                                                <td><?= $data['system_info']['server_software'] ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Bellek Kullanımı:</strong></td>
                                                <td><?= $data['system_info']['memory_usage'] ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Disk Alanı:</strong></td>
                                                <td><?= $data['system_info']['disk_free_space'] ?> / <?= $data['system_info']['disk_total_space'] ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Zaman Dilimi:</strong></td>
                                                <td><?= $data['system_info']['timezone'] ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Sistem Saati:</strong></td>
                                                <td><?= $data['system_info']['current_time'] ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Upload Limiti:</strong></td>
                                                <td><?= $data['system_info']['upload_max_filesize'] ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.admin-dashboard .stats-card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
}

.admin-dashboard .stats-card:hover {
    transform: translateY(-2px);
}

.admin-dashboard .stats-icon {
    opacity: 0.8;
}

.admin-dashboard .avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
}

.admin-dashboard .admin-nav .list-group-item {
    border: none;
    padding: 12px 20px;
}

.admin-dashboard .admin-nav .list-group-item:hover {
    background-color: #f8f9fa;
}

.admin-dashboard .admin-nav .list-group-item.active {
    background-color: #007bff;
    color: white;
}

.admin-dashboard .card {
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.admin-dashboard .table th {
    border-top: none;
    font-weight: 600;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Activity Chart
const activityCtx = document.getElementById('activityChart').getContext('2d');
const activityChart = new Chart(activityCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($data['analytics']['user_registrations'], 'date')) ?>,
        datasets: [
            {
                label: 'Kullanıcı Kayıtları',
                data: <?= json_encode(array_column($data['analytics']['user_registrations'], 'count')) ?>,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                fill: true
            },
            {
                label: 'Blog Yazıları',
                data: <?= json_encode(array_column($data['analytics']['blog_posts'], 'count')) ?>,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                fill: true
            },
            {
                label: 'Tarot Falları',
                data: <?= json_encode(array_column($data['analytics']['tarot_readings'], 'count')) ?>,
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
const categoryChart = new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_column($data['stats']['popular_categories'], 'category')) ?>,
        datasets: [{
            data: <?= json_encode(array_column($data['stats']['popular_categories'], 'count')) ?>,
            backgroundColor: [
                '#007bff',
                '#28a745',
                '#ffc107',
                '#dc3545',
                '#6f42c1'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Comment moderation functions
function approveComment(commentId) {
    if (confirm('Bu yorumu onaylamak istediğinizden emin misiniz?')) {
        fetch(`/admin/comments/${commentId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': '<?= $data['csrf_token'] ?>'
            },
            body: JSON.stringify({ status: 'approved' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Yorum onaylandı!', 'success');
                location.reload();
            } else {
                showAlert(data.error || 'Bir hata oluştu!', 'error');
            }
        })
        .catch(error => {
            showAlert('Bir hata oluştu!', 'error');
        });
    }
}

function rejectComment(commentId) {
    if (confirm('Bu yorumu reddetmek istediğinizden emin misiniz?')) {
        fetch(`/admin/comments/${commentId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': '<?= $data['csrf_token'] ?>'
            },
            body: JSON.stringify({ status: 'rejected' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Yorum reddedildi!', 'success');
                location.reload();
            } else {
                showAlert(data.error || 'Bir hata oluştu!', 'error');
            }
        })
        .catch(error => {
            showAlert('Bir hata oluştu!', 'error');
        });
    }
}

// Backup function
function createBackup() {
    if (confirm('Sistem yedeği oluşturulacak. Bu işlem birkaç dakika sürebilir. Devam etmek istediğinizden emin misiniz?')) {
        showAlert('Yedekleme başlatıldı...', 'info');
        
        fetch('/admin/backup', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': '<?= $data['csrf_token'] ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Yedekleme başarıyla tamamlandı!', 'success');
            } else {
                showAlert(data.error || 'Yedekleme başarısız!', 'error');
            }
        })
        .catch(error => {
            showAlert('Yedekleme sırasında bir hata oluştu!', 'error');
        });
    }
}

// Alert function
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    alertDiv.style.top = '20px';
    alertDiv.style.right = '20px';
    alertDiv.style.zIndex = '9999';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>