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
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        padding: 20px;
        border-radius: 20px 20px 0 0;
        position: relative;
        overflow: hidden;
    }

    .umkm-card-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-umkm {
        background: linear-gradient(135deg, #206BC4, #4A90E2);
        border: none;
        border-radius: 15px;
        padding: 8px 15px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
    }

    .btn-umkm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
        color: white;
        text-decoration: none;
    }

    .stock-low {
        background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(200, 35, 51, 0.1));
        border-left: 4px solid #dc3545;
    }

    .stock-medium {
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(224, 168, 0, 0.1));
        border-left: 4px solid #ffc107;
    }

    .stock-high {
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1));
        border-left: 4px solid #28a745;
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


    .search-box {
        background: white;
        border-radius: 15px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .form-control {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 10px 15px;
    }

    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .table {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .table thead th {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border: none;
        font-weight: 600;
        padding: 15px;
    }

    .table tbody td {
        padding: 15px;
        vertical-align: middle;
        border-color: #f8f9fa;
    }

    .badge-stock {
        padding: 8px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-low {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }

    .badge-medium {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        color: #000;
    }

    .badge-high {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }

    @media (min-width: 768px) {
        .page-title h1 {
            font-size: 2.5rem;
        }
    }

    /* Pagination yang lebih jelas */
    .pagination-wrapper .pagination {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .pagination-wrapper .page-link {
        border: none;
        padding: 12px 16px;
        color: #28a745; 
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    .pagination-wrapper .page-link:hover {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        transform: translateY(-1px);
    }
    .pagination-wrapper .page-item.active .page-link {
        background: linear-gradient(135deg, #28a745, #20c997);
        border-color: #28a745;
        color: white;
        font-weight: 700;
    }
    .pagination-wrapper .page-item .page-link[aria-label="Previous"],
    .pagination-wrapper .page-item .page-link[aria-label="Next"] {
        color: #28a745;
        font-weight: bold;
        font-size: 1.1rem;
    }
    .pagination-wrapper .page-item.disabled .page-link {
        color: #a5a5a5;
        background: transparent;
    }

    .restock-qty-large {
        font-size: 1.1rem; 
        font-weight: bold; 
        color: #28a745; 
    }

    .restock-qty-large + small {
    font-size: 0.8rem; 
    }
</style>

<div class="container-fluid py-4">
    <div class="page-title">
        <h1>📦 RESTOCK BARANG</h1>
        <p>Kelola stok barang di inventori GERAI UMKM MART</p>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="umkm-card">
                <div class="umkm-card-header">
                    <h3 class="umkm-card-title">
                        <i class="bi bi-boxes"></i>
                        Daftar Barang untuk Restock
                    </h3>
                </div>

                <div class="card-body p-3 p-md-4">
                    <div class="search-box">
                        <form action="/dashboard/restock" id="search-form">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Cari nama barang atau mitra..."
                                               name="search" value="{{ request('search') }}">
                                        <button class="btn btn-umkm" type="submit">
                                            <i class="bi bi-search"></i>
                                            <span class="d-none d-sm-inline ms-1">Cari</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <select name="mitra" class="form-select" onchange="this.form.submit()">
                                        <option value="">Semua Mitra</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('mitra') == $category->id ? 'selected' : '' }}>
                                                {{ $category->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if(request('sort_stok'))
                                <input type="hidden" name="sort_stok" value="{{ request('sort_stok') }}">
                            @endif
                            @if(request('sort_mitra'))
                                <input type="hidden" name="sort_mitra" value="{{ request('sort_mitra') }}">
                            @endif
                            @if(request('sort_status'))
                                <input type="hidden" name="sort_status" value="{{ request('sort_status') }}">
                            @endif
                            @if(request('filter_status'))
                                <input type="hidden" name="filter_status" value="{{ request('filter_status') }}">
                            @endif
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">NO</th>
                                    <th scope="col">Nama Barang</th>
                                    <th scope="col" class="d-none d-md-table-cell">
                                        Mitra
                                        <button type="button" class="btn btn-sm btn-light ms-2 sort-toggle"
                                            data-sort-param="mitra"
                                            data-sort-order="{{ request('sort_mitra', 'none') }}"
                                            title="Urutkan berdasarkan mitra">
                                            <i class="bi {{ request('sort_mitra') == 'asc' ? 'bi-sort-up' : (request('sort_mitra') == 'desc' ? 'bi-sort-down' : 'bi-arrow-down-up') }}"></i>
                                        </button>
                                    </th>
                                    <th scope="col">
                                        Stok
                                        <button type="button" class="btn btn-sm btn-light ms-2 sort-toggle"
                                            data-sort-param="stok"
                                            data-sort-order="{{ request('sort_stok', 'none') }}"
                                            title="Urutkan berdasarkan stok">
                                            <i class="bi {{ request('sort_stok') == 'asc' ? 'bi-sort-up' : (request('sort_stok') == 'desc' ? 'bi-sort-down' : 'bi-arrow-down-up') }}"></i>
                                        </button>
                                    </th>
                                    <th scope="col" class="d-none d-sm-table-cell">
                                        Status
                                        <button type="button" class="btn btn-sm btn-light ms-2 status-filter-toggle"
                                            data-filter-status="{{ request('filter_status', 'none') }}"
                                            title="Filter berdasarkan status stok">
                                            @if(request('filter_status') == 'aman')
                                                <i class="bi bi-check-circle text-success"></i>
                                            @elseif(request('filter_status') == 'sedang')
                                                <i class="bi bi-exclamation-triangle text-warning"></i>
                                            @elseif(request('filter_status') == 'rendah')
                                                <i class="bi bi-x-circle text-danger"></i>
                                            @else
                                                <i class="bi bi-funnel"></i>
                                            @endif
                                        </button>
                                    </th>
                                    <th scope="col" class="d-none d-lg-table-cell">Harga</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($goods as $good)
                                    @php
                                        $stockClass = '';
                                        $stockBadge = '';
                                        $stockText = '';

                                        if ($good->stok <= 5) {
                                            $stockClass = 'stock-low';
                                            $stockBadge = 'badge-low';
                                            $stockText = 'Rendah';
                                        } elseif ($good->stok <= 20) {
                                            $stockClass = 'stock-medium';
                                            $stockBadge = 'badge-medium';
                                            $stockText = 'Sedang';
                                        } else {
                                            $stockClass = 'stock-high';
                                            $stockBadge = 'badge-high';
                                            $stockText = 'Aman';
                                        }
                                    @endphp
                                    <tr class="{{ $stockClass }}">
                                        <td>{{ ($goods->currentPage() - 1) * $goods->perPage() + $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="mb-0">{{ $good->nama }}</h6>
                                                    <small class="text-muted">{{ $good->type }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <span class="badge bg-success px-3 py-2">
                                                <i class="bi bi-shop me-1"></i>
                                                {{ $good->category ? $good->category->nama : 'Tidak ada mitra' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="fw-bold fs-5">{{ $good->stok }}</span>
                                                <small class="text-muted"> unit</small>
                                                <br>
                                                @php
                                                    $restockNeeded = 0;
                                                    if ($good->stok <= 5) {
                                                        $restockNeeded = 25 - $good->stok; 
                                                    } elseif ($good->stok <= 20) {
                                                        $restockNeeded = 30 - $good->stok; 
                                                    }
                                                @endphp
                                                <!-- @if($restockNeeded > 0)
                                                    <small class="text-success fw-bold">(+{{ $restockNeeded }})</small>
                                                @endif -->
                                            </div>
                                        </td>
                                        <td class="d-none d-sm-table-cell">
                                            <span class="badge badge-stock {{ $stockBadge }}">
                                                {{ $stockText }}
                                            </span>
                                        </td>
                                        <td class="d-none d-lg-table-cell">Rp {{ number_format($good->harga, 0, ',', '.') }}</td>
                                        <td>
                                            <a href="/dashboard/restock/{{ $good->id }}/edit"
                                               class="btn-umkm">
                                                <i class="bi bi-plus-circle"></i>
                                                <span class="d-none d-sm-inline ms-1">Restock</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $goods->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="umkm-card">
                <div class="umkm-card-header">
                    <h3 class="umkm-card-title">
                        <i class="bi bi-clock-history"></i>
                        Riwayat Restock Barang
                    </h3>
                </div>

                <div class="card-body p-3 p-md-4">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">NO</th>
                                    <th scope="col">Nama Barang</th>
                                    <th scope="col">Tanggal Restock</th>
                                    <th scope="col" class="d-none d-md-table-cell">Mitra Binaan</th>
                                    <th scope="col" class="d-none d-md-table-cell">Stok Tambahan</th>
                                    <th scope="col" class="d-none d-lg-table-cell">Petugas</th>
                                    <th scope="col">Keterangan</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($restockHistory as $restock)
                                    <tr>
                                        <td>{{ ($restockHistory->currentPage() - 1) * $restockHistory->perPage() + $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="mb-0">{{ $restock->good->nama ?? 'Barang Terhapus' }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ \Carbon\Carbon::parse($restock->tgl_restock)->format('d/m/Y') }}</span>
                                            <br>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($restock->created_at)->format('H:i') }}</small>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <span class="badge bg-success px-3 py-2">
                                                <i class="bi bi-shop me-1"></i>
                                                {{ $restock->good->category->nama ?? 'Tidak ada mitra' }}
                                            </span>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <div>
                                                <!-- <span class="text-success fw-bold">{{ $restock->good->stok }}</span> -->
                                                <!-- <small class="text-muted"> unit</small> -->
                                                <br>
                                                <span class="restock-qty-large">(+{{ $restock->qty_restock }})</span> </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-lg-table-cell">
                                            <span class="badge bg-primary">
                                                <i class="bi bi-person-check text-white me-1"></i>
                                                {{ $restock->user->nama ?? 'User Terhapus' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $restock->keterangan ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-black" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu shadow">
                                                    <li>
                                                        <a class="dropdown-item" href="/dashboard/restock/{{ $restock->id }}/edit-restock">
                                                            <i class="bi bi-pencil me-2 text-warning"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="/dashboard/restock/{{ $restock->id }}" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data restock ini? Stok akan dikembalikan.')">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="bi bi-trash me-2"></i>Hapus
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            Belum ada riwayat restock barang
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- History Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $restockHistory->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Added JavaScript for sorting functionality like in returns page -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sortButtons = document.querySelectorAll('.sort-toggle');
        sortButtons.forEach(button => {
            button.addEventListener('click', function() {
                const sortParam = this.getAttribute('data-sort-param');
                const currentOrder = this.getAttribute('data-sort-order');
                const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.set(sortParam, newOrder);
                window.location.search = urlParams.toString();
            });
        });

        const statusFilterButton = document.querySelector('.status-filter-toggle');
        statusFilterButton.addEventListener('click', function() {
            const currentStatus = this.getAttribute('data-filter-status');
            const newStatus = currentStatus === 'none' ? 'aman' : currentStatus === 'aman' ? 'sedang' : currentStatus === 'sedang' ? 'rendah' : 'none';
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('filter_status', newStatus);
            window.location.search = urlParams.toString();
        });
    });
</script>
