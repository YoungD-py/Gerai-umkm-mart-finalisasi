<?php $__env->startSection('container'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<style>
    body {
        background-color: #D3D3D3;
    }
    .search-results {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #fff;
        position: absolute;
        z-index: 1000;
        width: calc(100% - 30px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .search-results-item {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
    }
    .search-results-item:last-child {
        border-bottom: none;
    }
    .search-results-item:hover {
        background-color: #f8f9fa;
    }
    .search-results-item .product-name {
        font-weight: bold;
        color: #333;
    }
    .search-results-item .product-details {
        font-size: 0.85em;
        color: #666;
    }
    /* Style untuk area kamera */
    #camera-scanner {
        width: 100%;
        max-width: 400px;
        height: 300px;
        margin: 15px auto;
        border: 3px solid #28a745;
        border-radius: 10px;
        background: #000;
        position: relative;
        overflow: hidden;
    }
    
    #camera-scanner video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .scanner-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 200px;
        height: 100px;
        border: 2px solid #fff;
        border-radius: 10px;
        box-shadow: 0 0 0 9999px rgba(0,0,0,0.3);
    }
    
    .scanner-line {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, transparent, #00ff00, transparent);
        animation: scan 2s linear infinite;
    }
    
    @keyframes scan {
        0% { top: 0; }
        100% { top: 100%; }
    }
</style>

<div class="d-flex justify-content-between flex-wrap align-items-center py-3 px-3 mb-3 border-bottom" style="background: linear-gradient(90deg, #4e54c8, #8f94fb); border-radius: 10px;">
    <h1 class="h4 text-light mb-2 mb-md-0">
        <i class="bi bi-cart-plus-fill me-2"></i> Buat Pesanan - Nota:
        <span class="text-warning fw-semibold"><?php echo e($no_nota); ?></span>
    </h1>
</div>

<div class="container-fluid">
    <?php if(session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div id="dynamic-alerts"></div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4" style="background: white;">
                <div class="card-header bg-success text-white py-3">
                    <h4 class="mb-0 fw-bold">
                        <i class="bi bi-upc-scan fs-3"></i> INPUT BARCODE
                    </h4>
                    <small class="opacity-75">Scan atau ketik barcode produk</small>
                </div>
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="barcode-input" class="form-label fs-5 fw-semibold text-dark">
                                    <i class="bi bi-keyboard text-success"></i> Masukkan Barcode
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-success text-white border-success">
                                        <i class="bi bi-upc-scan"></i>
                                    </span>
                                    <input type="text" class="form-control form-control-lg border-success"
                                           id="barcode-input"
                                           placeholder="Ketik atau scan barcode di sini..."
                                           autocomplete="off"
                                           autofocus
                                           style="font-size: 1.2rem; font-weight: 500;">
                                    <button class="btn btn-success btn-lg px-4" type="button" id="search-barcode">
                                        <i class="bi bi-search"></i> CARI
                                    </button>
                                </div>
                                <div class="form-text fs-6 mt-2">
                                    <i class="bi bi-info-circle text-primary"></i>
                                    <strong>Tips:</strong> Tekan Enter setelah mengetik barcode atau klik tombol CARI
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-info border-0 shadow-sm">
                                <h6 class="fw-bold mb-2">
                                    <i class="bi bi-lightbulb text-warning"></i> Cara Menggunakan:
                                </h6>
                                <ul class="mb-0 small lh-lg">
                                    <li><strong>Ketik barcode</strong> dengan teliti</li>
                                    <li><strong>Contoh:</strong> M-IND-123 (format baru)</li>
                                    <li><strong>Tekan Enter</strong> untuk cari cepat</li>
                                    <li><strong>Klik CARI</strong> jika tidak ada keyboard</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="border-top pt-3 mt-3">
                        <button id="start-scan-btn" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-camera-fill"></i> Scan dengan Kamera
                        </button>
                        <div id="camera-scanner" style="display: none;">
                            <div class="scanner-overlay">
                                <div class="scanner-line"></div>
                            </div>
                        </div>
                        <div id="scanner-status" class="text-center mt-2" style="display: none;">
                            <small class="text-muted">Arahkan kamera ke barcode...</small>
                        </div>
                    </div>
                    
                    <div id="barcode-result" class="alert alert-success border-0 shadow-sm mt-4" style="display: none;">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="fw-bold text-success mb-3">
                                    <i class="bi bi-check-circle-fill"></i> Produk Ditemukan!
                                </h5>
                                <div class="product-info bg-light p-3 rounded">
                                    <div class="row">
                                        <div class="col-6">
                                            <p class="mb-2"><strong>Kode:</strong> <span id="detected-barcode" class="badge bg-secondary fs-6"></span></p>
                                            <p class="mb-2"><strong>Nama Produk:</strong> <span id="product-name" class="fw-bold text-dark fs-5"></span></p>
                                        </div>
                                        <div class="col-6">
                                            <p class="mb-2"><strong>Harga:</strong> <span id="product-price" class="fw-bold text-success fs-4"></span></p>
                                            <p class="mb-2"><strong>Stok Tersedia:</strong> <span id="product-stock" class="badge bg-info fs-6"></span></p>
                                        </div>
                                    </div>
                                    <div id="wholesale-info" class="mt-3 p-2 bg-warning bg-opacity-10 rounded border-start border-warning border-3" style="display: none;">
                                        <h6 class="text-warning mb-2">
                                            <i class="bi bi-cart-plus"></i> Harga Grosir Tersedia!
                                        </h6>
                                        <div class="row">
                                            <div class="col-6">
                                                <small><strong>Min. Pembelian:</strong> <span id="min-wholesale-qty"></span> unit</small><br>
                                                <small><strong>Harga Grosir:</strong> <span id="wholesale-price" class="text-success fw-bold"></span></small>
                                            </div>
                                            <div class="col-6">
                                                <small><strong>Hemat per unit:</strong> <span id="savings-per-unit" class="text-success fw-bold"></span></small><br>
                                                <small><strong>Diskon:</strong> <span id="discount-percent" class="text-success fw-bold"></span></small>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tebus-murah-info" class="mt-3 p-2 bg-danger bg-opacity-10 rounded border-start border-danger border-3" style="display: none;">
                                        <h6 class="text-danger mb-2">
                                            <i class="bi bi-percent"></i> Harga Tebus Murah Tersedia!
                                        </h6>
                                        <div class="row">
                                            <div class="col-6">
                                                <small><strong>Min. Total Transaksi:</strong> <span id="min-tebus-total" class="text-danger fw-bold"></span></small><br>
                                                <small><strong>Harga Tebus Murah:</strong> <span id="tebus-price" class="text-danger fw-bold"></span></small>
                                            </div>
                                            <div class="col-6">
                                                <small><strong>Hemat per unit:</strong> <span id="tebus-savings-per-unit" class="text-danger fw-bold"></span></small><br>
                                                <small><strong>Diskon:</strong> <span id="tebus-discount-percent" class="text-danger fw-bold"></span></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-grid gap-2">
                                    <label for="scan-qty" class="form-label fw-semibold fs-5">Jumlah:</label>
                                    <input type="number" class="form-control form-control-lg text-center fw-bold"
                                           id="scan-qty" value="1" min="1" max="999"
                                           style="font-size: 1.5rem;" onchange="updateBarcodePrice()">
                                    <div id="price-info" class="text-center mb-2">
                                        <div class="fw-bold fs-5">Total: <span id="total-price" class="text-success">Rp 0</span></div>
                                        <div id="price-type" class="small text-muted"></div>
                                    </div>
                                    <button type="button" id="add-scanned-item" class="btn btn-success btn-lg py-3">
                                        <i class="bi bi-plus-circle"></i> TAMBAH KE PESANAN
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0" style="background: white;">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0 fw-bold">
                        <i class="bi bi-pencil-square fs-3"></i> INPUT MANUAL
                    </h4>
                    <small class="opacity-75">Pilih produk dari daftar jika barcode tidak tersedia</small>
                </div>
                <div class="card-body p-4">
                    <form id="manual-form">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="no_nota" value="<?php echo e($no_nota); ?>">
                        <input type="hidden" name="good_id" id="manual-good-id">

                        <div class="mb-4 position-relative">
                            <label for="product-search-input" class="form-label fs-5 fw-semibold text-dark">
                                <i class="bi bi-box-seam text-primary"></i> Cari Produk
                            </label>
                            <input type="text" class="form-control form-control-lg border-primary"
                                   id="product-search-input"
                                   placeholder="Ketik nama atau barcode produk..."
                                   autocomplete="off"
                                   style="font-size: 1.1rem;">
                            <div id="search-results" class="search-results" style="display: none;"></div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="qty" class="form-label fs-5 fw-semibold text-dark">
                                    <i class="bi bi-123 text-primary"></i> Jumlah
                                </label>
                                <input type="number" class="form-control form-control-lg border-primary text-center fw-bold"
                                       id="qty" name="qty" min="1" value="1"
                                       onchange="calculateSubtotal()"
                                       required
                                       style="font-size: 1.3rem;">
                            </div>
                            <div class="col-md-6">
                                <label for="subtotal" class="form-label fs-5 fw-semibold text-dark">
                                    <i class="bi bi-calculator text-primary"></i> Subtotal
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-primary text-white border-primary fw-bold">Rp</span>
                                    <input type="number" class="form-control border-primary text-end fw-bold"
                                           id="subtotal" name="subtotal" readonly
                                           style="font-size: 1.3rem; background-color: #f8f9fa;">
                                </div>
                            </div>
                        </div>

                        <div id="manual-wholesale-info" class="mt-3 p-3 bg-warning bg-opacity-10 rounded border-start border-warning border-3" style="display: none;">
                            <h6 class="text-warning mb-2">
                                <i class="bi bi-cart-plus"></i> Harga Grosir Aktif!
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <small><strong>Harga per unit:</strong> <span id="manual-unit-price" class="text-success fw-bold"></span></small><br>
                                    <small><strong>Total hemat:</strong> <span id="manual-total-savings" class="text-success fw-bold"></span></small>
                                </div>
                                <div class="col-md-6">
                                    <small><strong>Diskon:</strong> <span id="manual-discount-percent" class="text-success fw-bold"></span></small><br>
                                    <small class="text-muted">Pembelian <span id="manual-wholesale-min-qty"></span>+ unit</small>
                                </div>
                            </div>
                        </div>

                        <div id="manual-tebus-murah-info" class="mt-3 p-3 bg-danger bg-opacity-10 rounded border-start border-danger border-3" style="display: none;">
                            <h6 class="text-danger mb-2">
                                <i class="bi bi-percent"></i> Harga Tebus Murah Aktif!
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <small><strong>Harga per unit:</strong> <span id="manual-tebus-unit-price" class="text-danger fw-bold"></span></small><br>
                                    <small><strong>Total hemat:</strong> <span id="manual-tebus-total-savings" class="text-danger fw-bold"></span></small>
                                </div>
                                <div class="col-md-6">
                                    <small><strong>Diskon:</strong> <span id="manual-tebus-discount-percent" class="text-danger fw-bold"></span></small><br>
                                    <small class="text-muted">Total transaksi mencapai syarat</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg py-3" id="manual-submit-btn" disabled>
                                <i class="bi bi-plus-circle"></i> TAMBAH KE PESANAN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0" id="orders-card" style="background: white;">
                <div class="card-header bg-warning text-dark py-3">
                    <h4 class="mb-0 fw-bold">
                        <i class="bi bi-cart3 fs-3"></i> PESANAN SAAT INI
                        <span class="badge bg-dark ms-2 fs-6" id="order-count"><?php echo e($orders->count()); ?></span>
                    </h4>
                    <small>Daftar produk yang akan dibeli</small>
                </div>
                <div class="card-body p-0" id="orders-content">
                    <?php if($orders->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="fw-bold py-3 px-3">Produk</th>
                                        <th class="fw-bold py-3 text-center">Qty</th>
                                        <th class="fw-bold py-3 text-end px-3">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="orders-tbody">
                                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="py-3 px-3">
                                            <div class="fw-semibold"><?php echo e($order->good->nama); ?></div>
                                            <small class="text-muted">@ Rp <?php echo e(number_format($order->price, 0, ',', '.')); ?></small>
                                            <?php if($order->good->is_grosir_active && $order->qty >= $order->good->min_qty_grosir): ?>
                                                <br><small class="text-success fw-bold">
                                                    <i class="bi bi-cart-plus"></i> Harga Grosir
                                                </small>
                                            <?php endif; ?>
                                            <?php if($order->good->is_tebus_murah_active && $orders->sum('subtotal') >= $order->good->min_total_tebus_murah): ?>
                                                <br><small class="text-danger fw-bold">
                                                    <i class="bi bi-percent"></i> Harga Tebus Murah
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 text-center">
                                            <span class="badge bg-secondary fs-6"><?php echo e($order->qty); ?></span>
                                        </td>
                                        <td class="py-3 text-end px-3 fw-bold text-success">
                                            Rp <?php echo e(number_format($order->subtotal, 0, ',', '.')); ?>

                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="p-4 bg-light border-top">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0 fw-bold">TOTAL BELANJA:</h5>
                                <h4 class="mb-0 fw-bold text-success" id="total-amount">
                                    Rp <?php echo e(number_format($orders->sum('subtotal'), 0, ',', '.')); ?>

                                </h4>
                            </div>

                            <?php
                                $totalTransaction = $orders->sum('subtotal');
                                $tebusMusahProducts = $goods->filter(function($good) use ($totalTransaction) {
                                    return $good->is_tebus_murah_active && $totalTransaction >= $good->min_total_tebus_murah;
                                });
                            ?>

                            <?php if($tebusMusahProducts->count() > 0): ?>
                                <div class="alert alert-danger border-0 shadow-sm mb-3">
                                    <h6 class="fw-bold text-danger mb-2">
                                        <i class="bi bi-percent"></i> TEBUS MURAH AKTIF!
                                    </h6>
                                    <small class="text-muted">
                                        <?php echo e($tebusMusahProducts->count()); ?> produk memenuhi syarat tebus murah dengan total transaksi Rp <?php echo e(number_format($totalTransaction, 0, ',', '.')); ?>

                                    </small>
                                </div>
                            <?php endif; ?>

                            <form method="post" action="/dashboard/cashiers/checkout">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="no_nota" value="<?php echo e($no_nota); ?>">
                                <input type="hidden" name="total_harga" value="<?php echo e($orders->sum('subtotal')); ?>" id="checkout-total">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success btn-lg py-3 fw-bold">
                                        <i class="bi bi-credit-card"></i> LANJUT KE PEMBAYARAN
                                    </button>
                                </div>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5" id="empty-orders">
                            <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
                            <h5 class="mt-3 text-muted">Belum Ada Pesanan</h5>
                            <p class="text-muted">Scan barcode atau pilih produk untuk memulai</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-4" style="background: white;">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-lightning"></i> AKSI CEPAT
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary" onclick="clearBarcodeInput()">
                            <i class="bi bi-eraser"></i> Bersihkan Input
                        </button>
                        <button type="button" class="btn btn-outline-warning" onclick="location.reload()">
                            <i class="bi bi-arrow-clockwise"></i> Refresh Halaman
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Load Html5-QrCode Library -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
// Global Variables
let currentProduct = null;
let currentTransactionTotal = <?php echo e($orders->sum('subtotal')); ?>;
let manualSelectedProduct = null;
let html5QrCode = null;
let isScannerRunning = false;

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing...');
    document.getElementById('barcode-input').focus();
    calculateSubtotal();
    initializeCameraScanner();
});

// ======================================================
// KAMERA SCANNER IMPLEMENTATION - COMPLETELY REWRITTEN
// ======================================================
function initializeCameraScanner() {
    console.log('Initializing camera scanner...');
    const startScanBtn = document.getElementById('start-scan-btn');
    
    startScanBtn.addEventListener('click', function() {
        console.log('Scan button clicked, scanner running:', isScannerRunning);
        if (isScannerRunning) {
            stopScanner();
        } else {
            startScanner();
        }
    });
}

function startScanner() {
    console.log('Starting scanner...');
    
    const startScanBtn = document.getElementById('start-scan-btn');
    const cameraScannerDiv = document.getElementById('camera-scanner');
    const scannerStatus = document.getElementById('scanner-status');

    // Show camera area
    cameraScannerDiv.style.display = 'block';
    scannerStatus.style.display = 'block';
    
    // Change button to stop
    startScanBtn.innerHTML = '<i class="bi bi-stop-circle-fill"></i> Hentikan Scanner';
    startScanBtn.classList.remove('btn-primary');
    startScanBtn.classList.add('btn-danger');

    // Initialize Html5Qrcode
    html5QrCode = new Html5Qrcode("camera-scanner");

    // Success callback
    const onScanSuccess = (decodedText, decodedResult) => {
        console.log(`Code matched = ${decodedText}`, decodedResult);
        
        // Process the scanned barcode
        processBarcode(decodedText);
        
        // Fill the input
        document.getElementById('barcode-input').value = decodedText;
        
        // Stop scanner
        stopScanner();
        
        // Show success message
        showAlert('success', `📷 Barcode berhasil di-scan: ${decodedText}`);
    };

    // Error callback (ignore errors)
    const onScanFailure = (error) => {
        // console.warn(`Code scan error = ${error}`);
    };

    // Configuration
    const config = {
        fps: 10,
        qrbox: { width: 250, height: 150 },
        aspectRatio: 1.0
    };

    // Start scanning
    html5QrCode.start(
        { facingMode: "environment" }, // Use back camera
        config,
        onScanSuccess,
        onScanFailure
    ).then(() => {
        console.log('Scanner started successfully');
        isScannerRunning = true;
        showAlert('info', '📷 Scanner aktif! Arahkan kamera ke barcode.');
    }).catch(err => {
        console.error('Error starting scanner with back camera:', err);
        
        // Try front camera if back camera fails
        html5QrCode.start(
            { facingMode: "user" }, // Use front camera
            config,
            onScanSuccess,
            onScanFailure
        ).then(() => {
            console.log('Front camera started successfully');
            isScannerRunning = true;
            showAlert('info', '📷 Scanner aktif (kamera depan)! Arahkan ke barcode.');
        }).catch(err2 => {
            console.error('Error starting scanner with front camera:', err2);
            showAlert('danger', '❌ Gagal mengakses kamera. Pastikan browser memiliki izin akses kamera dan menggunakan HTTPS.');
            resetScannerUI();
        });
    });
}

function stopScanner() {
    console.log('Stopping scanner...');
    
    if (html5QrCode && isScannerRunning) {
        html5QrCode.stop().then(() => {
            console.log('Scanner stopped successfully');
            html5QrCode.clear();
            resetScannerUI();
        }).catch(err => {
            console.error('Error stopping scanner:', err);
            resetScannerUI();
        });
    } else {
        resetScannerUI();
    }
}

function resetScannerUI() {
    console.log('Resetting scanner UI...');
    
    const startScanBtn = document.getElementById('start-scan-btn');
    const cameraScannerDiv = document.getElementById('camera-scanner');
    const scannerStatus = document.getElementById('scanner-status');
    
    isScannerRunning = false;
    cameraScannerDiv.style.display = 'none';
    scannerStatus.style.display = 'none';
    
    startScanBtn.innerHTML = '<i class="bi bi-camera-fill"></i> Scan dengan Kamera';
    startScanBtn.classList.remove('btn-danger');
    startScanBtn.classList.add('btn-primary');
}

// ======================================================
// EXISTING FUNCTIONS (UNCHANGED)
// ======================================================

function calculateSubtotal() {
    const qtyInput = document.getElementById('qty');
    const subtotalInput = document.getElementById('subtotal');
    const wholesaleInfo = document.getElementById('manual-wholesale-info');
    const tebusMusahInfo = document.getElementById('manual-tebus-murah-info');
    const manualSubmitBtn = document.getElementById('manual-submit-btn');

    wholesaleInfo.style.display = 'none';
    tebusMusahInfo.style.display = 'none';
    subtotalInput.value = '';
    manualSubmitBtn.disabled = true;

    if (!manualSelectedProduct || !qtyInput.value) {
        return;
    }

    const product = manualSelectedProduct;
    const retailPrice = product.harga || 0;
    const qty = parseInt(qtyInput.value) || 0;
    const stock = product.stok || 0;
    
    const isWholesaleActive = product.is_grosir_active;
    const wholesaleMin = product.min_qty_grosir || 0;
    const wholesalePrice = product.harga_grosir || 0;
    
    const isTebusMusahActive = product.is_tebus_murah_active;
    const tebusMusahMinTotal = product.min_total_tebus_murah || 0;
    const tebusMusahPrice = product.harga_tebus_murah || 0;

    if (qty > stock) {
        showAlert('warning', `⚠️ Jumlah melebihi stok yang tersedia (${stock})`);
        qtyInput.value = stock;
        return;
    }

    let unitPrice = retailPrice;
    let isWholesale = false;
    let isTebusMusah = false;

    if (isTebusMusahActive && currentTransactionTotal >= tebusMusahMinTotal && tebusMusahPrice > 0) {
        unitPrice = tebusMusahPrice;
        isTebusMusah = true;
    }
    else if (isWholesaleActive && qty >= wholesaleMin && wholesalePrice > 0) {
        unitPrice = wholesalePrice;
        isWholesale = true;
    }

    const subtotal = unitPrice * qty;
    subtotalInput.value = subtotal;
    manualSubmitBtn.disabled = false;

    if (isWholesale && !isTebusMusah) {
        const savings = (retailPrice - wholesalePrice) * qty;
        const discountPercent = ((retailPrice - wholesalePrice) / retailPrice * 100).toFixed(1);

        document.getElementById('manual-unit-price').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(wholesalePrice);
        document.getElementById('manual-total-savings').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(savings);
        document.getElementById('manual-discount-percent').textContent = discountPercent + '%';
        document.getElementById('manual-wholesale-min-qty').textContent = wholesaleMin;

        wholesaleInfo.style.display = 'block';
    } else {
        wholesaleInfo.style.display = 'none';
    }

    if (isTebusMusah) {
        const savings = (retailPrice - tebusMusahPrice) * qty;
        const discountPercent = ((retailPrice - tebusMusahPrice) / retailPrice * 100).toFixed(1);

        document.getElementById('manual-tebus-unit-price').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(tebusMusahPrice);
        document.getElementById('manual-tebus-total-savings').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(savings);
        document.getElementById('manual-tebus-discount-percent').textContent = discountPercent + '%';

        tebusMusahInfo.style.display = 'block';
    } else {
        tebusMusahInfo.style.display = 'none';
    }
}

function processBarcode(barcode) {
    console.log('Processing barcode:', barcode);
    barcode = barcode.trim();
    
    fetch(`/dashboard/cashier/search-goods?query=${encodeURIComponent(barcode)}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(products => {
        console.log('Search results:', products);
        let productData = null;
        productData = products.find(p => p.barcode === barcode);
        
        if (!productData) {
            productData = products.find(p => p.barcode && p.barcode.toLowerCase() === barcode.toLowerCase());
        }
        if (!productData && products.length > 0) {
            productData = products[0];
        }

        if (productData) {
            currentProduct = productData;

            document.getElementById('detected-barcode').textContent = barcode;
            document.getElementById('product-name').textContent = productData.nama;
            document.getElementById('product-price').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(productData.harga);
            document.getElementById('product-stock').textContent = productData.stok + ' pcs';

            const wholesaleInfo = document.getElementById('wholesale-info');
            if (productData.is_grosir_active && productData.min_qty_grosir > 0 && productData.harga_grosir > 0) {
                const savings = productData.harga - productData.harga_grosir;
                const discountPercent = (savings / productData.harga * 100).toFixed(1);

                document.getElementById('min-wholesale-qty').textContent = productData.min_qty_grosir;
                document.getElementById('wholesale-price').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(productData.harga_grosir);
                document.getElementById('savings-per-unit').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(savings);
                document.getElementById('discount-percent').textContent = discountPercent + '%';
                wholesaleInfo.style.display = 'block';
            } else {
                wholesaleInfo.style.display = 'none';
            }

            const tebusMusahInfo = document.getElementById('tebus-murah-info');
            if (productData.is_tebus_murah_active && productData.min_total_tebus_murah > 0 && productData.harga_tebus_murah > 0) {
                const savings = productData.harga - productData.harga_tebus_murah;
                const discountPercent = (savings / productData.harga * 100).toFixed(1);

                document.getElementById('min-tebus-total').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(productData.min_total_tebus_murah);
                document.getElementById('tebus-price').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(productData.harga_tebus_murah);
                document.getElementById('tebus-savings-per-unit').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(savings);
                document.getElementById('tebus-discount-percent').textContent = discountPercent + '%';
                tebusMusahInfo.style.display = 'block';
            } else {
                tebusMusahInfo.style.display = 'none';
            }

            document.getElementById('barcode-result').style.display = 'block';
            document.getElementById('scan-qty').value = 1;
            updateBarcodePrice();

            showAlert('success', `✅ Produk ditemukan: ${productData.nama}`);
        } else {
            document.getElementById('barcode-result').style.display = 'none';
            currentProduct = null;
            showAlert('danger', `❌ Produk dengan barcode "${barcode}" tidak ditemukan!`);
        }
    })
    .catch(error => {
        console.error('Error searching product by barcode:', error);
        showAlert('danger', '❌ Terjadi kesalahan saat mencari produk.');
    });
}

function updateBarcodePrice() {
    if (!currentProduct) return;

    const qty = parseInt(document.getElementById('scan-qty').value) || 1;
    let unitPrice = currentProduct.harga;
    let priceType = 'Harga Normal';
    let isWholesale = false;
    let isTebusMusah = false;

    if (currentProduct.is_tebus_murah_active && 
        currentTransactionTotal >= currentProduct.min_total_tebus_murah && 
        currentProduct.harga_tebus_murah > 0) {
        unitPrice = currentProduct.harga_tebus_murah;
        priceType = 'Harga Tebus Murah';
        isTebusMusah = true;
    }
    else if (currentProduct.is_grosir_active && 
             qty >= currentProduct.min_qty_grosir && 
             currentProduct.harga_grosir > 0) {
        unitPrice = currentProduct.harga_grosir;
        priceType = 'Harga Grosir';
        isWholesale = true;
    }

    const totalPrice = unitPrice * qty;
    document.getElementById('total-price').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalPrice);
    document.getElementById('price-type').textContent = priceType;

    const priceTypeElement = document.getElementById('price-type');
    if (isTebusMusah) {
        priceTypeElement.className = 'small text-danger fw-bold';
    } else if (isWholesale) {
        priceTypeElement.className = 'small text-warning fw-bold';
    } else {
        priceTypeElement.className = 'small text-muted';
    }
}

// Event Listeners
document.getElementById('manual-form').addEventListener('submit', function(e) {
    e.preventDefault();
    if (!manualSelectedProduct) {
        showAlert('warning', '⚠️ Silakan pilih produk terlebih dahulu dari hasil pencarian.');
        return;
    }

    const submitBtn = document.getElementById('manual-submit-btn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menambahkan...';
    submitBtn.disabled = true;

    const formData = new FormData(this);
    formData.set('good_id', manualSelectedProduct.id);
    formData.set('subtotal', document.getElementById('subtotal').value);

    fetch('/dashboard/cashier/storeorder', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.ok) return response.text();
        throw new Error('Network response was not ok');
    })
    .then(html => {
        showAlert('success', '✅ Pesanan berhasil ditambahkan!');
        this.reset();
        document.getElementById('subtotal').value = '';
        document.getElementById('manual-wholesale-info').style.display = 'none';
        document.getElementById('manual-tebus-murah-info').style.display = 'none';
        document.getElementById('product-search-input').value = '';
        manualSelectedProduct = null;
        submitBtn.disabled = true;
        setTimeout(() => { location.reload(); }, 1000);
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', '❌ Terjadi kesalahan, silakan coba lagi.');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
    });
});

document.getElementById('add-scanned-item').addEventListener('click', function() {
    const barcode = document.getElementById('detected-barcode').textContent;
    const qty = document.getElementById('scan-qty').value;

    if (!barcode || !qty || qty < 1) {
        showAlert('warning', '⚠️ Data tidak valid!');
        return;
    }

    const originalText = this.innerHTML;
    this.innerHTML = '<i class="bi bi-hourglass-split"></i> Menambahkan...';
    this.disabled = true;

    fetch('/dashboard/cashier/store-barcode', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            barcode: barcode,
            qty: parseInt(qty),
            no_nota: '<?php echo e($no_nota); ?>'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', '✅ ' + data.message);
            document.getElementById('barcode-result').style.display = 'none';
            document.getElementById('barcode-input').value = '';
            document.getElementById('scan-qty').value = '1';
            currentProduct = null;
            setTimeout(() => { location.reload(); }, 1000);
        } else {
            showAlert('danger', '❌ ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', '❌ Terjadi kesalahan jaringan. Silakan coba lagi.');
    })
    .finally(() => {
        this.innerHTML = originalText;
        this.disabled = false;
    });
});

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show shadow-sm`;
    alertDiv.innerHTML = `
        <i class="bi bi-${type === 'success' ? 'check-circle-fill' : type === 'danger' ? 'exclamation-triangle-fill' : 'info-circle-fill'}"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.getElementById('dynamic-alerts').appendChild(alertDiv);
    setTimeout(() => { if (alertDiv.parentNode) { alertDiv.remove(); } }, 7000);
}

function clearBarcodeInput() {
    document.getElementById('barcode-input').value = '';
    document.getElementById('barcode-result').style.display = 'none';
    currentProduct = null;
    document.getElementById('barcode-input').focus();
}

document.getElementById('barcode-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const barcode = this.value.trim();
        if (barcode) processBarcode(barcode);
    }
});

document.getElementById('search-barcode').addEventListener('click', function() {
    const barcode = document.getElementById('barcode-input').value.trim();
    if (barcode) processBarcode(barcode);
    else showAlert('warning', '⚠️ Masukkan barcode terlebih dahulu!');
});

document.getElementById('scan-qty').addEventListener('change', updateBarcodePrice);
document.getElementById('scan-qty').addEventListener('input', updateBarcodePrice);

// Product Search
let searchTimeout;
const productSearchInput = document.getElementById('product-search-input');
const searchResultsDiv = document.getElementById('search-results');
const manualGoodIdInput = document.getElementById('manual-good-id');
const manualQtyInput = document.getElementById('qty');

productSearchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const query = this.value.trim();

    if (query.length < 2) {
        searchResultsDiv.style.display = 'none';
        manualSelectedProduct = null;
        manualGoodIdInput.value = '';
        calculateSubtotal();
        return;
    }

    searchTimeout = setTimeout(() => {
        fetch(`/dashboard/cashier/search-goods?query=${encodeURIComponent(query)}`, {
            method: 'GET',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
        })
        .then(response => response.json())
        .then(products => {
            searchResultsDiv.innerHTML = '';
            if (products.length > 0) {
                products.forEach(product => {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'search-results-item';
                    itemDiv.innerHTML = `
                        <div class="product-name">${product.nama}</div>
                        <div class="product-details">
                            Harga: Rp ${new Intl.NumberFormat('id-ID').format(product.harga)} | Stok: ${product.stok}
                            ${product.barcode ? `| Barcode: ${product.barcode}` : ''}
                        </div>
                    `;
                    itemDiv.addEventListener('click', () => {
                        manualSelectedProduct = product;
                        manualGoodIdInput.value = product.id;
                        productSearchInput.value = product.nama;
                        searchResultsDiv.style.display = 'none';
                        calculateSubtotal();
                        manualQtyInput.focus();
                    });
                    searchResultsDiv.appendChild(itemDiv);
                });
                searchResultsDiv.style.display = 'block';
            } else {
                searchResultsDiv.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error searching products:', error);
            searchResultsDiv.style.display = 'none';
        });
    }, 300);
});

document.addEventListener('click', function(event) {
    if (!productSearchInput.contains(event.target) && !searchResultsDiv.contains(event.target)) {
        searchResultsDiv.style.display = 'none';
    }
});

// Cleanup scanner when page unloads
window.addEventListener('beforeunload', function() {
    if (html5QrCode && isScannerRunning) {
        html5QrCode.stop();
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\SEMESTER 6\KERJA PRAKTEK PELINDO\project umkm\NEW\kasirku-main\resources\views/dashboard/cashiers/order/create.blade.php ENDPATH**/ ?>