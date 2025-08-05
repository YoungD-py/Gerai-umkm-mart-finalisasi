<?php $__env->startSection('container'); ?>
<style>
    /* --- CSS Styles adapted for Edit Transaction page --- */
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

    .umkm-card-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .umkm-card-body {
        padding: 1.5rem; /* [RESPONSIVE] Mengurangi padding di layar kecil */
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border-radius: 15px;
        border: 2px solid #e9ecef;
        padding: 12px 20px;
        transition: all 0.3s ease;
        background: rgba(255,255,255,0.9);
        font-size: 1rem;
    }

    .form-control:read-only, .form-select:disabled {
        background-color: #e9ecef;
        opacity: 1;
        cursor: not-allowed;
    }

    .form-control:focus, .form-select:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        background: white;
    }

    .btn-umkm, .btn-secondary-umkm {
        border-radius: 15px;
        padding: 12px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center; /* [RESPONSIVE] Center content inside button */
        gap: 8px;
    }

    .btn-umkm {
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-umkm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        color: white;
    }

    .btn-umkm:disabled {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        opacity: 0.65;
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }
    
    .btn-secondary-umkm {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        border: none;
        color: white;
    }

    .btn-secondary-umkm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);
        color: white;
    }

    .page-title {
        color: white;
        text-align: center;
        margin-bottom: 30px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .page-title h1 {
        font-size: 2rem; /* [RESPONSIVE] Menyesuaikan ukuran font */
        font-weight: 800;
        margin-bottom: 10px;
    }

    .page-title p {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .tax-info {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 5px;
    }

    @media (min-width: 768px) {
        .page-title h1 {
            font-size: 2.5rem;
        }
        .umkm-card-body {
            padding: 30px;
        }
    }
</style>

<div class="container-fluid py-4">
    <div class="page-title">
        <h1>✏️ EDIT TRANSAKSI</h1>
        <p>Perbarui status pembayaran dan detail transaksi</p>
    </div>

    <div class="row justify-content-center">
        
        <div class="col-xl-8 col-lg-10 col-md-12">
            <?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 15px;">
                    <strong>Terjadi Kesalahan!</strong>
                    <ul class="mb-0 mt-2">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="umkm-card">
                <div class="umkm-card-header">
                    <h3 class="umkm-card-title">
                        <i class="bi bi-pencil-square"></i>
                        Form Edit Transaksi
                    </h3>
                </div>

                <div class="umkm-card-body">
                    <form method="post" action="/dashboard/transactions/<?php echo e($transaction->id); ?>">
                        <?php echo method_field('put'); ?>
                        <?php echo csrf_field(); ?>
                        
                        <?php
                            $initialBayar = $transaction->bayar;
                            if (strtolower(trim($transaction->status)) !== 'lunas' && $transaction->bayar == 0) {
                                $initialBayar = $transaction->total_harga;
                            }
                            $defaultBayar = old('bayar', $initialBayar);
                        ?>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="no_nota" class="form-label">No. Nota</label>
                                <input type="text" class="form-control" id="no_nota" value="<?php echo e($transaction->no_nota); ?>" readonly>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="tgl_transaksi" class="form-label">Tanggal Transaksi</label>
                                <input type="text" class="form-control" id="tgl_transaksi" value="<?php echo e($transaction->created_at->format('d M Y, H:i')); ?>" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Metode Pembayaran</label>
                            <select class="form-select" id="payment_method" disabled>
                                <option value="">-- Tidak Ada --</option>
                                <option value="CASH" <?php echo e((isset($transaction->metode_pembayaran) && $transaction->metode_pembayaran == 'CASH') ? 'selected' : ''); ?>>CASH</option>
                                <option value="QRIS" <?php echo e((isset($transaction->metode_pembayaran) && $transaction->metode_pembayaran == 'QRIS') ? 'selected' : ''); ?>>QRIS</option>
                                <option value="P-Eats" <?php echo e((isset($transaction->metode_pembayaran) && $transaction->metode_pembayaran == 'P-Eats') ? 'selected' : ''); ?>>P-Eats</option>
                                <option value="TRANSFER" <?php echo e((isset($transaction->metode_pembayaran) && $transaction->metode_pembayaran == 'TRANSFER') ? 'selected' : ''); ?>>TRANSFER</option>
                            </select>
                            <small class="text-muted d-block mt-1">
                                <i class="bi bi-info-circle-fill"></i> Metode pembayaran tidak dapat diubah.
                            </small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Nama Administrator</label>
                            <input type="hidden" name="user_id" value="<?php echo e($transaction->user_id); ?>">
                            <select class="form-select" id="user_id" disabled>
                                <option value="">-- Pilih Administrator --</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" <?php echo e((old('user_id', $transaction->user_id) == $user->id) ? 'selected' : ''); ?>>
                                        <?php echo e($user->nama); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <small class="text-muted d-block mt-1">
                                <i class="bi bi-info-circle-fill"></i> Administrator tidak dapat diubah.
                            </small>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="total" class="form-label">Total Harga</label>
                                <input type="text" class="form-control" id="total" value="Rp <?php echo e(number_format($transaction->total_harga, 0, ',', '.')); ?>" readonly>
                                <input type="hidden" id="total_numeric" value="<?php echo e($transaction->total_harga); ?>">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="status_display" class="form-label">Status Pembayaran</label>
                                <input type="hidden" name="status" id="status_hidden" value="<?php echo e($transaction->status); ?>">
                                <select class="form-select" id="status_display" disabled>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="LUNAS" <?php echo e((old('status', $transaction->status) == 'LUNAS') ? 'selected' : ''); ?>>LUNAS</option>
                                    <option value="BELUM BAYAR" <?php echo e((old('status', $transaction->status) == 'BELUM BAYAR') ? 'selected' : ''); ?>>BELUM LUNAS</option>
                                </select>
                                <small class="text-muted d-block mt-1">
                                    <i class="bi bi-info-circle-fill"></i> Status diperbarui otomatis.
                                </small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="bayar_display" class="form-label">Jumlah Bayar</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['bayar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="bayar_display"
                                       placeholder="Masukkan Jumlah Bayar..." required>
                                <input type="hidden" name="bayar" id="bayar" value="<?php echo e($defaultBayar); ?>">
                                <?php $__errorArgs = ['bayar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="kembalian" class="form-label">Kembalian / Sisa</label>
                                <input type="text" class="form-control" id="kembalian_display" readonly>
                                <input type="hidden" id="kembalian" name="kembalian" value="<?php echo e(old('kembalian', $transaction->kembalian)); ?>">
                            </div>
                        </div>

                        <div class="d-grid d-sm-flex justify-content-sm-between pt-4 gap-2">
                            <a href="/dashboard/transactions" class="btn btn-secondary-umkm">
                                <i class="bi bi-arrow-left"></i>
                                Kembali
                            </a>
                            <button class="btn btn-umkm" type="submit">
                                <i class="bi bi-save"></i>
                                Ubah Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function formatNumber(n) {
        return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function unformatNumber(s) {
        return s.toString().replace(/[^0-9]/g, "");
    }

    function calculateChange() {
        var bayar = parseFloat(document.getElementById("bayar").value) || 0;
        var total = parseFloat(document.getElementById("total_numeric").value) || 0;
        var kembalian = bayar - total;

        document.getElementById("kembalian").value = kembalian;
        document.getElementById("kembalian_display").value = 'Rp ' + formatNumber(kembalian);
        
        var statusDisplaySelect = document.getElementById('status_display');
        var statusHiddenInput = document.getElementById('status_hidden');
        
        if (bayar >= total && total > 0) {
            statusDisplaySelect.value = 'LUNAS';
            statusHiddenInput.value = 'LUNAS';
        } else {
            statusDisplaySelect.value = 'BELUM BAYAR';
            statusHiddenInput.value = 'BELUM BAYAR';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const bayarDisplayInput = document.getElementById('bayar_display');
        const bayarHiddenInput = document.getElementById('bayar');

        const handleBayarInput = (event) => {
            let rawValue = unformatNumber(bayarDisplayInput.value);
            bayarHiddenInput.value = rawValue;
            
            let formattedValue = formatNumber(rawValue);
            if (bayarDisplayInput.value !== formattedValue) {
                bayarDisplayInput.value = formattedValue;
            }
            
            calculateChange();
        };
        
        bayarDisplayInput.value = formatNumber(bayarHiddenInput.value);
        bayarDisplayInput.addEventListener('input', handleBayarInput);
        calculateChange();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\SEMESTER 6\KERJA PRAKTEK PELINDO\project umkm\NEW\kasirku-main\resources\views/dashboard/transactions/edit.blade.php ENDPATH**/ ?>