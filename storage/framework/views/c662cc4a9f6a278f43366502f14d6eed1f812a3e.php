

<?php $__env->startSection('container'); ?>
<style>
    /* --- CSS Styles copied from other dashboards for consistency --- */
    .umkm-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.9));
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.2);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .umkm-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .umkm-card-header {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        padding: 20px;
        border-radius: 20px 20px 0 0;
        position: relative;
        overflow: hidden;
    }

    .umkm-card-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.1);
        transform: rotate(45deg);
        transition: all 0.3s ease;
    }

    .umkm-card-header:hover::before {
        right: -30%;
    }

    .umkm-card-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .umkm-card-body {
        padding: 25px;
        overflow: hidden;
    }

    .btn-umkm {
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
        border-radius: 15px;
        padding: 12px 25px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-umkm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        color: white;
        text-decoration: none;
    }

    .btn-umkm-sm {
        padding: 8px 15px;
        font-size: 0.875rem;
    }

    .form-control, .form-select {
        border-radius: 15px;
        border: 2px solid #e9ecef;
        padding: 12px 20px;
        transition: all 0.3s ease;
        background: rgba(255,255,255,0.9);
    }

    .form-control:focus, .form-select:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        background: white;
    }

    .table-umkm {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    .table-umkm thead th {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        padding: 15px 12px;
        white-space: nowrap; 
    }

    .table-umkm tbody td {
        padding: 15px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f8f9fa;
    }

    .table-umkm tbody tr:last-child td {
        border-bottom: none;
    }

    .table-umkm tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .page-title {
        color: white;
        text-align: center;
        margin-bottom: 30px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .page-title h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .page-title p {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .search-section {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .input-group .form-control {
        border-radius: 15px 0 0 15px;
    }

    .input-group .btn {
        border-radius: 0 15px 15px 0;
    }

    .action-dropdown {
        position: static; 
    }
    
    .action-dropdown .dropdown-toggle::after {
        display: none; 
    }

    .action-dropdown .btn-action {
        background: transparent;
        border: none;
        color: #6c757d;
        padding: 0.25rem 0.5rem;
    }
    .action-dropdown .btn-action:hover,
    .action-dropdown .btn-action:focus {
        background-color: #e9ecef;
        color: #212529;
    }

    .action-dropdown .dropdown-menu {
        border-radius: 15px;
        border: 1px solid rgba(0,0,0,0.1);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        padding: 0.5rem 0;
        z-index: 100; 
    }

    .action-dropdown .dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1.25rem;
        font-weight: 500;
        transition: background-color 0.2s ease, color 0.2s ease;
    }
    
    .action-dropdown .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .action-dropdown .dropdown-item i {
        font-size: 1.1rem;
        width: 20px;
        text-align: center;
    }
    
    .action-dropdown .dropdown-item-form {
        padding: 0;
        margin: 0;
    }
    .action-dropdown .dropdown-item-form button {
        width: 100%;
        text-align: left;
        background: none;
        border: none;
    }

    .action-dropdown .dropdown-item.text-danger:hover {
        background-color: #fdf2f2;
        color: #c82333 !important;
    }

    .pagination-wrapper .pagination {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .pagination-wrapper .page-link {
        border: none;
        padding: 12px 16px;
        color: #28a745;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .pagination-wrapper .page-link:hover {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        transform: translateY(-1px);
    }

    .pagination-wrapper .page-item.active .page-link {
        background: linear-gradient(135deg, #28a745, #20c997);
        border-color: #28a745;
    }
</style>

<div class="container-fluid py-4">
    <div class="page-title">
        <h1>👥 MANAJEMEN DATA ADMIN</h1>
        <p>Kelola data administrator dan kasir untuk sistem</p>
    </div>

    <?php if(session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 15px; border: none;">
            <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    
    <?php if(session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 15px; border: none;">
            <i class="bi bi-x-circle-fill me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="umkm-card">
        <div class="umkm-card-header">
            <div class="d-flex justify-content-between align-items-center w-100">
                <h3 class="umkm-card-title">
                    <i class="bi bi-people-fill"></i>
                    Data Admin & Kasir
                </h3>
                
                <div class="d-flex gap-2 align-items-center">
                    <button type="button" id="bulk-delete-button" class="btn btn-danger btn-umkm-sm" style="display: none;">
                        <i class="bi bi-trash-fill"></i> Hapus Terpilih
                    </button>
                    <a href="/dashboard/users/create" class="btn-umkm btn-umkm-sm">
                        <i class="bi bi-plus-circle"></i>
                        Tambah Pengguna
                    </a>
                </div>
            </div>
        </div>

        <div class="umkm-card-body">
            <!-- Search Section -->
            <div class="search-section">
                <form action="/dashboard/users" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-8 mb-3">
                            <label class="form-label text-white fw-bold">
                                <i class="bi bi-search me-2"></i>Cari Pengguna
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Masukkan nama atau username..."
                                       name="search" value="<?php echo e(request('search')); ?>">
                                <button class="btn btn-umkm" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-white">
                                <small><i class="bi bi-info-circle me-1"></i>Total: <?php echo e($users->total()); ?> pengguna</small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- [BARU] Form untuk bulk delete -->
            <form id="bulk-delete-form" action="<?php echo e(route('users.bulkDelete')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-umkm">
                        <thead>
                            <tr>
                                
                                <th style="width: 3%; text-align: center;">
                                    <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                                </th>
                                <th style="width: 5%;">#</th>
                                <th style="width: 35%;">Nama Pengguna</th>
                                <th style="width: 20%;">Username</th>
                                <th style="width: 12%;">Role</th>
                                <th style="width: 15%;">Bergabung</th>
                                <th style="width: 10%; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                
                                <td class="text-center">
                                    
                                    <?php if(auth()->user()->id !== $user->id): ?>
                                        <input class="form-check-input item-checkbox" type="checkbox" name="selected_ids[]" value="<?php echo e($user->id); ?>">
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo e($users->firstItem() + $key); ?></strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-secondary text-white me-3" style="width: 40px; height: 40px; font-weight: bold;">
                                            <?php echo e(strtoupper(substr($user->nama, 0, 1))); ?>

                                        </div>
                                        <div>
                                            <strong><?php echo e($user->nama); ?></strong>
                                            <small class="d-block text-muted">ID: <?php echo e($user->id); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code><?php echo e($user->username); ?></code>
                                </td>
                                <td>
                                    <?php if($user->role == 'ADMIN'): ?>
                                        <span class="badge bg-success"><i class="bi bi-shield-check me-1"></i><?php echo e($user->role); ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-primary"><i class="bi bi-person-badge me-1"></i><?php echo e($user->role); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <i class="bi bi-calendar3 text-muted me-1"></i>
                                    <?php echo e($user->created_at->format('d M Y')); ?>

                                </td>
                                <td class="text-center">
                                    <div class="dropdown action-dropdown">
                                        <button class="btn btn-action dropdown-toggle" type="button" id="dropdownMenuButton-<?php echo e($user->id); ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical fs-5"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton-<?php echo e($user->id); ?>">
                                            <li>
                                                <a class="dropdown-item" href="/dashboard/users/<?php echo e($user->id); ?>/edit">
                                                    <i class="bi bi-pencil-square text-warning"></i> Edit
                                                </a>
                                            </li>
                                            
                                            <?php if(auth()->user()->id !== $user->id): ?>
                                            <li>
                                                <form action="/dashboard/users/<?php echo e($user->id); ?>" method="post" class="dropdown-item-form" id="deleteForm<?php echo e($user->id); ?>">
                                                    <?php echo method_field('delete'); ?>
                                                    <?php echo csrf_field(); ?>
                                                    <button type="button" class="dropdown-item text-danger" onclick="showDeleteModal(this, '<?php echo e($user->id); ?>', '<?php echo e($user->nama); ?>')">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                        <h5>Belum ada data pengguna</h5>
                                        <p>Silakan tambah pengguna baru untuk memulai</p>
                                        <a href="/dashboard/users/create" class="btn-umkm">
                                            <i class="bi bi-plus-circle"></i>
                                            Tambah Pengguna
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </form>

            <!-- Pagination -->
            <?php if($users->hasPages()): ?>
            <div class="d-flex justify-content-center mt-4">
                <div class="pagination-wrapper">
                    <?php echo e($users->links()); ?>

                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus SATUAN -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 15px; border: none;">
      <div class="modal-header" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; border-bottom: none; border-radius: 15px 15px 0 0;">
        <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Penghapusan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1) grayscale(100%) brightness(200%);"></button>
      </div>
      <div class="modal-body fs-5 text-center py-4">
        Apakah Anda yakin ingin menghapus pengguna <br><strong id="userNameToDelete" class="text-danger"></strong>?
      </div>
      <div class="modal-footer" style="border-top: none;">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteButton" style="border-radius: 10px;">Ya, Hapus</button>
      </div>
    </div>
  </div>
</div>

<!-- [BARU] Modal Konfirmasi Hapus BANYAK -->
<div class="modal fade" id="bulkDeleteConfirmationModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 15px; border: none;">
      <div class="modal-header" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; border-bottom: none; border-radius: 15px 15px 0 0;">
        <h5 class="modal-title" id="bulkDeleteModalLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus Massal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1) grayscale(100%) brightness(200%);"></button>
      </div>
      <div class="modal-body fs-5 text-center py-4">
        Apakah Anda yakin ingin menghapus <strong id="bulkDeleteCount" class="text-danger"></strong> pengguna yang dipilih?
      </div>
      <div class="modal-footer" style="border-top: none;">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmBulkDeleteButton" style="border-radius: 10px;">Ya, Hapus Semua</button>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- SCRIPT LAMA UNTUK HAPUS SATUAN (TIDAK DIUBAH) ---
        const deleteModalElement = document.getElementById('deleteConfirmationModal');
        const deleteModal = new bootstrap.Modal(deleteModalElement);
        const confirmDeleteButton = document.getElementById('confirmDeleteButton');
        const userNameToDeleteSpan = document.getElementById('userNameToDelete');
        let formToSubmit = null;
        let originalButton = null;

        window.showDeleteModal = function(button, userId, userName) {
            formToSubmit = document.getElementById('deleteForm' + userId);
            originalButton = button;
            userNameToDeleteSpan.textContent = userName;
            deleteModal.show();
        }

        confirmDeleteButton.addEventListener('click', function() {
            if (formToSubmit && originalButton) {
                originalButton.disabled = true;
                originalButton.innerHTML = `
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                `;
                deleteModal.hide();
                formToSubmit.submit();
            }
        });

        // --- [BARU] SCRIPT UNTUK BULK DELETE ---
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const bulkDeleteButton = document.getElementById('bulk-delete-button');
        const bulkDeleteForm = document.getElementById('bulk-delete-form');
        const bulkDeleteModal = new bootstrap.Modal(document.getElementById('bulkDeleteConfirmationModal'));
        const bulkDeleteCountSpan = document.getElementById('bulkDeleteCount');
        const confirmBulkDeleteButton = document.getElementById('confirmBulkDeleteButton');

        function updateBulkDeleteButtonState() {
            const selectedCount = document.querySelectorAll('.item-checkbox:checked').length;
            if (selectedCount > 0) {
                bulkDeleteButton.style.display = 'inline-flex';
                bulkDeleteButton.querySelector('.bi').nextSibling.textContent = ` Hapus ${selectedCount} Terpilih`;
            } else {
                bulkDeleteButton.style.display = 'none';
            }
            // Cek jika semua checkbox yang BISA dipilih, sudah terpilih
            selectAllCheckbox.checked = itemCheckboxes.length > 0 && selectedCount === itemCheckboxes.length;
        }

        if(selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkDeleteButtonState();
            });
        }

        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkDeleteButtonState);
        });

        if(bulkDeleteButton) {
            bulkDeleteButton.addEventListener('click', function() {
                const selectedCount = document.querySelectorAll('.item-checkbox:checked').length;
                if (selectedCount > 0) {
                    bulkDeleteCountSpan.textContent = selectedCount;
                    bulkDeleteModal.show();
                }
            });
        }
        
        if(confirmBulkDeleteButton) {
            confirmBulkDeleteButton.addEventListener('click', function() {
                bulkDeleteForm.submit();
            });
        }
        
        updateBulkDeleteButtonState();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Repo_Git\Gerai-umkm-mart-finalisasi\resources\views/dashboard/users/index.blade.php ENDPATH**/ ?>