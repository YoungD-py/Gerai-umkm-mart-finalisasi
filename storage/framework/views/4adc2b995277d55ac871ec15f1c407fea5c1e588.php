<?php $__env->startSection('container'); ?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
        padding: 30px;
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
    
    .form-control:read-only {
        background-color: #e9ecef;
        opacity: 1;
    }

    .form-control:focus, .form-select:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        background: white;
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
    }
    
    .btn-secondary-umkm {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        border: none;
        border-radius: 15px;
        padding: 12px 25px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
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
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .page-title p {
        font-size: 1.1rem;
        opacity: 0.9;
    }
    
    /* Select2 Customization */
    .select2-container--default .select2-selection--single {
        height: calc(2.25rem + 20px);
        padding: 12px 20px;
        border-radius: 15px;
        border: 2px solid #e9ecef;
        background: rgba(255,255,255,0.9);
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.5;
        padding-left: 0;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(2.25rem + 18px);
        right: 15px;
    }
    .select2-container--open .select2-dropdown--below {
        border-radius: 15px;
        border: 2px solid #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        overflow: hidden;
    }
</style>

<div class="container-fluid py-4">
    <div class="page-title">
        <h1>➕ TAMBAH PESANAN</h1>
        <p>Tambah item baru ke dalam nota nomor <strong><?php echo e($no_nota); ?></strong></p>
    </div>

    <?php if(session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 15px; border: none;">
            <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="umkm-card">
                <div class="umkm-card-header">
                    <h3 class="umkm-card-title">
                        <i class="bi bi-cart-plus"></i>
                        Formulir Tambah Pesanan
                    </h3>
                </div>

                <div class="umkm-card-body">
                    <form method="post" action="/dashboard/orders/store">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="no_nota" class="form-label">No. Nota</label>
                            <input type="text" class="form-control" name="no_nota" required value="<?php echo e($no_nota); ?>" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="goods" class="form-label">Pilih Barang</label>
                            <select class="form-select" id="goods" name="good_id" onchange="Subtotal()">
                                <option value="" disabled selected>-- Cari dan Pilih Barang --</option>
                                <?php $__currentLoopData = $goods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $good): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option price="<?php echo e($good->harga); ?>" value="<?php echo e($good->id); ?>">
                                        <?php echo e($good->nama); ?> (Stok: <?php echo e($good->stok); ?> | Harga: Rp <?php echo e(number_format($good->harga, 0, ',', '.')); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="qty" class="form-label">Jumlah Pesan</label>
                                <input type="number" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"
                                       class="form-control" name="qty" id="qty" required placeholder="Masukkan Jumlah..." oninput="Subtotal()">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="subtotal" class="form-label">Subtotal</label>
                                <input type="text" class="form-control" id="subtotal_display" readonly placeholder="Rp 0">
                                <input type="hidden" id="subtotal" name="subtotal" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-4">
                            <!-- <a href="/dashboard/orders?no_nota=<?php echo e($no_nota); ?>" class="btn-secondary-umkm">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a> -->
                            <button class="btn-umkm" type="submit">
                                <i class="bi bi-plus-lg"></i> Tambahkan Pesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#goods').select2({
            placeholder: "-- Cari dan Pilih Barang --",
            allowClear: true
        });
    });

    function Subtotal() {
        var databarang = document.querySelector("#goods");
        var selectedOption = databarang.options[databarang.selectedIndex];
        
        if (!selectedOption || !selectedOption.hasAttribute('price')) {
            document.getElementById("subtotal_display").value = "Rp 0";
            document.getElementById("subtotal").value = "";
            return;
        }

        var harga = selectedOption.getAttribute('price');
        var qty = document.querySelector("#qty").value;
        var hasil = harga * qty;

        document.getElementById("subtotal").value = hasil;
        document.getElementById("subtotal_display").value = 'Rp ' + new Intl.NumberFormat('id-ID').format(hasil);
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\SEMESTER 6\KERJA PRAKTEK PELINDO\project umkm\NEW\kasirku-main\resources\views/dashboard/orders/create.blade.php ENDPATH**/ ?>