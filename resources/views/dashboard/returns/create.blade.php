@extends('dashboard.layouts.main')

@section('container')
<style>
    body {
        background: linear-gradient(135deg, #4b6cb7, #182848);
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
        background: linear-gradient(135deg, #00c9ff, #92fe9d);
        color: white;
        padding: 20px;
        border-radius: 20px 20px 0 0;
        font-weight: 700;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .return-card-body {
        padding: 30px;
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
        border-color: #4b6cb7;
        box-shadow: 0 0 0 0.2rem rgba(75, 108, 183, 0.3);
        background-color: white;
    }

    .btn-primary-return {
        background: linear-gradient(135deg, #4b6cb7, #182848);
        border: none;
        border-radius: 15px;
        padding: 12px 25px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-primary-return:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(75, 108, 183, 0.3);
    }

    .btn-secondary-return {
        background: #6c757d;
        border: none;
        border-radius: 15px;
        padding: 12px 25px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-secondary-return:hover {
        transform: translateY(-2px);
        background: #5a6268;
    }

    .alert-return {
        border-radius: 15px;
        border: none;
        padding: 15px 20px;
        background: rgba(75, 108, 183, 0.1);
        border-left: 4px solid #4b6cb7;
        color: #333;
    }

    .page-title {
        color: white;
        text-align: center;
        margin-bottom: 30px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .page-title h1 {
        font-size: 2.3rem;
        font-weight: 800;
    }

    .required {
        color: red;
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

    <div class="row justify-content-center">
        <div class="col-lg-8">
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
                                    <i class="bi bi-box text-primary"></i> Barang <span class="required">*</span>
                                </label>
                                <select class="form-select @error('good_id') is-invalid @enderror" name="good_id" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach ($goods as $good)
                                        <option value="{{ $good->id }}" {{ old('good_id') == $good->id ? 'selected' : '' }}>
                                            {{ $good->nama }} (Stok: {{ $good->stok }})
                                        </option>
                                    @endforeach
                                </select>
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

                        <div class="d-flex justify-content-between pt-3">
                            <a href="/dashboard/returns" class="btn-secondary-return">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn-primary-return">
                                <i class="bi bi-save"></i> Simpan Return
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
