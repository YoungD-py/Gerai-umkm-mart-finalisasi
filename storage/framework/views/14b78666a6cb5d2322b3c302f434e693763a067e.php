<?php $__env->startSection('container'); ?>
<style>
    /* --- CSS Styles adapted for Cashier Dashboard --- */
    .cashier-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.9));
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.2);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .cashier-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .cashier-card-header {
        /* Different color for Cashier page */
        background: linear-gradient(135deg, #007bff, #0056b3); 
        color: white;
        padding: 20px;
        border-radius: 20px 20px 0 0;
        position: relative;
        overflow: hidden;
    }

    .cashier-card-header::before {
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

    .cashier-card-header:hover::before {
        right: -30%;
    }

    .cashier-card-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .cashier-card-body {
        padding: 25px;
        overflow: hidden;
    }

    .btn-cashier {
        /* Different color for Cashier page */
        background: linear-gradient(135deg, #007bff, #0056b3);
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

    .btn-cashier:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
        color: white;
        text-decoration: none;
    }

    .btn-cashier-sm {
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
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        background: white;
    }

    .table-cashier {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    .table-cashier thead th {
        /* Different color for Cashier page */
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        padding: 15px 12px;
        white-space: nowrap; 
    }

    .table-cashier tbody td {
        padding: 15px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f8f9fa;
    }

    .table-cashier tbody tr:last-child td {
        border-bottom: none;
    }

    .table-cashier tbody tr:hover {
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

    .pagination-wrapper .pagination {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .pagination-wrapper .page-link {
        border: none;
        padding: 12px 16px;
        color: #007bff;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .pagination-wrapper .page-link:hover {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        transform: translateY(-1px);
    }

    .pagination-wrapper .page-item.active .page-link {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border-color: #007bff;
    }
</style>

<div class="container-fluid py-4">
    <div class="page-title">
        <h1><i class="bi bi-cash-register"></i> SISTEM KASIR</h1>
        <p>Lakukan transaksi penjualan dan lihat riwayat transaksi hari ini</p>
    </div>

    <?php if(session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 15px; border: none;">
            <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="cashier-card">
        <div class="cashier-card-header">
            <div class="d-flex justify-content-between align-items-center w-100">
                <h3 class="cashier-card-title">
                    <i class="bi bi-receipt"></i>
                    Transaksi Hari Ini
                </h3>
                <a href="/dashboard/cashier/quick-transaction" class="btn-cashier btn-cashier-sm">
                    <i class="bi bi-plus-circle"></i>
                    Tambah Transaksi
                </a>
            </div>
        </div>

        <div class="cashier-card-body">
            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-cashier">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No. Nota</th>
                            <th>Waktu Transaksi</th>
                            <th>Petugas</th>
                            <th>Metode Bayar</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e($loop->iteration); ?></strong></td>
                            <td>
                                <i class="bi bi-hash text-primary"></i>
                                <?php echo e($transaction->no_nota); ?>

                            </td>
                            <td style="white-space:nowrap;">
                                <i class="bi bi-clock text-muted me-1"></i>
                                <?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i:s')); ?>

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
                            <td class="text-center">
                                
                                <form method="post" action="/dashboard/cashiers/nota" class="d-inline" onsubmit="handleFormSubmit(this)">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="no_nota" value="<?php echo e($transaction->no_nota); ?>">
                                    <button class="btn btn-primary btn-sm" type="submit" title="Unduh Nota">
                                        <i class="bi bi-download"></i> Unduh Nota
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-cart-x display-4 d-block mb-3"></i>
                                    <h5>Belum ada transaksi hari ini</h5>
                                    <p>Silakan buat transaksi baru untuk memulai</p>
                                    <a href="/dashboard/cashier/quick-transaction" class="btn-cashier">
                                        <i class="bi bi-plus-circle"></i>
                                        Buat Transaksi Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    function handleFormSubmit(form) {
        const button = form.querySelector('button[type="submit"]');
        if (button) {
            const originalButtonHTML = button.innerHTML;

            button.disabled = true;
            button.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Loading...
            `;

            // Mengembalikan tombol ke keadaan semula setelah 3 detik
            setTimeout(function() {
                button.disabled = false;
                button.innerHTML = originalButtonHTML;
            }, 3000);
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\SEMESTER 6\KERJA PRAKTEK PELINDO\project umkm\NEW\kasirku-main\resources\views/dashboard/cashiers/index.blade.php ENDPATH**/ ?>