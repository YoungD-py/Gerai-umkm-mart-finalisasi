@extends('dashboard.layouts.main')

@section('container')
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
        background: linear-gradient(135deg, #ffc107, #e0a800);
        color: #000;
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
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-control, .form-select {
        border-radius: 15px;
        border: 2px solid #e9ecef;
        padding: 12px 20px;
        transition: all 0.3s ease;
        background: rgba(255,255,255,0.9);
        font-size: 1rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        background: white;
        transform: translateY(-1px);
    }

    .form-control:read-only {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
        cursor: not-allowed;
    }

    .input-group-text {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        color: #000;
        border: none;
        border-radius: 15px 0 0 15px;
        font-weight: 600;
    }

    .input-group .form-control {
        border-radius: 0 15px 15px 0;
        border-left: none;
    }

    .btn-warning-umkm {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        border: none;
        border-radius: 15px;
        padding: 12px 25px;
        font-weight: 600;
        color: #000;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-warning-umkm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 193, 7, 0.3);
        color: #000;
        text-decoration: none;
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
        text-decoration: none;
    }

    .btn-outline-umkm {
        background: transparent;
        border: 2px solid #28a745;
        border-radius: 15px;
        padding: 10px 20px;
        font-weight: 600;
        color: #28a745;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
    }

    .btn-outline-umkm:hover {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        transform: translateY(-1px);
        text-decoration: none;
    }

    .btn-info-restock {
        background: linear-gradient(135deg, #17a2b8, #138496);
        border: none;
        border-radius: 15px;
        padding: 10px 20px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
    }

    .btn-info-restock:hover {
        background: linear-gradient(135deg, #138496, #117a8b);
        color: white;
        transform: translateY(-1px);
        text-decoration: none;
    }

    .barcode-section {
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1));
        border-radius: 15px;
        padding: 20px;
        border: 2px solid rgba(40, 167, 69, 0.2);
        margin-bottom: 20px;
    }

    .barcode-info {
        background: white;
        border-radius: 10px;
        padding: 15px;
        border-left: 4px solid #28a745;
    }

    .alert-warning-umkm {
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(224, 168, 0, 0.1));
        border-radius: 15px;
        border: 2px solid rgba(255, 193, 7, 0.3);
        padding: 15px 20px;
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

    .form-section {
        background: rgba(248, 249, 250, 0.5);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .section-title {
        color: #ffc107;
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .required {
        color: #dc3545;
    }

    .expired-section {
        display: none;
        background: rgba(255, 193, 7, 0.1);
        border-radius: 15px;
        padding: 15px;
        border: 2px solid rgba(255, 193, 7, 0.3);
        margin-top: 15px;
    }

    .expired-section.show {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    .wholesale-section {
        background: linear-gradient(135deg, rgba(255, 87, 34, 0.1), rgba(255, 152, 0, 0.1));
        border-radius: 15px;
        padding: 20px;
        border: 2px solid rgba(255, 87, 34, 0.2);
        margin-bottom: 20px;
    }

    .wholesale-toggle {
        background: white;
        border-radius: 10px;
        padding: 15px;
        border-left: 4px solid #ff5722;
        margin-bottom: 15px;
    }

    .wholesale-fields {
        display: none;
        background: rgba(255, 255, 255, 0.7);
        border-radius: 10px;
        padding: 15px;
        margin-top: 15px;
    }

    .wholesale-fields.show {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    .tebus-murah-section {
        background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(200, 35, 51, 0.1));
        border-radius: 15px;
        padding: 20px;
        border: 2px solid rgba(220, 53, 69, 0.2);
        margin-bottom: 20px;
    }

    .tebus-murah-toggle {
        background: white;
        border-radius: 10px;
        padding: 15px;
        border-left: 4px solid #dc3545;
        margin-bottom: 15px;
    }

    .tebus-murah-fields {
        display: none;
        background: rgba(255, 255, 255, 0.7);
        border-radius: 10px;
        padding: 15px;
        margin-top: 15px;
    }

    .tebus-murah-fields.show {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    .form-check-input:checked {
        background-color: #ff5722;
        border-color: #ff5722;
    }

    .form-check-input:focus {
        border-color: #ff5722;
        box-shadow: 0 0 0 0.25rem rgba(255, 87, 34, 0.25);
    }

    .price-info {
        background: rgba(23, 162, 184, 0.05);
        border-radius: 10px;
        padding: 15px;
        margin-top: 15px;
        border-left: 4px solid #17a2b8;
    }

    .stock-readonly-info {
        background: linear-gradient(135deg, rgba(23, 162, 184, 0.1), rgba(19, 132, 150, 0.1));
        border-radius: 10px;
        padding: 15px;
        margin-top: 10px;
        border-left: 4px solid #17a2b8;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="container-fluid py-4">
    <div class="page-title">
        <h1>✏️ EDIT BARANG</h1>
        <p>Perbarui informasi barang di inventori GERAI UMKM MART</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="umkm-card">
                <div class="umkm-card-header">
                    <h3 class="umkm-card-title">
                        <i class="bi bi-pencil-square"></i>
                        Form Edit Barang
                    </h3>
                </div>

                <div class="umkm-card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="post" action="/dashboard/goods/{{ $good->id }}" enctype="multipart/form-data">
                        @method('put')
                        @csrf
                        <input type="hidden" name="id" value="{{ $good->id }}">

                        <!-- Basic Information -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="bi bi-info-circle"></i>
                                Informasi Dasar
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="category_id" class="form-label">
                                        <i class="bi bi-building text-warning"></i>
                                        Supplier <span class="required">*</span>
                                    </label>
                                    <select class="form-select @error('category_id') is-invalid @enderror"
                                            name="category_id" id="category_id" required>
                                        <option value="">-- Pilih Supplier --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                    {{ old('category_id', $good->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="tgl_masuk" class="form-label">
                                        <i class="bi bi-calendar-event text-warning"></i>
                                        Tanggal Masuk <span class="required">*</span>
                                    </label>
                                    <input type="date" class="form-control @error('tgl_masuk') is-invalid @enderror"
                                           id="tgl_masuk" name="tgl_masuk"
                                           value="{{ old('tgl_masuk', $good->tgl_masuk->format('Y-m-d')) }}" required>
                                    @error('tgl_masuk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="nama" class="form-label">
                                    <i class="bi bi-box text-warning"></i>
                                    Nama Barang <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                       id="nama" name="nama" value="{{ old('nama', $good->nama) }}"
                                       required autofocus placeholder="Masukkan nama barang...">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Product Type & Expiry -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="bi bi-tags"></i>
                                Jenis & Masa Berlaku
                            </div>

                            <div class="mb-3">
                                <label for="type" class="form-label">
                                    <i class="bi bi-collection text-warning"></i>
                                    Jenis Barang <span class="required">*</span>
                                </label>
                                <select class="form-select @error('type') is-invalid @enderror"
                                        name="type" id="type" required onchange="toggleExpiredField(); calculateSellingPrice();">
                                    <option value="">-- Pilih Jenis Barang --</option>
                                    <option value="makanan" {{ old('type', $good->type) == 'makanan' ? 'selected' : '' }}>
                                        Makanan & Minuman
                                    </option>
                                    <option value="non_makanan" {{ old('type', $good->type) == 'non_makanan' ? 'selected' : '' }}>
                                        Non Makanan & Minuman
                                    </option>
                                    <option value="lainnya" {{ old('type', $good->type) == 'lainnya' ? 'selected' : '' }}>
                                        Lainnya
                                    </option>
                                    <option value="handycraft" {{ old('type', $good->type) == 'handycraft' ? 'selected' : '' }}>Handycraft</option>
                                    <option value="fashion" {{ old('type', $good->type) == 'fashion' ? 'selected' : '' }}>Fashion</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="expired-section" class="expired-section">
                                <label for="expired_date" class="form-label">
                                    <i class="bi bi-calendar-x text-warning"></i>
                                    Tanggal Expired <span class="required">*</span>
                                </label>
                                <input type="date" class="form-control @error('expired_date') is-invalid @enderror"
                                       id="expired_date" name="expired_date"
                                       value="{{ old('expired_date', $good->expired_date ? $good->expired_date->format('Y-m-d') : '') }}"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                @error('expired_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i>
                                    Tanggal expired harus setelah hari ini
                                </small>
                            </div>
                        </div>

                        <!-- Price & Stock -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="bi bi-currency-dollar"></i>
                                Harga & Stok
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="harga_asli" class="form-label">
                                        <i class="bi bi-cash text-warning"></i>
                                        Harga Asli <span class="required">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control @error('harga_asli') is-invalid @enderror"
                                               id="harga_asli" name="harga_asli" value="{{ old('harga_asli', $good->harga_asli) }}"
                                               required min="0" onchange="calculateSellingPrice();" oninput="calculateSellingPrice();">
                                    </div>
                                    @error('harga_asli')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Harga asli sebelum markup</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="stok" class="form-label">
                                        <i class="bi bi-boxes text-warning"></i>
                                        Stok Saat Ini
                                    </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control"
                                               id="stok" name="stok" value="{{ $good->stok }}"
                                               readonly>
                                        <span class="input-group-text">unit</span>
                                    </div>
                                    <div class="stock-readonly-info">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-info">
                                                    <i class="bi bi-info-circle"></i>
                                                    Stok tidak dapat diubah di halaman ini
                                                </small>
                                            </div>
                                            <a href="/dashboard/restock/{{ $good->id }}/edit" class="btn-info-restock">
                                                <i class="bi bi-plus-circle"></i>
                                                Restock
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="price-info" class="price-info">
                                <h6 class="text-info mb-2">
                                    <i class="bi bi-calculator"></i> Informasi Harga Jual
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Harga Asli:</strong> <span id="display-harga-asli" class="text-muted">Rp {{ number_format($good->harga_asli ?? 0, 0, ',', '.') }}</span></p>
                                        <p class="mb-1"><strong>Markup:</strong> <span id="markup-percent" class="text-info">{{ $good->type === 'makanan' ? '2%' : '5%' }}</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Harga Jual:</strong> <span id="display-harga-jual" class="text-success fw-bold">Rp {{ number_format($good->harga ?? 0, 0, ',', '.') }}</span></p>
                                        <p class="mb-0"><strong>Keuntungan:</strong> <span id="display-profit" class="text-success">Rp {{ number_format(($good->harga ?? 0) - ($good->harga_asli ?? 0), 0, ',', '.') }}</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Wholesale Section -->
                        <div class="wholesale-section">
                            <div class="section-title">
                                <i class="bi bi-tags-fill" style="color: #ff5722;"></i>
                                Pengaturan Grosir
                            </div>

                            <div class="wholesale-toggle">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                           id="is_grosir_active" name="is_grosir_active" value="1"
                                           {{ old('is_grosir_active', $good->is_grosir_active) ? 'checked' : '' }}
                                           onchange="toggleWholesaleFields()">
                                    <label class="form-check-label fw-bold" for="is_grosir_active">
                                        <i class="bi bi-shop"></i>
                                        Aktifkan Harga Grosir
                                    </label>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i>
                                    Aktifkan untuk memberikan harga khusus pembelian dalam jumlah besar
                                </small>
                            </div>

                            <div id="wholesale-fields" class="wholesale-fields {{ old('is_grosir_active', $good->is_grosir_active) ? 'show' : '' }}">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="min_qty_grosir" class="form-label">
                                            <i class="bi bi-box-seam" style="color: #ff5722;"></i>
                                            Minimal Pembelian Grosir <span class="required">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" class="form-control @error('min_qty_grosir') is-invalid @enderror"
                                                   id="min_qty_grosir" name="min_qty_grosir"
                                                   value="{{ old('min_qty_grosir', $good->min_qty_grosir) }}"
                                                   min="2" placeholder="Contoh: 10">
                                            <span class="input-group-text">unit</span>
                                        </div>
                                        @error('min_qty_grosir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Minimal 2 unit untuk harga grosir</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="harga_grosir" class="form-label">
                                            <i class="bi bi-cash-coin" style="color: #ff5722;"></i>
                                            Harga Grosir <span class="required">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control @error('harga_grosir') is-invalid @enderror"
                                                   id="harga_grosir" name="harga_grosir"
                                                   value="{{ old('harga_grosir', $good->harga_grosir) }}"
                                                   min="0" placeholder="Harga lebih murah dari harga eceran">
                                        </div>
                                        @error('harga_grosir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Harga harus lebih kecil dari harga eceran</small>
                                    </div>
                                </div>

                                @if($good->is_grosir_active && $good->min_qty_grosir && $good->harga_grosir)
                                <div class="alert alert-info">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        <div>
                                            <strong>Pengaturan Grosir Saat Ini:</strong><br>
                                            <small>
                                                Minimal {{ $good->min_qty_grosir }} unit = Rp {{ number_format($good->harga_grosir, 0, ',', '.') }}/unit
                                                (Hemat Rp {{ number_format($good->harga - $good->harga_grosir, 0, ',', '.') }}/unit)
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Tebus Murah Section -->
                        <div class="tebus-murah-section">
                            <div class="section-title">
                                <i class="bi bi-tag-fill" style="color: #dc3545;"></i>
                                Pengaturan Tebus Murah
                            </div>

                            <div class="tebus-murah-toggle">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                           id="is_tebus_murah_active" name="is_tebus_murah_active" value="1"
                                           {{ old('is_tebus_murah_active', $good->is_tebus_murah_active) ? 'checked' : '' }}
                                           onchange="toggleTebusMusahFields()" style="background-color: #dc3545; border-color: #dc3545;">
                                    <label class="form-check-label fw-bold" for="is_tebus_murah_active">
                                        <i class="bi bi-percent"></i>
                                        Aktifkan Harga Tebus Murah untuk Barang Ini
                                    </label>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i>
                                    Aktifkan untuk memberikan harga khusus ketika total pembelian mencapai nilai tertentu
                                </small>
                            </div>

                            <div id="tebus-murah-fields" class="tebus-murah-fields {{ old('is_tebus_murah_active', $good->is_tebus_murah_active) ? 'show' : '' }}">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="min_total_tebus_murah" class="form-label">
                                            <i class="bi bi-cash-stack" style="color: #dc3545;"></i>
                                            Minimal Pembelian Tebus Murah <span class="required">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white;">Rp</span>
                                            <input type="number" class="form-control @error('min_total_tebus_murah') is-invalid @enderror"
                                                   id="min_total_tebus_murah" name="min_total_tebus_murah"
                                                   value="{{ old('min_total_tebus_murah', $good->min_total_tebus_murah) }}"
                                                   min="0" placeholder="Contoh: 100000">
                                        </div>
                                        @error('min_total_tebus_murah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Total nilai transaksi minimal untuk mendapat harga tebus murah</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="harga_tebus_murah" class="form-label">
                                            <i class="bi bi-tag" style="color: #dc3545;"></i>
                                            Harga Tebus Murah <span class="required">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white;">Rp</span>
                                            <input type="number" class="form-control @error('harga_tebus_murah') is-invalid @enderror"
                                                   id="harga_tebus_murah" name="harga_tebus_murah"
                                                   value="{{ old('harga_tebus_murah', $good->harga_tebus_murah) }}"
                                                   min="0" placeholder="Harga lebih murah dari harga normal">
                                        </div>
                                        @error('harga_tebus_murah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Harga khusus untuk barang ini ketika syarat terpenuhi</small>
                                    </div>
                                </div>

                                @if($good->is_tebus_murah_active && $good->min_total_tebus_murah && $good->harga_tebus_murah)
                                <div class="alert alert-danger">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        <div>
                                            <strong>Pengaturan Tebus Murah Saat Ini:</strong><br>
                                            <small>
                                                Min. total transaksi Rp {{ number_format($good->min_total_tebus_murah, 0, ',', '.') }} = Harga Rp {{ number_format($good->harga_tebus_murah, 0, ',', '.') }}
                                                (Hemat Rp {{ number_format($good->harga - $good->harga_tebus_murah, 0, ',', '.') }})
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Barcode Section -->
                        @if($good->barcode)
                        <div class="barcode-section">
                            <div class="section-title">
                                <i class="bi bi-qr-code"></i>
                                Informasi Barcode
                            </div>
                            <div class="barcode-info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 text-success">{{ $good->barcode }}</h6>
                                        <small class="text-muted">Barcode saat ini</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="/dashboard/goods/{{ $good->id }}/print-barcode"
                                           class="btn-outline-umkm">
                                            <i class="bi bi-printer"></i> <i class="bi bi-download"></i>
                                            Print / Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="alert-warning-umkm">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill text-warning me-3" style="font-size: 1.5rem;"></i>
                                <div class="flex-grow-1">
                                    <strong>Perhatian!</strong>
                                    <p class="mb-0">Barang ini belum memiliki barcode.</p>
                                </div>
                                <a href="/dashboard/goods/{{ $good->id }}/generate-barcode"
                                   class="btn-warning-umkm">
                                    <i class="bi bi-qr-code"></i>
                                    Generate Barcode
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between pt-3">
                            <a href="/dashboard/goods" class="btn-secondary-umkm">
                                <i class="bi bi-arrow-left"></i>
                                Kembali
                            </a>
                            <button type="submit" class="btn-warning-umkm">
                                <i class="bi bi-save"></i>
                                Update Barang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleExpiredField() {
    const typeSelect = document.getElementById('type');
    const expiredSection = document.getElementById('expired-section');
    const expiredInput = document.getElementById('expired_date');

    if (typeSelect.value === 'makanan' || typeSelect.value === 'non_makanan') {
        expiredSection.classList.add('show');
        expiredInput.required = true;
    } else {
        expiredSection.classList.remove('show');
        expiredInput.required = false;
        expiredInput.value = '';
    }
}

function calculateSellingPrice() {
    const hargaAsli = parseFloat(document.getElementById('harga_asli').value) || 0;
    const typeSelect = document.getElementById('type');
    const priceInfo = document.getElementById('price-info');
    
    if (hargaAsli > 0 && typeSelect.value) {
        const markup = typeSelect.value === 'makanan' ? 0.02 : 0.05;
        const hargaJual = hargaAsli + (hargaAsli * markup);
        const profit = hargaJual - hargaAsli;
        const markupPercent = (markup * 100).toFixed(0);
        
        document.getElementById('display-harga-asli').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(hargaAsli);
        document.getElementById('markup-percent').textContent = markupPercent + '%';
        document.getElementById('display-harga-jual').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(hargaJual);
        document.getElementById('display-profit').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(profit);
        
        priceInfo.style.display = 'block';
    }
}

function toggleWholesaleFields() {
    const checkbox = document.getElementById('is_grosir_active');
    const wholesaleFields = document.getElementById('wholesale-fields');
    const minQtyInput = document.getElementById('min_qty_grosir');
    const hargaGrosirInput = document.getElementById('harga_grosir');

    if (checkbox.checked) {
        wholesaleFields.classList.add('show');
        minQtyInput.required = true;
        hargaGrosirInput.required = true;
    } else {
        wholesaleFields.classList.remove('show');
        minQtyInput.required = false;
        hargaGrosirInput.required = false;
        minQtyInput.value = '';
        hargaGrosirInput.value = '';
    }
}

function toggleTebusMusahFields() {
    const checkbox = document.getElementById('is_tebus_murah_active');
    const tebusMusahFields = document.getElementById('tebus-murah-fields');
    const minTotalInput = document.getElementById('min_total_tebus_murah');
    const hargaTebusMusahInput = document.getElementById('harga_tebus_murah');

    if (checkbox.checked) {
        tebusMusahFields.classList.add('show');
        minTotalInput.required = true;
        hargaTebusMusahInput.required = true;
    } else {
        tebusMusahFields.classList.remove('show');
        minTotalInput.required = false;
        hargaTebusMusahInput.required = false;
        minTotalInput.value = '';
        hargaTebusMusahInput.value = '';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleExpiredField();
    toggleWholesaleFields();
    toggleTebusMusahFields();
    calculateSellingPrice();
});
</script>
@endsection
