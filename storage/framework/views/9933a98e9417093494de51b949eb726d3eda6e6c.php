

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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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

        #qr-reader {
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
        }
    </style>

    <div class="d-flex justify-content-between flex-wrap align-items-center py-3 px-3 mb-3 border-bottom"
        style="background: linear-gradient(90deg, #4e54c8, #8f94fb); border-radius: 10px;">
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
                        <h4 class="mb-0 fw-bold"><i class="bi bi-upc-scan fs-3"></i> INPUT BARCODE</h4>
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
                                            id="barcode-input" placeholder="Ketik atau scan barcode di sini..."
                                            autocomplete="off" autofocus style="font-size: 1.2rem; font-weight: 500;">
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
                                        <li><strong>Tekan Enter</strong> untuk cari cepat</li>
                                        <li><strong>Gunakan kamera</strong> untuk scan</li>
                                    </ul>
                                </div>
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
                                                <p class="mb-2"><strong>Kode:</strong> <span id="detected-barcode"
                                                        class="badge bg-secondary fs-6"></span></p>
                                                <p class="mb-2"><strong>Nama Produk:</strong> <span id="product-name"
                                                        class="fw-bold text-dark fs-5"></span></p>
                                            </div>
                                            <div class="col-6">
                                                <p class="mb-2"><strong>Harga:</strong> <span id="product-price"
                                                        class="fw-bold text-success fs-4"></span></p>
                                                <p class="mb-2"><strong>Stok Tersedia:</strong> <span id="product-stock"
                                                        class="badge bg-info fs-6"></span></p>
                                            </div>
                                        </div>
                                        <div id="wholesale-info"
                                            class="mt-3 p-2 bg-warning bg-opacity-10 rounded border-start border-warning border-3"
                                            style="display: none;">
                                            <h6 class="text-warning mb-2">
                                                <i class="bi bi-cart-plus"></i> Harga Grosir Tersedia!
                                            </h6>
                                            <div class="row">
                                                <div class="col-6">
                                                    <small><strong>Min. Pembelian:</strong> <span
                                                            id="min-wholesale-qty"></span></small><br>
                                                    <small><strong>Harga Grosir:</strong> <span id="wholesale-price"
                                                            class="text-success fw-bold"></span></small>
                                                </div>
                                                <div class="col-6">
                                                    <small><strong>Hemat per unit:</strong> <span id="savings-per-unit"
                                                            class="text-success fw-bold"></span></small><br>
                                                    <small><strong>Diskon:</strong> <span id="discount-percent"
                                                            class="text-success fw-bold"></span></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="tebus-murah-info"
                                            class="mt-3 p-2 bg-danger bg-opacity-10 rounded border-start border-danger border-3"
                                            style="display: none;">
                                            <h6 class="text-danger mb-2">
                                                <i class="bi bi-percent"></i> Harga Tebus Murah Tersedia!
                                            </h6>
                                            <div class="row">
                                                <div class="col-6">
                                                    <small><strong>Min. Total Transaksi:</strong> <span id="min-tebus-total"
                                                            class="text-danger fw-bold"></span></small><br>
                                                    <small><strong>Harga Tebus Murah:</strong> <span id="tebus-price"
                                                            class="text-danger fw-bold"></span></small>
                                                </div>
                                                <div class="col-6">
                                                    <small><strong>Hemat per unit:</strong> <span
                                                            id="tebus-savings-per-unit"
                                                            class="text-danger fw-bold"></span></small><br>
                                                    <small><strong>Diskon:</strong> <span id="tebus-discount-percent"
                                                            class="text-danger fw-bold"></span></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-grid gap-2">
                                        <label for="scan-qty" class="form-label fw-semibold fs-5">Jumlah:</label>
                                        <input type="number" class="form-control form-control-lg text-center fw-bold"
                                            id="scan-qty" value="1" min="1" max="999" style="font-size: 1.5rem;"
                                            onchange="updateBarcodePrice()" oninput="updateBarcodePrice()">
                                        <div id="price-info" class="text-center mb-2">
                                            <div class="fw-bold fs-5">Total: <span id="total-price" class="text-success">Rp
                                                    0</span></div>
                                            <div id="price-type" class="small text-muted"></div>
                                        </div>
                                        <button type="button" id="add-scanned-item" class="btn btn-success btn-lg py-3">
                                            <i class="bi bi-plus-circle"></i> TAMBAH KE PESANAN
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 pt-4 border-top">
                            <h5 class="fw-bold text-primary mb-3">
                                <i class="bi bi-camera-video"></i> Scan Barcode dengan Kamera
                            </h5>
                            <div id="qr-reader" style="width: 100%; max-width: 500px; margin: auto;"></div>
                            <div class="d-grid gap-2 mt-3">
                                <button type="button" class="btn btn-info btn-lg" id="start-scan-btn">
                                    <i class="bi bi-camera"></i> Mulai Scan Kamera
                                </button>
                                <button type="button" class="btn btn-danger btn-lg" id="stop-scan-btn"
                                    style="display: none;">
                                    <i class="bi bi-stop-circle"></i> Hentikan Scan Kamera
                                </button>
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
                                    id="product-search-input" placeholder="Ketik nama atau barcode produk..."
                                    autocomplete="off" style="font-size: 1.1rem;">
                                <div id="search-results" class="search-results" style="display: none;"></div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="qty" class="form-label fs-5 fw-semibold text-dark">
                                        <i class="bi bi-123 text-primary"></i> Jumlah
                                    </label>
                                    <input type="number"
                                        class="form-control form-control-lg border-primary text-center fw-bold" id="qty"
                                        name="qty" min="1" value="1" onchange="calculateSubtotal()" oninput="calculateSubtotal()" required
                                        style="font-size: 1.3rem;">
                                </div>
                                <div class="col-md-6">
                                    <label for="subtotal" class="form-label fs-5 fw-semibold text-dark">
                                        <i class="bi bi-calculator text-primary"></i> Subtotal
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <span
                                            class="input-group-text bg-primary text-white border-primary fw-bold">Rp</span>
                                        <input type="number" class="form-control border-primary text-end fw-bold"
                                            id="subtotal" name="subtotal" readonly
                                            style="font-size: 1.3rem; background-color: #f8f9fa;">
                                    </div>
                                </div>
                            </div>

                            <div id="manual-wholesale-info"
                                class="mt-3 p-3 bg-warning bg-opacity-10 rounded border-start border-warning border-3"
                                style="display: none;">
                                <h6 class="text-warning mb-2">
                                    <i class="bi bi-cart-plus"></i> Harga Grosir Aktif!
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small><strong>Harga per unit:</strong> <span id="manual-unit-price"
                                                class="text-success fw-bold"></span></small><br>
                                        <small><strong>Total hemat:</strong> <span id="manual-total-savings"
                                                class="text-success fw-bold"></span></small>
                                    </div>
                                    <div class="col-md-6">
                                        <small><strong>Diskon:</strong> <span id="manual-discount-percent"
                                                class="text-success fw-bold"></span></small><br>
                                        <small class="text-muted">Pembelian <span id="manual-wholesale-min-qty"></span>+
                                            unit</small>
                                    </div>
                                </div>
                            </div>

                            <div id="manual-tebus-murah-info"
                                class="mt-3 p-3 bg-danger bg-opacity-10 rounded border-start border-danger border-3"
                                style="display: none;">
                                <h6 class="text-danger mb-2">
                                    <i class="bi bi-percent"></i> Harga Tebus Murah Aktif!
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small><strong>Harga per unit:</strong> <span id="manual-tebus-unit-price"
                                                class="text-danger fw-bold"></span></small><br>
                                        <small><strong>Total hemat:</strong> <span id="manual-tebus-total-savings"
                                                class="text-danger fw-bold"></span></small>
                                    </div>
                                    <div class="col-md-6">
                                        <small><strong>Diskon:</strong> <span id="manual-tebus-discount-percent"
                                                class="text-danger fw-bold"></span></small><br>
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
                                                    <small class="text-muted">@ Rp
                                                        <?php echo e(number_format($order->price, 0, ',', '.')); ?></small>
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
                                    $tebusMusahProducts = $goods->filter(function ($good) use ($totalTransaction) {
                                        return $good->is_tebus_murah_active && $totalTransaction >= $good->min_total_tebus_murah;
                                    });
                                ?>

                                <?php if($tebusMusahProducts->count() > 0): ?>
                                    <div class="alert alert-danger border-0 shadow-sm mb-3">
                                        <h6 class="fw-bold text-danger mb-2">
                                            <i class="bi bi-percent"></i> TEBUS MURAH AKTIF!
                                        </h6>
                                        <small class="text-muted">
                                            <?php echo e($tebusMusahProducts->count()); ?> produk memenuhi syarat tebus murah.
                                        </small>
                                    </div>
                                <?php endif; ?>

                                <form method="post" action="/dashboard/cashiers/checkout">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="no_nota" value="<?php echo e($no_nota); ?>">
                                    <input type="hidden" name="total_harga" value="<?php echo e($orders->sum('subtotal')); ?>"
                                        id="checkout-total">
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
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        let currentProduct = null;
        let currentTransactionTotal = <?php echo e($orders->sum('subtotal')); ?>;
        let manualSelectedProduct = null;
        let html5QrcodeScanner;
        let isScanning = false;

        function onScanSuccess(decodedText, decodedResult) {
            document.getElementById('barcode-input').value = decodedText;
            processBarcode(decodedText);
            stopScanner();
        }

        function onScanError(errorMessage) {}

        function startScanner() {
            const isSecureContext = window.location.protocol === 'https:' || ['localhost', '127.0.0.1'].includes(window.location.hostname);
            if (!isSecureContext) {
                showAlert('danger', '❌ Akses kamera butuh HTTPS atau localhost.');
                return;
            }
            if (!html5QrcodeScanner) {
                html5QrcodeScanner = new Html5Qrcode("qr-reader");
            }
            html5QrcodeScanner.start({ facingMode: "environment" }, { fps: 10, qrbox: { width: 250, height: 250 } }, onScanSuccess, onScanError);
            isScanning = true;
            document.getElementById('start-scan-btn').style.display = 'none';
            document.getElementById('stop-scan-btn').style.display = 'block';
            showAlert('info', 'Kamera aktif, siap memindai barcode.');
        }

        function stopScanner() {
            if (html5QrcodeScanner && isScanning) {
                html5QrcodeScanner.stop().then(() => {
                    isScanning = false;
                    document.getElementById('start-scan-btn').style.display = 'block';
                    document.getElementById('stop-scan-btn').style.display = 'none';
                }).catch(err => console.error("Gagal stop scanner.", err));
            }
        }
        
        function formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }
        
        function processBarcode(barcode) {
            barcode = barcode.trim();
            if (!barcode) return;
            
            fetch(`/dashboard/cashier/search-goods?query=${encodeURIComponent(barcode)}`)
            .then(response => response.json())
            .then(products => {
                let productData = products.find(p => p.barcode && p.barcode.toLowerCase() === barcode.toLowerCase()) || (products.length > 0 ? products[0] : null);

                if (productData) {
                    currentProduct = productData;
                    document.getElementById('barcode-result').style.display = 'block';
                    document.getElementById('detected-barcode').textContent = productData.barcode || 'N/A';
                    document.getElementById('product-name').textContent = productData.nama;
                    document.getElementById('product-price').textContent = formatRupiah(productData.harga);
                    document.getElementById('product-stock').textContent = productData.stok + ' pcs';
                    document.getElementById('scan-qty').value = 1;
                    updateBarcodePrice();
                    showAlert('success', `✅ Produk ditemukan: ${productData.nama}`);
                } else {
                    document.getElementById('barcode-result').style.display = 'none';
                    currentProduct = null;
                    showAlert('danger', `❌ Produk dengan barcode "${barcode}" tidak ditemukan!`);
                }
            }).catch(error => showAlert('danger', '❌ Terjadi kesalahan saat mencari produk.'));
        }

        function updateBarcodePrice() {
            if (!currentProduct) return;

            const qty = parseInt(document.getElementById('scan-qty').value) || 1;
            let unitPrice = currentProduct.harga;
            let priceType = 'Harga Normal';

            const wholesaleInfo = document.getElementById('wholesale-info');
            const tebusMurahInfo = document.getElementById('tebus-murah-info');
            wholesaleInfo.style.display = 'none';
            tebusMurahInfo.style.display = 'none';

            const isTebusMurahEligible = currentProduct.is_tebus_murah_active && currentTransactionTotal >= currentProduct.min_total_tebus_murah && currentProduct.harga_tebus_murah > 0;
            const isWholesaleEligible = currentProduct.is_grosir_active && qty >= currentProduct.min_qty_grosir && currentProduct.harga_grosir > 0;
            
            if (isTebusMurahEligible) {
                unitPrice = currentProduct.harga_tebus_murah;
                priceType = 'Harga Tebus Murah';
            } else if (isWholesaleEligible) {
                unitPrice = currentProduct.harga_grosir;
                priceType = 'Harga Grosir';
            }
            
            if (isWholesaleEligible) {
                const savings = currentProduct.harga - currentProduct.harga_grosir;
                document.getElementById('min-wholesale-qty').textContent = currentProduct.min_qty_grosir;
                document.getElementById('wholesale-price').textContent = formatRupiah(currentProduct.harga_grosir);
                document.getElementById('savings-per-unit').textContent = formatRupiah(savings);
                document.getElementById('discount-percent').textContent = (savings / currentProduct.harga * 100).toFixed(1) + '%';
                wholesaleInfo.style.display = 'block';
            }

            if (isTebusMurahEligible) {
                const savings = currentProduct.harga - currentProduct.harga_tebus_murah;
                document.getElementById('min-tebus-total').textContent = formatRupiah(currentProduct.min_total_tebus_murah);
                document.getElementById('tebus-price').textContent = formatRupiah(currentProduct.harga_tebus_murah);
                document.getElementById('tebus-savings-per-unit').textContent = formatRupiah(savings);
                document.getElementById('tebus-discount-percent').textContent = (savings / currentProduct.harga * 100).toFixed(1) + '%';
                tebusMurahInfo.style.display = 'block';
            }

            document.getElementById('total-price').textContent = formatRupiah(unitPrice * qty);
            const priceTypeElement = document.getElementById('price-type');
            priceTypeElement.textContent = priceType;
            priceTypeElement.className = isTebusMurahEligible ? 'small text-danger fw-bold' : isWholesaleEligible ? 'small text-warning fw-bold' : 'small text-muted';
        }

        function calculateSubtotal() {
            const qtyInput = document.getElementById('qty');
            const subtotalInput = document.getElementById('subtotal');
            const manualSubmitBtn = document.getElementById('manual-submit-btn');

            const wholesaleInfo = document.getElementById('manual-wholesale-info');
            const tebusMusahInfo = document.getElementById('manual-tebus-murah-info');
            wholesaleInfo.style.display = 'none';
            tebusMusahInfo.style.display = 'none';
            subtotalInput.value = '';
            manualSubmitBtn.disabled = true;
            
            if (!manualSelectedProduct || !qtyInput.value) return;

            const product = manualSelectedProduct;
            const retailPrice = product.harga || 0;
            const qty = parseInt(qtyInput.value) || 0;
            const stock = product.stok || 0;
            if (qty > stock) {
                showAlert('warning', `⚠️ Jumlah melebihi stok (${stock})`);
                qtyInput.value = stock;
                return;
            }

            let unitPrice = retailPrice;
            const isTebusMurahEligible = product.is_tebus_murah_active && currentTransactionTotal >= product.min_total_tebus_murah && product.harga_tebus_murah > 0;
            const isWholesaleEligible = product.is_grosir_active && qty >= product.min_qty_grosir && product.harga_grosir > 0;

            if (isTebusMurahEligible) {
                unitPrice = product.harga_tebus_murah;
            } else if (isWholesaleEligible) {
                unitPrice = product.harga_grosir;
            }

            if(isWholesaleEligible) {
                const savings = (retailPrice - product.harga_grosir) * qty;
                document.getElementById('manual-unit-price').textContent = formatRupiah(product.harga_grosir);
                document.getElementById('manual-total-savings').textContent = formatRupiah(savings);
                document.getElementById('manual-discount-percent').textContent = ((retailPrice - product.harga_grosir) / retailPrice * 100).toFixed(1) + '%';
                document.getElementById('manual-wholesale-min-qty').textContent = product.min_qty_grosir;
                wholesaleInfo.style.display = 'block';
            }

            if(isTebusMurahEligible) {
                const savings = (retailPrice - product.harga_tebus_murah) * qty;
                document.getElementById('manual-tebus-unit-price').textContent = formatRupiah(product.harga_tebus_murah);
                document.getElementById('manual-tebus-total-savings').textContent = formatRupiah(savings);
                document.getElementById('manual-tebus-discount-percent').textContent = ((retailPrice - product.harga_tebus_murah) / retailPrice * 100).toFixed(1) + '%';
                tebusMusahInfo.style.display = 'block';
            }

            subtotalInput.value = unitPrice * qty;
            manualSubmitBtn.disabled = false;
        }

        function showAlert(type, message) {
            const alertContainer = document.getElementById('dynamic-alerts');
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show shadow-sm`;
            alertDiv.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'}"></i> ${message} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
            alertContainer.appendChild(alertDiv);
            setTimeout(() => { alertDiv.remove(); }, 5000);
        }

        // --- Event Listeners ---
        document.getElementById('manual-form').addEventListener('submit', function (e) {
            e.preventDefault();
            if (!manualSelectedProduct) {
                showAlert('warning', '⚠️ Silakan pilih produk dari pencarian.');
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
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
            }).then(response => {
                if (!response.ok) throw new Error('Network error');
                return response.text();
            }).then(() => {
                showAlert('success', '✅ Pesanan berhasil ditambahkan!');
                setTimeout(() => location.reload(), 1000);
            }).catch(() => showAlert('danger', '❌ Terjadi kesalahan.')).finally(() => submitBtn.innerHTML = originalText);
        });

        document.getElementById('add-scanned-item').addEventListener('click', function () {
            const barcode = document.getElementById('barcode-input').value.trim();
            const qty = document.getElementById('scan-qty').value;
            if (!barcode || !currentProduct) {
                showAlert('warning', '⚠️ Cari produk via barcode dulu!');
                return;
            }
            if (!qty || qty < 1) {
                showAlert('warning', '⚠️ Jumlah minimal 1!');
                return;
            }
            const submitBtn = this;
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menambahkan...';
            submitBtn.disabled = true;

            const formData = new FormData();
            formData.append('barcode', barcode);
            formData.append('qty', parseInt(qty));
            formData.append('no_nota', '<?php echo e($no_nota); ?>');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/dashboard/cashier/store-barcode', {
                method: 'POST',
                body: formData
            }).then(response => {
                if (!response.ok) return response.json().then(err => { throw err; });
                return response.text();
            }).then(() => {
                showAlert('success', '✅ Produk ditambahkan ke pesanan!');
                setTimeout(() => location.reload(), 1000);
            }).catch(error => showAlert('danger', '❌ ' + (error.message || 'Error jaringan.'))).finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });

        document.getElementById('barcode-input').addEventListener('keypress', e => { if (e.key === 'Enter') { e.preventDefault(); processBarcode(e.target.value); } });
        document.getElementById('search-barcode').addEventListener('click', () => processBarcode(document.getElementById('barcode-input').value));
        
        const productSearchInput = document.getElementById('product-search-input');
        const searchResultsDiv = document.getElementById('search-results');
        let searchTimeout;
        productSearchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            if (query.length < 2) { searchResultsDiv.style.display = 'none'; return; }
            searchTimeout = setTimeout(() => {
                fetch(`/dashboard/cashier/search-goods?query=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(products => {
                    searchResultsDiv.innerHTML = '';
                    if (products.length > 0) {
                        products.forEach(product => {
                            const itemDiv = document.createElement('div');
                            itemDiv.className = 'search-results-item';
                            itemDiv.innerHTML = `<div class="product-name">${product.nama}</div><div class="product-details">Harga: ${formatRupiah(product.harga)} | Stok: ${product.stok}</div>`;
                            itemDiv.addEventListener('click', () => {
                                manualSelectedProduct = product;
                                document.getElementById('manual-good-id').value = product.id;
                                productSearchInput.value = product.nama;
                                searchResultsDiv.style.display = 'none';
                                document.getElementById('qty').value = 1;
                                calculateSubtotal();
                                document.getElementById('qty').focus();
                            });
                            searchResultsDiv.appendChild(itemDiv);
                        });
                        searchResultsDiv.style.display = 'block';
                    } else {
                        searchResultsDiv.style.display = 'none';
                    }
                });
            }, 300);
        });

        document.addEventListener('click', e => { if (!productSearchInput.contains(e.target) && !searchResultsDiv.contains(e.target)) { searchResultsDiv.style.display = 'none'; } });
        document.getElementById('start-scan-btn').addEventListener('click', startScanner);
        document.getElementById('stop-scan-btn').addEventListener('click', stopScanner);
        window.addEventListener('beforeunload', () => stopScanner());
        document.addEventListener('DOMContentLoaded', () => document.getElementById('barcode-input').focus());
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('dashboard.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\SEMESTER 6\KERJA PRAKTEK PELINDO\project umkm\NEW\kasirku-main\resources\views/dashboard/cashiers/order/create.blade.php ENDPATH**/ ?>