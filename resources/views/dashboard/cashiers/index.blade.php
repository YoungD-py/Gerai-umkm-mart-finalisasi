@extends('dashboard.layouts.main')

@section('container')
    <style>
        /* --- CSS Styles adapted for Cashier Dashboard --- */
        .cashier-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.9));
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .cashier-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .cashier-card-header {
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
            background: rgba(255, 255, 255, 0.1);
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

        .btn-print {
            background: linear-gradient(135deg, #28a745, #1e7e34);
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.875rem;
        }

        .btn-print:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
            color: white;
            text-decoration: none;
            background: linear-gradient(135deg, #218838, #1c7430);
        }

        .form-control,
        .form-select {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            padding: 12px 20px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            background: white;
        }

        .table-cashier {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .table-cashier thead th {
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
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
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

        .pagination-wrapper .pagination {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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

        @media (min-width: 768px) {
            .page-title h1 {
                font-size: 2.5rem;
            }
        }
    </style>

    <div class="container-fluid py-4">
        <div class="page-title">
            <h1><i class="bi bi-cash-register"></i> SISTEM KASIR</h1>
            <p>Lakukan transaksi penjualan dan lihat riwayat transaksi hari ini</p>
        </div>

        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert"
                style="border-radius: 15px; border: none;">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="cashier-card">
            <div class="cashier-card-header">
                <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center">
                    <h3 class="cashier-card-title mb-2 mb-md-0">
                        <i class="bi bi-receipt"></i>
                        Transaksi Hari Ini
                    </h3>
                    <a href="/dashboard/cashier/quick-transaction" class="btn btn-cashier btn-cashier-sm w-100 w-md-auto">
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
                                <th class="d-none d-lg-table-cell">Petugas</th>
                                <th class="d-none d-md-table-cell">Metode Bayar</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $key => $transaction)
                                <tr>
                                    {{-- [PERUBAHAN] Menggunakan nomor urut dari pagination --}}
                                    <td><strong>{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}</strong>
                                    </td>
                                    <td>
                                        <i class="bi bi-hash text-primary"></i>
                                        {{ $transaction->no_nota }}
                                    </td>
                                    <td style="white-space:nowrap;">
                                        <i class="bi bi-clock text-muted me-1"></i>
                                        {{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="d-none d-lg-table-cell">{{ $transaction->user->nama }}</td>
                                    <td class="d-none d-md-table-cell">{{ $transaction->metode_pembayaran }}</td>
                                    <td>
                                        @if(strtolower(trim($transaction->status)) == 'lunas')
                                            <span class="badge bg-success">{{ $transaction->status }}</span>
                                        @else
                                            <span class="badge bg-warning text-dark">{{ $transaction->status }}</span>
                                        @endif
                                    </td>
                                    <td><strong>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</strong></td>
                                    <td class="text-center">
                                        <!-- <form method="post" action="/dashboard/cashiers/nota" class="d-inline" onsubmit="handleFormSubmit(this)">
                                            @csrf
                                            <input type="hidden" name="no_nota" value="{{ $transaction->no_nota }}">
                                            <button class="btn btn-primary btn-sm" type="submit" title="Unduh Nota">
                                                <i class="bi bi-download"></i>
                                                <span class="d-none d-md-inline">Unduh Nota</span>
                                            </button>
                                        </form> -->
                                        <a href="/dashboard/cashiers/print-nota?no_nota={{ $transaction->no_nota }}"
                                            class="btn btn-print" target="_blank" title="Cetak Nota">
                                            <i class="bi bi-printer"></i>
                                            <span class="d-none d-md-inline">Cetak Nota</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
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
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- [BARU] Menambahkan link pagination --}}
                @if($transactions->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        <div class="pagination-wrapper">
                            {{ $transactions->links() }}
                        </div>
                    </div>
                @endif
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
                    <span class="d-none d-md-inline">Loading...</span>
                `;

                setTimeout(function () {
                    button.disabled = false;
                    button.innerHTML = originalButtonHTML;
                }, 3000);
            }
        }
    </script>
@endsection
