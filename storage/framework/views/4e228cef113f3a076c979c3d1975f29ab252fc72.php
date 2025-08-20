<?php $__env->startSection('container'); ?>
<style>
    .umkm-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.9));
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.2);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .umkm-card-header {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        padding: 20px;
        border-radius: 20px 20px 0 0;
        position: relative;
        overflow: hidden;
    }

    .umkm-card-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-umkm {
        background: linear-gradient(135deg, #17a2b8, #17a2b8);
        border: none;
        border-radius: 15px;
        padding: 8px 15px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
    }

    .btn-umkm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        color: white;
        text-decoration: none;
    }

    .stock-low {
        background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(200, 35, 51, 0.1));
        border-left: 4px solid #dc3545;
    }

    .stock-medium {
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(224, 168, 0, 0.1));
        border-left: 4px solid #ffc107;
    }

    .stock-high {
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1));
        border-left: 4px solid #28a745;
    }

    .page-title {
        color: white;
        text-align: center;
        margin-bottom: 30px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .page-title h1 {
    font-size: 3rem;
    font-weight: 900;
    margin-bottom: 15px;
    color: #ffffff;
    text-shadow: 0 3px 6px rgba(0,0,0,0.4);
    }

    .page-title p {
    font-size: 1.5rem;
    font-weight: 600;
    color: #ffffff;
    opacity: 1;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }


    .search-box {
        background: white;
        border-radius: 15px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .form-control {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 10px 15px;
    }

    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .table {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .table thead th {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border: none;
        font-weight: 600;
        padding: 15px;
    }

    .table tbody td {
        padding: 15px;
        vertical-align: middle;
        border-color: #f8f9fa;
    }

    .badge-stock {
        padding: 8px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-low {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }

    .badge-medium {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        color: #000;
    }

    .badge-high {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }

    @media (min-width: 768px) {
        .page-title h1 {
            font-size: 2.5rem;
        }
    }

    /* Pagination yang lebih jelas */
    .pagination-wrapper .pagination {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .pagination-wrapper .page-link {
        border: none;
        padding: 12px 16px;
        color: #28a745; /* Hijau tegas */
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
</style>

<div class="container-fluid py-4">
    <div class="page-title">
        <h1>📦 RESTOCK BARANG</h1>
        <p>Kelola stok barang di inventori GERAI UMKM MART</p>
    </div>

    <?php if(session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="umkm-card">
                <div class="umkm-card-header">
                    <h3 class="umkm-card-title">
                        <i class="bi bi-boxes"></i>
                        Daftar Barang untuk Restock
                    </h3>
                </div>

                <div class="card-body p-3 p-md-4">
                    <!-- Search Box -->
                    <div class="search-box">
                        <form action="/dashboard/restock" id="search-form">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Cari nama barang atau mitra..."
                                               name="search" value="<?php echo e(request('search')); ?>">
                                        <button class="btn btn-umkm" type="submit">
                                            <i class="bi bi-search"></i>
                                            <span class="d-none d-sm-inline ms-1">Cari</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Added mitra filter dropdown -->
                                    <select name="mitra" class="form-select" onchange="this.form.submit()">
                                        <option value="">Semua Mitra</option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($category->id); ?>" <?php echo e(request('mitra') == $category->id ? 'selected' : ''); ?>>
                                                <?php echo e($category->nama); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <!-- Hidden inputs to preserve sorting when searching -->
                            <?php if(request('sort_stok')): ?>
                                <input type="hidden" name="sort_stok" value="<?php echo e(request('sort_stok')); ?>">
                            <?php endif; ?>
                            <?php if(request('sort_mitra')): ?>
                                <input type="hidden" name="sort_mitra" value="<?php echo e(request('sort_mitra')); ?>">
                            <?php endif; ?>
                            <?php if(request('sort_status')): ?>
                                <input type="hidden" name="sort_status" value="<?php echo e(request('sort_status')); ?>">
                            <?php endif; ?>
                            <?php if(request('filter_status')): ?>
                                <input type="hidden" name="filter_status" value="<?php echo e(request('filter_status')); ?>">
                            <?php endif; ?>
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">NO</th>
                                    <th scope="col">Nama Barang</th>
                                    <!-- Updated sorting UI for Mitra column to match returns page style -->
                                    <th scope="col" class="d-none d-md-table-cell">
                                        Mitra
                                        <button type="button" class="btn btn-sm btn-light ms-2 sort-toggle"
                                            data-sort-param="mitra"
                                            data-sort-order="<?php echo e(request('sort_mitra', 'none')); ?>"
                                            title="Urutkan berdasarkan mitra">
                                            <i class="bi <?php echo e(request('sort_mitra') == 'asc' ? 'bi-sort-up' : (request('sort_mitra') == 'desc' ? 'bi-sort-down' : 'bi-arrow-down-up')); ?>"></i>
                                        </button>
                                    </th>
                                    <!-- Updated sorting UI for Stok column to match returns page style -->
                                    <th scope="col">
                                        Stok
                                        <button type="button" class="btn btn-sm btn-light ms-2 sort-toggle"
                                            data-sort-param="stok"
                                            data-sort-order="<?php echo e(request('sort_stok', 'none')); ?>"
                                            title="Urutkan berdasarkan stok">
                                            <i class="bi <?php echo e(request('sort_stok') == 'asc' ? 'bi-sort-up' : (request('sort_stok') == 'desc' ? 'bi-sort-down' : 'bi-arrow-down-up')); ?>"></i>
                                        </button>
                                    </th>
                                    <th scope="col" class="d-none d-sm-table-cell">
                                        Status
                                        <!-- Updated status button to use filtering instead of sorting -->
                                        <button type="button" class="btn btn-sm btn-light ms-2 status-filter-toggle"
                                            data-filter-status="<?php echo e(request('filter_status', 'none')); ?>"
                                            title="Filter berdasarkan status stok">
                                            <?php if(request('filter_status') == 'aman'): ?>
                                                <i class="bi bi-check-circle text-success"></i>
                                            <?php elseif(request('filter_status') == 'sedang'): ?>
                                                <i class="bi bi-exclamation-triangle text-warning"></i>
                                            <?php elseif(request('filter_status') == 'rendah'): ?>
                                                <i class="bi bi-x-circle text-danger"></i>
                                            <?php else: ?>
                                                <i class="bi bi-funnel"></i>
                                            <?php endif; ?>
                                        </button>
                                    </th>
                                    <th scope="col" class="d-none d-lg-table-cell">Harga</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $goods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $good): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $stockClass = '';
                                        $stockBadge = '';
                                        $stockText = '';

                                        if ($good->stok <= 5) {
                                            $stockClass = 'stock-low';
                                            $stockBadge = 'badge-low';
                                            $stockText = 'Rendah';
                                        } elseif ($good->stok <= 20) {
                                            $stockClass = 'stock-medium';
                                            $stockBadge = 'badge-medium';
                                            $stockText = 'Sedang';
                                        } else {
                                            $stockClass = 'stock-high';
                                            $stockBadge = 'badge-high';
                                            $stockText = 'Aman';
                                        }
                                    ?>
                                    <tr class="<?php echo e($stockClass); ?>">
                                        <td><?php echo e(($goods->currentPage() - 1) * $goods->perPage() + $loop->iteration); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="mb-0"><?php echo e($good->nama); ?></h6>
                                                    <small class="text-muted"><?php echo e($good->type); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-md-table-cell"><?php echo e($good->category ? $good->category->nama : 'Tidak ada mitra'); ?></td>
                                        <td>
                                            <span class="fw-bold fs-5"><?php echo e($good->stok); ?></span>
                                            <small class="text-muted">unit</small>
                                        </td>
                                        <td class="d-none d-sm-table-cell">
                                            <span class="badge badge-stock <?php echo e($stockBadge); ?>">
                                                <?php echo e($stockText); ?>

                                            </span>
                                        </td>
                                        <td class="d-none d-lg-table-cell">Rp <?php echo e(number_format($good->harga, 0, ',', '.')); ?></td>
                                        <td>
                                            <a href="/dashboard/restock/<?php echo e($good->id); ?>/edit"
                                               class="btn-umkm">
                                                <i class="bi bi-plus-circle"></i>
                                                <span class="d-none d-sm-inline ms-1">Restock</span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($goods->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Added Restock History Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="umkm-card">
                <div class="umkm-card-header">
                    <h3 class="umkm-card-title">
                        <i class="bi bi-clock-history"></i>
                        Riwayat Restock Barang
                    </h3>
                </div>

                <div class="card-body p-3 p-md-4">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">NO</th>
                                    <th scope="col">Nama Barang</th>
                                    <th scope="col">Tanggal Restock</th>
                                    <!-- replaced Stok Sebelum with Mitra Binaan -->
                                    <th scope="col" class="d-none d-md-table-cell">Mitra Binaan</th>
                                    <th scope="col" class="d-none d-md-table-cell">Stok Sesudah</th>
                                    <th scope="col" class="d-none d-lg-table-cell">Administrasi</th>
                                    <th scope="col">Keterangan</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $restockHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $restock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e(($restockHistory->currentPage() - 1) * $restockHistory->perPage() + $loop->iteration); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="mb-0"><?php echo e($restock->good->nama ?? 'Barang Terhapus'); ?></h6>
                                                    <small class="text-muted"><?php echo e($restock->good->category->nama ?? 'Tidak ada mitra'); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold"><?php echo e(\Carbon\Carbon::parse($restock->tgl_restock)->format('d/m/Y')); ?></span>
                                            <br>
                                            <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($restock->created_at)->format('H:i')); ?></small>
                                        </td>
                                        <!-- replaced stok sebelum data with mitra binaan information -->
                                        <td class="d-none d-md-table-cell">
                                            <span class="badge bg-info text-white">
                                                <i class="bi bi-building me-1"></i>
                                                <?php echo e($restock->good->category->nama ?? 'Tidak ada mitra'); ?>

                                            </span>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <span class="text-success fw-bold"><?php echo e($restock->good->stok); ?></span>
                                            <small class="text-muted">unit</small>
                                            <br>
                                            <small class="text-info">(+<?php echo e($restock->qty_restock); ?>)</small>
                                        </td>
                                        <td class="d-none d-lg-table-cell">
                                            <span class="badge bg-primary">
                                                <i class="bi bi-person-check text-success me-1"></i>
                                                <?php echo e($restock->user->nama ?? 'User Terhapus'); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted"><?php echo e($restock->keterangan ?? '-'); ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="/dashboard/restock/<?php echo e($restock->id); ?>/edit-restock"
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="/dashboard/restock/<?php echo e($restock->id); ?>" method="post"
                                                      class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data restock ini? Stok akan dikembalikan.')">
                                                    <?php echo method_field('delete'); ?>
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            Belum ada riwayat restock barang
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- History Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($restockHistory->appends(request()->query())->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Added JavaScript for sorting functionality like in returns page -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- SCRIPT UNTUK SORTING ---
        const sortButtons = document.querySelectorAll('.sort-toggle');
        sortButtons.forEach(button => {
            button.addEventListener('click', function() {
                const sortParam = this.getAttribute('data-sort-param');
                const currentOrder = this.getAttribute('data-sort-order');
                const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.set(sortParam, newOrder);
                window.location.search = urlParams.toString();
            });
        });

        // --- SCRIPT UNTUK FILTERING STATUS ---
        const statusFilterButton = document.querySelector('.status-filter-toggle');
        statusFilterButton.addEventListener('click', function() {
            const currentStatus = this.getAttribute('data-filter-status');
            const newStatus = currentStatus === 'none' ? 'aman' : currentStatus === 'aman' ? 'sedang' : currentStatus === 'sedang' ? 'rendah' : 'none';
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('filter_status', newStatus);
            window.location.search = urlParams.toString();
        });
    });
</script>

<?php echo $__env->make('dashboard.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Repo_Git\Gerai-umkm-mart-finalisasi\resources\views/dashboard/restock/index.blade.php ENDPATH**/ ?>