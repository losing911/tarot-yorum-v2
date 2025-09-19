<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="admin-dashboard">
    <!-- Admin Header -->
    <div class="admin-header bg-dark text-white py-3 mb-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h3 mb-0">
                        <i class="fas fa-users me-2"></i>
                        Kullanıcı Yönetimi
                    </h1>
                </div>
                <div class="col-md-6 text-end">
                    <a href="/admin" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i>Dashboard'a Dön
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Stats Cards -->
            <div class="col-12 mb-4">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card stats-card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title mb-0"><?= number_format($data['total_users']) ?></h5>
                                        <p class="card-text">Toplam Kullanıcı</p>
                                    </div>
                                    <div class="stats-icon">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card stats-card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title mb-0">
                                            <?php
                                            $activeCount = 0;
                                            foreach ($data['user_stats']['by_status'] ?? [] as $stat) {
                                                if ($stat['status'] === 'active') {
                                                    $activeCount = $stat['count'];
                                                    break;
                                                }
                                            }
                                            echo number_format($activeCount);
                                            ?>
                                        </h5>
                                        <p class="card-text">Aktif Kullanıcı</p>
                                    </div>
                                    <div class="stats-icon">
                                        <i class="fas fa-user-check fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card stats-card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title mb-0">
                                            <?php
                                            $adminCount = 0;
                                            foreach ($data['user_stats']['by_role'] ?? [] as $stat) {
                                                if ($stat['role'] === 'admin') {
                                                    $adminCount = $stat['count'];
                                                    break;
                                                }
                                            }
                                            echo number_format($adminCount);
                                            ?>
                                        </h5>
                                        <p class="card-text">Admin</p>
                                    </div>
                                    <div class="stats-icon">
                                        <i class="fas fa-user-shield fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card stats-card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title mb-0">
                                            <?php
                                            $pendingCount = 0;
                                            foreach ($data['user_stats']['by_status'] ?? [] as $stat) {
                                                if ($stat['status'] === 'pending') {
                                                    $pendingCount = $stat['count'];
                                                    break;
                                                }
                                            }
                                            echo number_format($pendingCount);
                                            ?>
                                        </h5>
                                        <p class="card-text">Beklemede</p>
                                    </div>
                                    <div class="stats-icon">
                                        <i class="fas fa-user-clock fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="/admin/users" class="row g-3">
                            <div class="col-md-4">
                                <label for="search" class="form-label">Arama</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       placeholder="Kullanıcı adı veya email" 
                                       value="<?= htmlspecialchars($data['current_search'] ?? '') ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="role" class="form-label">Rol</label>
                                <select class="form-select" id="role" name="role">
                                    <option value="">Tüm Roller</option>
                                    <option value="user" <?= $data['current_role'] === 'user' ? 'selected' : '' ?>>Kullanıcı</option>
                                    <option value="admin" <?= $data['current_role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="status" class="form-label">Durum</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">Tüm Durumlar</option>
                                    <option value="active" <?= $data['current_status'] === 'active' ? 'selected' : '' ?>>Aktif</option>
                                    <option value="pending" <?= $data['current_status'] === 'pending' ? 'selected' : '' ?>>Beklemede</option>
                                    <option value="suspended" <?= $data['current_status'] === 'suspended' ? 'selected' : '' ?>>Askıya Alınmış</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i>Filtrele
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>
                            Kullanıcılar (<?= number_format($data['total_users']) ?>)
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-download me-2"></i>Export
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/admin/export/users/csv">CSV İndir</a></li>
                                <li><a class="dropdown-item" href="/admin/export/users/json">JSON İndir</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($data['users'])): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5>Kullanıcı bulunamadı</h5>
                                <p class="text-muted">Arama kriterlerinizi değiştirmeyi deneyin.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Kullanıcı</th>
                                            <th>Email</th>
                                            <th>Rol</th>
                                            <th>Durum</th>
                                            <th>Kayıt Tarihi</th>
                                            <th>Son Giriş</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data['users'] as $user): ?>
                                        <tr id="user-<?= $user['id'] ?>">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                        <?= strtoupper(substr($user['username'], 0, 1)) ?>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold"><?= htmlspecialchars($user['username']) ?></div>
                                                        <?php if ($user['full_name']): ?>
                                                            <small class="text-muted"><?= htmlspecialchars($user['full_name']) ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($user['email']) ?></td>
                                            <td>
                                                <select class="form-select form-select-sm" onchange="updateUserRole(<?= $user['id'] ?>, this.value)">
                                                    <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Kullanıcı</option>
                                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select form-select-sm" onchange="updateUserStatus(<?= $user['id'] ?>, this.value)">
                                                    <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Aktif</option>
                                                    <option value="pending" <?= $user['status'] === 'pending' ? 'selected' : '' ?>>Beklemede</option>
                                                    <option value="suspended" <?= $user['status'] === 'suspended' ? 'selected' : '' ?>>Askıya Alınmış</option>
                                                </select>
                                            </td>
                                            <td>
                                                <span title="<?= $user['created_at'] ?>">
                                                    <?= formatTurkishDate($user['created_at']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($user['last_login']): ?>
                                                    <span title="<?= $user['last_login'] ?>">
                                                        <?= formatTurkishDate($user['last_login']) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">Hiç giriş yok</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button class="btn btn-outline-primary" onclick="viewUser(<?= $user['id'] ?>)" title="Görüntüle">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-outline-info" onclick="editUser(<?= $user['id'] ?>)" title="Düzenle">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                        <button class="btn btn-outline-danger" onclick="deleteUser(<?= $user['id'] ?>)" title="Sil">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <?php if ($data['total_pages'] > 1): ?>
            <div class="col-12">
                <nav aria-label="Kullanıcı sayfalama">
                    <ul class="pagination justify-content-center">
                        <?php if ($data['current_page'] > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $data['current_page'] - 1 ?>&search=<?= urlencode($data['current_search'] ?? '') ?>&role=<?= urlencode($data['current_role'] ?? '') ?>&status=<?= urlencode($data['current_status'] ?? '') ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php
                        $start = max(1, $data['current_page'] - 2);
                        $end = min($data['total_pages'], $data['current_page'] + 2);
                        
                        for ($i = $start; $i <= $end; $i++):
                        ?>
                            <li class="page-item <?= $i === $data['current_page'] ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($data['current_search'] ?? '') ?>&role=<?= urlencode($data['current_role'] ?? '') ?>&status=<?= urlencode($data['current_status'] ?? '') ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($data['current_page'] < $data['total_pages']): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $data['current_page'] + 1 ?>&search=<?= urlencode($data['current_search'] ?? '') ?>&role=<?= urlencode($data['current_role'] ?? '') ?>&status=<?= urlencode($data['current_status'] ?? '') ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- User Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kullanıcı Detayları</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userModalBody">
                <!-- User details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
.admin-dashboard .stats-card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.admin-dashboard .stats-icon {
    opacity: 0.8;
}

.admin-dashboard .avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 16px;
}

.admin-dashboard .table th {
    border-top: none;
    font-weight: 600;
}

.admin-dashboard .form-select-sm {
    min-width: 120px;
}

.admin-dashboard .btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}
</style>

<script>
// Update user role
function updateUserRole(userId, role) {
    fetch(`/admin/users/${userId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': '<?= $data['csrf_token'] ?>'
        },
        body: JSON.stringify({ role: role })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Kullanıcı rolü güncellendi!', 'success');
        } else {
            showAlert(data.error || 'Bir hata oluştu!', 'error');
            location.reload();
        }
    })
    .catch(error => {
        showAlert('Bir hata oluştu!', 'error');
        location.reload();
    });
}

// Update user status
function updateUserStatus(userId, status) {
    fetch(`/admin/users/${userId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': '<?= $data['csrf_token'] ?>'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Kullanıcı durumu güncellendi!', 'success');
        } else {
            showAlert(data.error || 'Bir hata oluştu!', 'error');
            location.reload();
        }
    })
    .catch(error => {
        showAlert('Bir hata oluştu!', 'error');
        location.reload();
    });
}

// View user details
function viewUser(userId) {
    // For now, show basic info - can be expanded to show more details
    const row = document.querySelector(`#user-${userId}`);
    const username = row.querySelector('.fw-bold').textContent;
    const email = row.cells[1].textContent;
    
    document.getElementById('userModalBody').innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <strong>Kullanıcı Adı:</strong><br>
                ${username}
            </div>
            <div class="col-md-6">
                <strong>Email:</strong><br>
                ${email}
            </div>
        </div>
    `;
    
    new bootstrap.Modal(document.getElementById('userModal')).show();
}

// Edit user (placeholder)
function editUser(userId) {
    // This would open an edit modal or redirect to edit page
    showAlert('Düzenleme özelliği yakında eklenecek!', 'info');
}

// Delete user
function deleteUser(userId) {
    if (confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.')) {
        fetch(`/admin/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': '<?= $data['csrf_token'] ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Kullanıcı başarıyla silindi!', 'success');
                document.querySelector(`#user-${userId}`).remove();
            } else {
                showAlert(data.error || 'Kullanıcı silinemedi!', 'error');
            }
        })
        .catch(error => {
            showAlert('Bir hata oluştu!', 'error');
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