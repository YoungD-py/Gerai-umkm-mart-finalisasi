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
        padding: 1.5rem; /* [RESPONSIVE] Mengurangi padding di layar kecil */
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
    
    .btn-warning-umkm, .btn-secondary-umkm {
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

    .btn-warning-umkm {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        border: none;
        color: #000;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-warning-umkm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 193, 7, 0.3);
        color: #000;
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
        color: #343a40;
        text-align: center;
        margin-bottom: 30px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
    
    .required {
        color: #dc3545;
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
        <h1><i class="bi bi-pencil-square"></i> EDIT BIAYA OPERASIONAL</h1>
        <p>Perbarui rincian pengeluaran operasional</p>
    </div>

    <div class="row justify-content-center">
        {{-- [RESPONSIVE] Mengubah col-lg-8 menjadi lebih fleksibel --}}
        <div class="col-xl-8 col-lg-10 col-md-12">
            <div class="umkm-card">
                <div class="umkm-card-header">
                    <h3 class="umkm-card-title">
                        <i class="bi bi-pencil-square"></i>
                        Form Edit Biaya
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

                    <form method="post" action="{{ route('biayaoperasional.update', $biayaOperasional->id) }}" enctype="multipart/form-data">
                        @method('put')
                        @csrf
                        
                        <div class="mb-3">
                            <label for="uraian" class="form-label">
                                <i class="bi bi-chat-left-text text-warning"></i>
                                Uraian/Keterangan <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control @error('uraian') is-invalid @enderror" id="uraian"
                                name="uraian" required autofocus value="{{ old('uraian', $biayaOperasional->uraian) }}" placeholder="Contoh: Pembelian ATK">
                            @error('uraian')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="nominal" class="form-label">
                                    <i class="bi bi-cash-coin text-warning"></i>
                                    Nominal <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('nominal') is-invalid @enderror" id="nominal"
                                        name="nominal" required value="{{ old('nominal', $biayaOperasional->nominal) }}" min="0" placeholder="Contoh: 50000">
                                </div>
                                @error('nominal')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="qty" class="form-label">
                                    <i class="bi bi-box text-warning"></i>
                                    Kuantitas (Qty) <span class="required">*</span>
                                </label>
                                <input type="number" class="form-control @error('qty') is-invalid @enderror" id="qty"
                                    name="qty" required value="{{ old('qty', $biayaOperasional->qty) }}" min="1" placeholder="Contoh: 1">
                                @error('qty')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">
                                <i class="bi bi-calendar-event text-warning"></i>
                                Tanggal <span class="required">*</span>
                            </label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal"
                                name="tanggal" required value="{{ old('tanggal', \Carbon\Carbon::parse($biayaOperasional->tanggal)->format('Y-m-d')) }}">
                            @error('tanggal')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="bukti_resi" class="form-label">
                                <i class="bi bi-receipt text-warning"></i>
                                Ganti Bukti/Resi (Opsional)
                            </label>
                            
                            @if($biayaOperasional->bukti_resi)
                                <div class="mb-2">
                                    <p class="form-label mb-1" style="font-size: 0.9rem;">Bukti Saat Ini:</p>
                                    <img src="{{ asset('storage/' . $biayaOperasional->bukti_resi) }}" class="img-fluid" style="max-width: 300px; border-radius: 15px; border: 2px solid #e9ecef;">
                                </div>
                            @endif

                            <img class="img-preview img-fluid mb-3 col-sm-5" style="display: none; border-radius: 15px;">
                            <input class="form-control @error('bukti_resi') is-invalid @enderror" type="file" id="bukti_resi" name="bukti_resi" onchange="previewImage()">
                            <small class="text-muted mt-2 d-block">Kosongkan jika tidak ingin mengubah bukti.</small>
                            @error('bukti_resi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid d-sm-flex justify-content-sm-between pt-3 border-top gap-2">
                            <a href="{{ route('biayaoperasional.index') }}" class="btn btn-secondary-umkm">
                                <i class="bi bi-arrow-left"></i>
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-warning-umkm">
                                <i class="bi bi-save"></i>
                                Update Biaya
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage() {
        const image = document.querySelector('#bukti_resi');
        const imgPreview = document.querySelector('.img-preview');

        imgPreview.style.display = 'block';

        const oFReader = new FileReader();
        oFReader.readAsDataURL(image.files[0]);

        oFReader.onload = function(oFREvent) {
            imgPreview.src = oFREvent.target.result;
        }
    }
</script>
@endsection
