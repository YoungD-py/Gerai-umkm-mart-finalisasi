@extends('dashboard.layouts.main')
@section('container')
<style>
    body {
        background: linear-gradient(135deg, #cccccc, #e0e0e0);
        font-family: 'Poppins', sans-serif;
        min-height: 100vh;
        color: #333;
    }

    .return-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .return-card-header {
        background: linear-gradient(135deg, #28a745, #218838);
        color: white;
        padding: 20px;
        border-radius: 20px 20px 0 0;
        position: relative;
        overflow: hidden;
    }

    .return-card-body {
        padding: 1.5rem; /* [RESPONSIVE] */
    }

    .form-section {
        background: rgba(255, 255, 255, 0.7);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-control, .form-select {
        border-radius: 12px;
        border: 2px solid #d6e0f5;
        padding: 12px 16px;
        background: #f0f4ff;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #28a745, #20c997;
        box-shadow: 0 0 0 0.2rem rgba(75, 108, 183, 0.3);
        background-color: white;
    }

    .btn-primary-return, .btn-secondary-return {
        border-radius: 15px;
        padding: 12px 25px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center; /* [RESPONSIVE] */
        gap: 8px;
        text-decoration: none;
    }

    .btn-primary-return {
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
    }

    .btn-primary-return:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(75, 108, 183, 0.3);
    }

    .btn-secondary-return {
        background: #6c757d;
        border: none;
    }

    .btn-secondary-return:hover {
        transform: translateY(-2px);
        background: #5a6268;
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

    .required {
        color: red;
    }

    .search-container {
        position: relative;
    }

    .search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 2px solid #d6e0f5;
        border-top: none;
        border-radius: 0 0 12px 12px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
    }

    .search-item {
        padding: 12px 16px;
        cursor: pointer;
        border-bottom: 1px solid #f0f4ff;
        transition: background-color 0.2s ease;
    }

    .search-item:hover {
        background-color: #f0f4ff;
    }

    .search-item:last-child {
        border-bottom: none;
    }

    .search-item-name {
        font-weight: 600;
        color: #333;
    }

    .search-item-info {
        font-size: 0.85rem;
        color: #666;
        margin-top: 2px;
    }

    .no-results {
        padding: 12px 16px;
        text-align: center;
        color: #999;
        font-style: italic;
    }

    .loading-results {
        padding: 12px 16px;
        text-align: center;
        color: #666;
    }

    @media (min-width: 768px) {
        .page-title h1 {
            font-size: 2.3rem;
        }
        .return-card-body {
            padding: 30px;
        }
    }
    .bi-calendar3,
    .text-primary,
    .bi-123,
    .bi-exclamation-triangle,
    .bi-person-gear {
        Color: #28a745 !important;
    }
</style>

<div class="container-fluid py-4">
    <div class="page-title">
        <h1><i class="bi bi-arrow-return-left"></i> RETURN BARANG</h1>
        <p>Isi form di bawah untuk mencatat barang yang diretur.</p>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div id="barcode-instruction-card" class="card shadow-sm border-0 mb-3" style="position: fixed; top: 100px; right: 20px; z-index: 1000; width: 300px; background: white; border-radius: 15px;">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <i class="bi bi-upc-scan" style="font-size: 1.5rem; color: #198754;"></i>
                <button type="button" class="btn-close btn-sm" onclick="closeInstructionCard()" aria-label="Close"></button>
            </div>
            <h6 class="card-title fw-bold mb-2">Pencarian Otomatis</h6>
            <p class="card-text text-muted small mb-0">Pindai/Scan barcode barang untuk langsung mengisi field Cari Barang.</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10 col-md-12">
            <div class="return-card">
                <div class="return-card-header">
                    <i class="bi bi-arrow-return-left"></i> Tambah Data Return Barang
                </div>

                <div class="return-card-body">
                    <form method="post" action="/dashboard/returns">
                        @csrf

                        <div class="form-section">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-calendar3 text-primary"></i> Tanggal Return <span class="required">*</span>
                                </label>
                                <input type="date" class="form-control @error('tgl_return') is-invalid @enderror"
                                       name="tgl_return" value="{{ old('tgl_return', date('Y-m-d')) }}" required>
                                @error('tgl_return')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-box text-primary"></i> Cari Barang <span class="required">*</span>
                                </label>
                                <div class="search-container">
                                    <input type="text"
                                           class="form-control @error('good_id') is-invalid @enderror"
                                           id="good-search"
                                           placeholder="Ketik nama barang atau barcode..."
                                           autocomplete="off">
                                    <input type="hidden" name="good_id" id="selected-good-id" value="{{ old('good_id') }}">
                                    <div class="search-results" id="search-results"></div>
                                </div>
                                @error('good_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-123 text-primary"></i> Jumlah Return <span class="required">*</span>
                                </label>
                                <input type="number" class="form-control @error('qty_return') is-invalid @enderror"
                                       name="qty_return" placeholder="Masukkan jumlah..." value="{{ old('qty_return') }}" min="1" required>
                                @error('qty_return')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-exclamation-triangle text-primary"></i> Alasan Return <span class="required">*</span>
                                </label>
                                <select class="form-select @error('alasan') is-invalid @enderror" name="alasan" required>
                                    <option value="">-- Pilih Alasan --</option>
                                    @foreach(['Rusak', 'Cacat', 'Kadaluarsa', 'Salah Kirim', 'Lainnya'] as $alasan)
                                        <option value="{{ $alasan }}" {{ old('alasan') == $alasan ? 'selected' : '' }}>{{ $alasan }}</option>
                                    @endforeach
                                </select>
                                @error('alasan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-person-gear text-primary"></i> Administrator <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" value="{{ auth()->user()->nama }}" readonly>
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-chat-left-text text-primary"></i> Keterangan (Opsional)
                                </label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                          name="keterangan" rows="3"
                                          placeholder="Tambahkan keterangan...">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid d-sm-flex justify-content-sm-between pt-3 gap-2">
                            <a href="/dashboard/returns" class="btn btn-secondary-return">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary-return">
                                <i class="bi bi-save"></i> Simpan Return
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function closeInstructionCard() {
    const card = document.getElementById('barcode-instruction-card');
    if (card) {
        card.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('good-search');
    const searchResults = document.getElementById('search-results');
    const selectedGoodId = document.getElementById('selected-good-id');
    let searchTimeout;
    let barcodeBuffer = '';
    let lastInputTime = Date.now();

    // Set initial value if editing
    if (selectedGoodId.value) {
        @if(old('good_id'))
            @foreach($goods as $good)
                @if($good->id == old('good_id'))
                    searchInput.value = '{{ $good->nama }} ({{ $good->barcode }})';
                @endif
            @endforeach
        @endif
    }

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();

        clearTimeout(searchTimeout);

        if (query.length < 2) {
            hideResults();
            selectedGoodId.value = '';
            return;
        }

        showLoading();

        searchTimeout = setTimeout(() => {
            fetch(`/dashboard/returns/search-goods?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displayResults(data);
                })
                .catch(error => {
                    console.error('Search error:', error);
                    showError();
                });
        }, 300);
    });

    document.addEventListener('keydown', function(e) {
        const currentTime = Date.now();

        if (currentTime - lastInputTime > 50) {
            barcodeBuffer = '';
        }

        if (e.key === 'Enter') {
            if (barcodeBuffer.length > 3) {
                e.preventDefault();
                processBarcodeScan(barcodeBuffer);
            }
            barcodeBuffer = '';
        } else if (e.key.length === 1) {
            barcodeBuffer += e.key;
        }

        lastInputTime = currentTime;
    });

    function showLoading() {
        searchResults.innerHTML = '<div class="loading-results"><i class="bi bi-hourglass-split"></i> Mencari...</div>';
        searchResults.style.display = 'block';
    }

    function showError() {
        searchResults.innerHTML = '<div class="no-results"><i class="bi bi-exclamation-triangle"></i> Terjadi kesalahan saat mencari</div>';
        searchResults.style.display = 'block';
    }

    function displayResults(goods) {
        if (goods.length === 0) {
            searchResults.innerHTML = '<div class="no-results"><i class="bi bi-search"></i> Tidak ada barang ditemukan</div>';
        } else {
            let html = '';
            goods.forEach(good => {
                html += `
                    <div class="search-item" data-id="${good.id}" data-name="${good.nama}" data-barcode="${good.barcode}">
                        <div class="search-item-name">${good.nama}</div>
                        <div class="search-item-info">Barcode: ${good.barcode || '-'} | Stok: ${good.stok}</div>
                    </div>
                `;
            });
            searchResults.innerHTML = html;

            document.querySelectorAll('.search-item').forEach(item => {
                item.addEventListener('click', function() {
                    selectGood({
                        id: this.dataset.id,
                        nama: this.dataset.name,
                        barcode: this.dataset.barcode
                    });
                });
            });
        }
        searchResults.style.display = 'block';
    }

    function selectGood(good) {
        const barcodeValue = good.barcode || '-';
        searchInput.value = `${good.nama} (${barcodeValue})`;
        selectedGoodId.value = good.id;
        hideResults();
    }

    function processBarcodeScan(barcode) {
        const normalizedBarcode = barcode.trim().toLowerCase();
        if (!normalizedBarcode) return;

        fetch(`/dashboard/returns/search-goods?q=${encodeURIComponent(barcode.trim())}`)
            .then(response => response.json())
            .then(data => {
                if (!Array.isArray(data) || data.length === 0) {
                    searchInput.value = barcode.trim();
                    selectedGoodId.value = '';
                    return;
                }

                const exactMatch = data.find(good => (good.barcode || '').toLowerCase() === normalizedBarcode);
                const selectedGood = exactMatch || data[0];
                selectGood(selectedGood);
            })
            .catch(error => {
                console.error('Barcode scan error:', error);
            });
    }

    function hideResults() {
        searchResults.style.display = 'none';
    }

    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            hideResults();
        }
    });

    searchInput.addEventListener('focus', function() {
        if (this.value.length >= 2) {
            searchResults.style.display = 'block';
        }
    });
});
</script>
@endsection
