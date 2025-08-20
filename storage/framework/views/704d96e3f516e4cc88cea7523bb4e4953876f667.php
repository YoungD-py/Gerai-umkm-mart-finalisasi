

<?php $__env->startSection('container'); ?>
    <style>
        .umkm-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.9));
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .umkm-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
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
            background: rgba(255, 255, 255, 0.1);
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
            padding: 1.5rem;
            overflow: hidden;
        }

        .btn-umkm {
            background: linear-gradient(135deg, #206BC4, #4A90E2);
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
            justify-content: center;
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

        .form-control,
        .form-select {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            padding: 12px 20px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
            background: white;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table-umkm {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
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
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .page-title h1 {
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 15px;
            color: #ffffff;
            text-shadow: 0 3px 6px rgba(0, 0, 0, 0.4);
        }

        .page-title p {
            font-size: 1.5rem;
            font-weight: 600;
            color: #ffffff;
            opacity: 1;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .search-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
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
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
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

        /* Pagination yang lebih jelas dan responsif */
        .pagination-wrapper .pagination {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .pagination-wrapper .page-link {
            border: none;
            padding: 12px 16px;
            color: #28a745;
            /* Hijau tegas */
            font-weight: 700;
            font-size: 1rem;
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
            color: white;
            font-weight: 700;
        }

        .pagination-wrapper .page-item .page-link[aria-label="Previous"],
        .pagination-wrapper .page-item .page-link[aria-label="Next"] {
            color: #28a745;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .pagination-wrapper .page-item.disabled .page-link {
            color: #a5a5a5;
            background: transparent;
        }

        /* Responsif di layar kecil */
        @media (max-width: 576px) {
            .pagination-wrapper .page-link {
                padding: 8px 12px;
                font-size: 0.9rem;
            }

            .pagination-wrapper .page-item .page-link[aria-label="Previous"],
            .pagination-wrapper .page-item .page-link[aria-label="Next"] {
                font-size: 1rem;
            }

            .pagination-wrapper .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }
        }

        @media (min-width: 768px) {
            .page-title h1 {
                font-size: 2.5rem;
            }

            .umkm-card-body {
                padding: 25px;
            }
        }
    </style>

    <div class="container-fluid py-4">
        <div class="page-title">
            <h1>🧾 MANAJEMEN DATA TRANSAKSI</h1>
            <p>Kelola riwayat dan detail transaksi penjualan</p>
        </div>

        <?php if(session()->has('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert"
                style="border-radius: 15px; border: none;">
                <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="umkm-card">
            <div class="umkm-card-header">
                
                <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center w-100 gap-2">
                    <h3 class="umkm-card-title mb-2 mb-md-0">
                        <i class="bi bi-receipt"></i>
                        Data Transaksi
                    </h3>
                    <button type="button" id="bulk-delete-button" class="btn btn-danger btn-umkm-sm w-100 w-md-auto"
                        style="display: none;">
                        <i class="bi bi-trash-fill"></i> Hapus Terpilih
                    </button>
                </div>
            </div>

            <div class="umkm-card-body">
                <!-- Search Section -->
                <div class="search-section">
                    <form action="/dashboard/transactions" method="GET">
                        <div class="row align-items-end">
                            <!-- Enhanced search form with date inputs and reset button -->
                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label text-white fw-bold">
                                    <i class="bi bi-search me-2"></i>Cari Berdasarkan Nomor Nota
                                </label>
                                <input type="text" class="form-control" placeholder="Masukkan nomor nota..." name="search"
                                    value="<?php echo e(request('search')); ?>">
                            </div>
                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label text-white fw-bold">
                                    <i class="bi bi-calendar me-2"></i>Tanggal Mulai
                                </label>
                                <input type="date" class="form-control" name="start_date"
                                    value="<?php echo e(request('start_date')); ?>">
                            </div>
                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label text-white fw-bold">
                                    <i class="bi bi-calendar-check me-2"></i>Tanggal Akhir
                                </label>
                                <input type="date" class="form-control" name="end_date" value="<?php echo e(request('end_date')); ?>">
                            </div>
                            <div class="col-12 col-lg-2 mb-3">
                                <div class="d-flex gap-2 flex-column flex-sm-row">
                                    <!-- Reduced button padding and font size to match input fields -->
                                    <button class="btn btn-umkm flex-fill" type="submit"
                                        style="padding: 12px 16px; font-size: 0.9rem;">
                                        <i class="bi bi-search"></i>
                                        <span class="d-none d-sm-inline">CARI</span>
                                    </button>
                                    <!-- Reduced reset button padding and font size to match input fields -->
                                    <a href="/dashboard/transactions" class="btn btn-secondary text-white flex-fill"
                                        style="border-radius: 15px; padding: 12px 16px; font-weight: 600; font-size: 0.9rem;">
                                        <i class="bi bi-arrow-clockwise"></i>
                                        <span class="d-none d-sm-inline">Reset</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="text-white">
                                    <small>
                                        <i class="bi bi-info-circle me-1"></i>
                                        Total: <?php echo e($transactions->total()); ?> transaksi
                                        <?php if(request('search') || request('start_date') || request('end_date')): ?>
                                            | Filter aktif:
                                            <?php if(request('search')): ?>
                                                Nota "<?php echo e(request('search')); ?>"
                                            <?php endif; ?>
                                            <?php if(request('start_date') || request('end_date')): ?>
                                                <?php if(request('search')): ?> • <?php endif; ?>
                                                Tanggal
                                                <?php if(request('start_date') && request('end_date')): ?>
                                                    <?php echo e(\Carbon\Carbon::parse(request('start_date'))->format('d/m/Y')); ?> -
                                                    <?php echo e(\Carbon\Carbon::parse(request('end_date'))->format('d/m/Y')); ?>

                                                <?php elseif(request('start_date')): ?>
                                                    dari <?php echo e(\Carbon\Carbon::parse(request('start_date'))->format('d/m/Y')); ?>

                                                <?php elseif(request('end_date')): ?>
                                                    sampai <?php echo e(\Carbon\Carbon::parse(request('end_date'))->format('d/m/Y')); ?>

                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Form untuk bulk delete -->
                <form id="bulk-delete-form" action="<?php echo e(route('transactions.bulkDelete')); ?>" method="POST">
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
                                    <th>NO</th>
                                    <th>No. Nota</th>
                                    <th>Waktu</th>
                                    <th>Petugas</th>
                                    <th>Metode Bayar</th>
                                    <th>
                                        
                                        Status
                                        <button type="button" class="btn btn-sm btn-light ms-2 sort-toggle"
                                            data-sort-param="status" data-sort-order="<?php echo e(request('sort_status', 'none')); ?>"
                                            title="Urutkan berdasarkan status">
                                            <i
                                                class="bi <?php echo e(request('sort_status') == 'failed_first' ? 'bi-sort-up text-warning' : (request('sort_status') == 'success_first' ? 'bi-sort-down text-success' : 'bi-arrow-down-up')); ?>"></i>
                                        </button>
                                    </th>
                                    <th>Total</th>
                                    <th>Bayar</th>
                                    <th>Kembalian</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr data-row-index="<?php echo e($key); ?>">
                                        <td class="text-center">
                                            <input class="form-check-input item-checkbox" type="checkbox" name="selected_ids[]"
                                                value="<?php echo e($transaction->id); ?>" data-row-index="<?php echo e($key); ?>">
                                        </td>
                                        <td><strong><?php echo e($transactions->firstItem() + $key); ?></strong></td>
                                        <td>
                                            <i class="bi bi-hash text-primary"></i>
                                            <?php echo e($transaction->no_nota); ?>

                                        </td>
                                        <td style="white-space:nowrap;">
                                            <i class="bi bi-clock text-info me-1"></i>
                                            <?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i')); ?>

                                        </td>
                                        <td><?php echo e($transaction->user->nama); ?></td>
                                        <td><?php echo e($transaction->metode_pembayaran); ?></td>
                                        <td>
                                            <?php if(strtolower(trim($transaction->status)) == 'lunas'): ?>
                                                <span class="badge bg-success"><?php echo e($transaction->status); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark"><?php echo e($transaction->status); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><strong>Rp <?php echo e(number_format($transaction->total_harga, 0, ',', '.')); ?></strong></td>
                                        <td>Rp <?php echo e(number_format($transaction->bayar, 0, ',', '.')); ?></td>
                                        <td>Rp <?php echo e(number_format($transaction->kembalian, 0, ',', '.')); ?></td>
                                        <td class="text-center">
                                            <div class="dropdown action-dropdown">
                                                <button class="btn btn-action dropdown-toggle" type="button"
                                                    id="dropdownMenuButton-<?php echo e($transaction->id); ?>" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="bi bi-three-dots-vertical fs-5"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end"
                                                    aria-labelledby="dropdownMenuButton-<?php echo e($transaction->id); ?>">
                                                    <li>
                                                        
                                                            <a href="#" class="dropdown-item download-nota-btn"
                                                                data-no-nota="<?php echo e($transaction->no_nota); ?>">
                                                                <i class="bi bi-download text-primary"></i> Unduh Nota
                                                            </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="/dashboard/transactions/<?php echo e($transaction->id); ?>/edit">
                                                            <i class="bi bi-credit-card text-success"></i> Pembayaran
                                                        </a>
                                                    </li>
                                                    <li style="display: none;">
                                                        <form action="/dashboard/transactions/<?php echo e($transaction->id); ?>"
                                                            method="post" class="dropdown-item-form"
                                                            id="deleteForm<?php echo e($transaction->id); ?>">
                                                            <?php echo method_field('delete'); ?>
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="no_nota"
                                                                value="<?php echo e($transaction->no_nota); ?>">
                                                            <button type="button" class="dropdown-item text-danger"
                                                                onclick="showDeleteModal(this, '<?php echo e($transaction->id); ?>', '<?php echo e($transaction->no_nota); ?>')">
                                                                <i class="bi bi-trash"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="/dashboard/orders" method="post"
                                                            class="dropdown-item-form" onsubmit="handleActionSubmit(this)">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="no_nota"
                                                                value="<?php echo e($transaction->no_nota); ?>">
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="bi bi-pencil-square text-warning"></i> Edit Pesanan
                                                            </button>
                                                        </form>
                                                    </li>


                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <form action="/dashboard/transactions/<?php echo e($transaction->id); ?>"
                                                            method="post" class="dropdown-item-form"
                                                            id="deleteForm<?php echo e($transaction->id); ?>">
                                                            <?php echo method_field('delete'); ?>
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="no_nota"
                                                                value="<?php echo e($transaction->no_nota); ?>">
                                                            <button type="button" class="dropdown-item text-danger"
                                                                onclick="showDeleteModal(this, '<?php echo e($transaction->id); ?>', '<?php echo e($transaction->no_nota); ?>')">
                                                                <i class="bi bi-trash"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="11" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bi bi-cart-x display-4 d-block mb-3"></i>
                                                <h5>Belum ada data transaksi</h5>
                                                <p>Belum ada transaksi yang tercatat di sistem</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </form>

                <!-- Pagination -->
                <?php if($transactions->hasPages()): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <div class="pagination-wrapper">
                            <?php echo e($transactions->links()); ?>

                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus SATUAN -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px; border: none;">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; border-bottom: none; border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title" id="deleteModalLabel"><i
                            class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Penghapusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="filter: invert(1) grayscale(100%) brightness(200%);"></button>
                </div>
                <div class="modal-body fs-5 text-center py-4">
                    Apakah Anda yakin ingin menghapus transaksi <br><strong id="itemNameToDelete"
                        class="text-danger"></strong>?
                </div>
                <div class="modal-footer" style="border-top: none;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="border-radius: 10px;">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton" style="border-radius: 10px;">Ya,
                        Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- [BARU] Modal Konfirmasi Hapus BANYAK -->
    <div class="modal fade" id="bulkDeleteConfirmationModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px; border: none;">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; border-bottom: none; border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title" id="bulkDeleteModalLabel"><i
                            class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus Massal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="filter: invert(1) grayscale(100%) brightness(200%);"></button>
                </div>
                <div class="modal-body fs-5 text-center py-4">
                    Apakah Anda yakin ingin menghapus <strong id="bulkDeleteCount" class="text-danger"></strong> transaksi
                    yang dipilih?
                </div>
                <div class="modal-footer" style="border-top: none;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="border-radius: 10px;">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmBulkDeleteButton"
                        style="border-radius: 10px;">Ya, Hapus Semua</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- SCRIPT LAMA UNTUK HAPUS SATUAN (TIDAK DIUBAH) ---
            const deleteModalElement = document.getElementById('deleteConfirmationModal');
            const deleteModal = new bootstrap.Modal(deleteModalElement);
            const confirmDeleteButton = document.getElementById('confirmDeleteButton');
            const itemNameToDeleteSpan = document.getElementById('itemNameToDelete');
            let formToSubmit = null;
            let originalButton = null;

            window.showDeleteModal = function (button, transactionId, transactionNota) {
                formToSubmit = document.getElementById('deleteForm' + transactionId);
                originalButton = button;
                itemNameToDeleteSpan.textContent = 'No. Nota ' + transactionNota;
                deleteModal.show();
            }

            confirmDeleteButton.addEventListener('click', function () {
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

            // --- SCRIPT UNTUK BULK DELETE ---
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
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = selectedCount > 0 && selectedCount === itemCheckboxes.length;
                }
            }

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function () {
                    itemCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateBulkDeleteButtonState();
                });
            }

            itemCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateBulkDeleteButtonState);
            });

            if (bulkDeleteButton) {
                bulkDeleteButton.addEventListener('click', function () {
                    const selectedCount = document.querySelectorAll('.item-checkbox:checked').length;
                    if (selectedCount > 0) {
                        bulkDeleteCountSpan.textContent = selectedCount;
                        bulkDeleteModal.show();
                    }
                });
            }

            if (confirmBulkDeleteButton) {
                confirmBulkDeleteButton.addEventListener('click', function () {
                    bulkDeleteModal.hide();
                    bulkDeleteForm.submit();
                });
            }

            const sortToggles = document.querySelectorAll('.sort-toggle');
            sortToggles.forEach(toggleButton => {
                toggleButton.addEventListener('click', function () {
                    const sortParam = this.dataset.sortParam;
                    let currentSortOrder = this.dataset.sortOrder;
                    let newSortOrder;

                    if (sortParam === 'status') {
                        if (currentSortOrder === 'none') {
                            newSortOrder = 'failed_first';
                        } else if (currentSortOrder === 'failed_first') {
                            newSortOrder = 'success_first';
                        } else {
                            newSortOrder = 'none';
                        }

                        const url = new URL(window.location.href);

                        if (newSortOrder !== 'none') {
                            url.searchParams.set('sort_status', newSortOrder);
                        } else {
                            url.searchParams.delete('sort_status');
                        }

                        url.searchParams.delete('page');
                        window.location.href = url.toString();
                    }
                });
            });

            // --- [PERBAIKAN UTAMA] Script tunggal untuk menangani semua tombol Unduh Nota ---
            const downloadNotaButtons = document.querySelectorAll('.download-nota-btn');
            downloadNotaButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault(); // Mencegah aksi default dari link

                    const buttonElement = this;
                    const originalHTML = buttonElement.innerHTML;
                    const noNota = buttonElement.getAttribute('data-no-nota');
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    // 1. Ubah tombol menjadi status loading
                    buttonElement.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Loading...
            `;
                    // Menonaktifkan pointer agar tidak bisa di-klik ganda
                    buttonElement.style.pointerEvents = 'none';

                    // 2. Gunakan Fetch API untuk mengirim request dan mengunduh file
                    fetch('/dashboard/cashiers/nota', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            no_nota: noNota
                        })
                    })
                        .then(response => {
                            if (!response.ok) {
                                // Jika server mengembalikan error (misal: 404, 500)
                                throw new Error('Gagal mengunduh nota. Respon server tidak valid.');
                            }
                            // Mendapatkan nama file dari header, jika ada
                            const disposition = response.headers.get('content-disposition');
                            let filename = `nota-${noNota}.pdf`; // Nama file default
                            if (disposition && disposition.indexOf('attachment') !== -1) {
                                const matches = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/.exec(disposition);
                                if (matches != null && matches[1]) {
                                    filename = matches[1].replace(/['"]/g, '');
                                }
                            }
                            return response.blob().then(blob => ({ blob, filename }));
                        })
                        .then(({ blob, filename }) => {
                            // 3. Membuat link sementara untuk men-trigger download di browser
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.style.display = 'none';
                            a.href = url;
                            a.download = filename;
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                            document.body.removeChild(a);
                        })
                        .catch(error => {
                            // Jika terjadi error pada jaringan atau proses fetch
                            console.error('Error saat mengunduh nota:', error);
                            // Anda bisa menampilkan notifikasi error di sini (misal: menggunakan alert atau SweetAlert)
                            alert('Terjadi kesalahan saat mencoba mengunduh nota. Silakan coba lagi.');
                        })
                        .finally(() => {
                            // 4. Kembalikan tombol ke keadaan semula, baik proses berhasil maupun gagal
                            buttonElement.innerHTML = originalHTML;
                            buttonElement.style.pointerEvents = 'auto'; // Mengaktifkan kembali pointer
                        });
                });
            });

            updateBulkDeleteButtonState();
        });

        // Fungsi untuk tombol yang menyebabkan navigasi (seperti Edit Pesanan)
        function handleActionSubmit(form) {
            const button = form.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.innerHTML = `
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                    `;
            }
            return true; // Lanjutkan submit
        }

        // Script untuk mengatasi masalah cache browser (bfcache)
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                // Reset semua button states
                const buttons = document.querySelectorAll('.dropdown-item-form button, .download-nota-direct');
                buttons.forEach(button => {
                    button.disabled = false;
                    if (button.classList.contains('download-nota-direct')) {
                        button.innerHTML = '<i class="bi bi-download text-primary"></i> Unduh Nota';
                    } else {
                        const form = button.closest('form');
                        if (form) {
                            const action = form.getAttribute('action');
                            if (action.includes('/dashboard/cashiers/nota')) {
                                button.innerHTML = '<i class="bi bi-download text-primary"></i> Unduh Nota';
                            } else if (action.includes('/dashboard/orders')) {
                                button.innerHTML = '<i class="bi bi-pencil-square text-warning"></i> Edit Pesanan';
                            } else if (action.includes('/dashboard/transactions')) {
                                if (button.type === 'button') {
                                    button.innerHTML = '<i class="bi bi-trash"></i> Hapus';
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>



    <!-- Tambahkan CSRF token untuk JavaScript -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/dashboard/transactions/index.blade.php ENDPATH**/ ?>