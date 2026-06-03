@extends('dashboard.layouts.main')

@section('container')
<style>
    body {
        background-color: #f8fafc;
        color: #1d273b;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    }

    .card {
        border: 1px solid #e6e7e9;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        border-radius: 16px;
        margin-bottom: 1.5rem;
        background-color: #fff;
        height: 100%;
        animation: fadeInUp 0.4s ease-in-out;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        overflow: hidden;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 18px 38px rgba(15, 23, 42, 0.12);
        border-color: #d7dde4;
    }
    .card-body { padding: 1.5rem; }
    .card-header {
        background: none;
        border-bottom: 1px solid #e6e7e9;
        padding: 1rem 1.5rem;
    }
    .card-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1d273b;
        margin: 0;
    }

    .page-header {
        display: flex;
        flex-wrap: wrap; 
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        gap: 1rem; 
    }
    .page-title {
        font-size: 1.5rem; 
        font-weight: 600;
    }

    .date-filter .btn {
        font-weight: 500;
    }
    .date-filter .btn.active {
        background-color: #206bc4;
        color: white;
        font-weight: 600;
    }
    .date-filter-wrapper {
        overflow-x: auto;
        padding-bottom: 10px; 
    }
    .date-filter {
        flex-wrap: nowrap; 
    }

    .stat-card .stat-label {
        color: #6c757d;
        font-size: 0.875rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .stat-card .stat-value {
        font-size: 1.75rem;
        font-weight: 600;
        line-height: 1.2;
        color: #1d273b;
        margin-top: 0.25rem;
    }
    .stat-card .stat-comparison {
        font-size: 0.8rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    .stat-card .stat-comparison.positive { color: #2fb344; }
    .stat-card .stat-comparison.negative { color: #d63939; }

    .card-alert-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .card-alert-title {
        font-size: 1.1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .list-group-flush .list-group-item {
        padding: 0.85rem 0.25rem;
    }
    .list-group-item .item-details .subtext {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .manager-toolbar {
        background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%);
        color: #fff;
        border-radius: 18px;
        padding: 1.15rem 1.25rem;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.16);
    }
    .manager-toolbar .toolbar-label {
        font-size: 0.85rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.72);
    }
    .manager-toolbar .toolbar-title {
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 0.15rem;
    }
    .metric-card {
        position: relative;
        overflow: hidden;
    }
    .metric-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(30, 64, 175, 0.05), rgba(14, 165, 233, 0.02));
        opacity: 0;
        transition: opacity 0.25s ease;
        pointer-events: none;
    }
    .metric-card:hover::before {
        opacity: 1;
    }
    .metric-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    .metric-value {
        font-size: 1.6rem;
        font-weight: 700;
        line-height: 1.1;
    }
    .metric-subtitle {
        color: #64748b;
        font-size: 0.86rem;
    }
    .section-card {
        border-radius: 20px;
    }
    .section-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .section-title {
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 0.15rem;
    }
    .section-subtitle {
        color: #64748b;
        font-size: 0.9rem;
    }
    .summary-item {
        display: flex;
        gap: 0.9rem;
        align-items: flex-start;
        padding: 0.95rem 1rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        height: 100%;
    }
    .summary-item .summary-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .summary-item .summary-label {
        color: #64748b;
        font-size: 0.84rem;
        margin-bottom: 0.2rem;
    }
    .summary-item .summary-value {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
    }
    .chart-shell {
        position: relative;
        min-height: 320px;
    }
    .chart-skeleton {
        position: absolute;
        inset: 0;
        display: grid;
        align-content: center;
        gap: 0.75rem;
        padding: 1.5rem;
        background: linear-gradient(90deg, #f8fafc 0%, #eef2ff 50%, #f8fafc 100%);
        background-size: 200% 100%;
        animation: skeletonPulse 1.4s ease-in-out infinite;
        border-radius: 16px;
        z-index: 1;
    }
    .chart-skeleton .sk-line,
    .chart-skeleton .sk-block {
        border-radius: 999px;
        background: rgba(148, 163, 184, 0.25);
    }
    .chart-skeleton .sk-line { height: 12px; }
    .chart-skeleton .sk-block { height: 180px; border-radius: 18px; }
    .chart-ready {
        position: relative;
        z-index: 2;
    }
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 250px;
        color: #64748b;
        text-align: center;
        padding: 1rem;
    }
    .empty-state i {
        font-size: 2.35rem;
        margin-bottom: 0.5rem;
        color: #94a3b8;
    }
    .timeline-modern {
        position: relative;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .timeline-modern::before {
        content: '';
        position: absolute;
        top: 8px;
        bottom: 8px;
        left: 18px;
        width: 2px;
        background: linear-gradient(to bottom, rgba(59, 130, 246, 0.35), rgba(148, 163, 184, 0.18));
    }
    .timeline-item-modern {
        position: relative;
        padding-left: 58px;
        margin-bottom: 1rem;
    }
    .timeline-item-modern:last-child { margin-bottom: 0; }
    .timeline-item-modern .timeline-dot {
        position: absolute;
        left: 0;
        top: 2px;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        box-shadow: 0 10px 18px rgba(15, 23, 42, 0.12);
    }
    .timeline-item-modern .timeline-title {
        font-weight: 700;
        margin-bottom: 0.2rem;
    }
    .timeline-item-modern .timeline-text {
        color: #64748b;
        font-size: 0.9rem;
    }
    .timeline-item-modern .timeline-time {
        color: #94a3b8;
        font-size: 0.82rem;
        margin-top: 0.25rem;
    }
    @keyframes skeletonPulse {
        0% { background-position: 100% 0; }
        100% { background-position: -100% 0; }
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (min-width: 768px) {
        .page-title {
            font-size: 1.75rem;
        }
        .date-filter-wrapper {
            overflow-x: visible;
        }
        .date-filter {
            flex-wrap: wrap;
        }
    }

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

</style>

        <div class="container-fluid py-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="mb-3">
                        <h2 class="page-title mb-1">{{ $greeting }}, {{ auth()->user()->nama }}!</h2>
                        <p class="text-muted mb-0" id="live-clock"></p>
                    </div>

                    <div class="d-flex flex-column flex-sm-row gap-2">
                        @if(!auth()->user()->isManajer())
                            <a href="/dashboard/goods/create" 
                            class="btn btn-primary btn-sm flex-grow-1 flex-sm-grow-0">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Barang
                            </a>
                            <a href="/dashboard/cashier/quick-transaction" 
                            class="btn btn-primary btn-sm flex-grow-1 flex-sm-grow-0">
                            <i class="bi bi-calculator me-2"></i>Tambah Transaksi
                            </a>
                        @else
                            <a href="/dashboard/users" 
                            class="btn btn-primary btn-sm flex-grow-1 flex-sm-grow-0">
                            <i class="bi bi-people me-2"></i>Lihat Data Pengguna
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>


    <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center mb-4">
        <div class="date-filter-wrapper mb-3 mb-md-0">
            <div class="btn-group date-filter">
                <a href="?range=all_time" class="btn btn-outline-secondary @if($range == 'all_time') active @endif">Semua Waktu</a>
                <a href="?range=today" class="btn btn-outline-secondary @if($range == 'today') active @endif">Hari Ini</a>
                <a href="?range=7_days" class="btn btn-outline-secondary @if($range == '7_days') active @endif">7 Hari</a>
                <a href="?range=30_days" class="btn btn-outline-secondary @if($range == '30_days') active @endif">30 Hari</a>
                <a href="?range=this_month" class="btn btn-outline-secondary @if($range == 'this_month') active @endif">Bulan Ini</a>
            </div>
        </div>
        <div class="text-muted fw-bold text-start text-md-end">Laporan untuk: {{ $rangeTitle }}</div>
    </div>

    @if(auth()->user()->isManajer())
        <div class="manager-toolbar mb-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <div class="toolbar-label mb-1">Dashboard Manajer</div>
                    <div class="toolbar-title">Executive overview untuk penjualan, profitabilitas, risiko stok, dan aktivitas penting.</div>
                    <div class="small text-white-50">Export tersedia langsung dari rentang laporan yang sedang dipilih.</div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('dashboard.manager.export-pdf', ['range' => $range]) }}" class="btn btn-light btn-sm">
                        <i class="bi bi-file-earmark-pdf me-2 text-danger"></i>Export PDF
                    </a>
                    <a href="{{ route('dashboard.manager.export-excel', ['range' => $range]) }}" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card stat-card metric-card h-100">
                    <div class="card-body d-flex align-items-start gap-3">
                        <div class="metric-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-pie-chart-fill"></i></div>
                        <div class="flex-grow-1">
                            <div class="metric-subtitle mb-1">Margin Profit (%)</div>
                            <div class="metric-value">{{ number_format($managerProfitMarginPercentage, 1) }}%</div>
                            <div class="metric-subtitle mt-1">Dari total pendapatan periode ini</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card stat-card metric-card h-100">
                    <div class="card-body d-flex align-items-start gap-3">
                        <div class="metric-icon bg-success bg-opacity-10 text-success"><i class="bi bi-graph-up-arrow"></i></div>
                        <div class="flex-grow-1">
                            <div class="metric-subtitle mb-1">Rata-rata transaksi harian</div>
                            <div class="metric-value">{{ number_format($managerAverageDailyTransactions, 1) }}</div>
                            <div class="metric-subtitle mt-1">Transaksi per hari pada rentang ini</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card stat-card metric-card h-100">
                    <div class="card-body d-flex align-items-start gap-3">
                        <div class="metric-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-calendar-x"></i></div>
                        <div class="flex-grow-1">
                            <div class="metric-subtitle mb-1">Total barang hampir expired</div>
                            <div class="metric-value">{{ number_format($managerExpiredSoonCount) }}</div>
                            <div class="metric-subtitle mt-1">Maksimum 30 hari ke depan</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card stat-card metric-card h-100">
                    <div class="card-body d-flex align-items-start gap-3">
                        <div class="metric-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-cart-x"></i></div>
                        <div class="flex-grow-1">
                            <div class="metric-subtitle mb-1">Produk tidak terjual 30 hari</div>
                            <div class="metric-value">{{ number_format($managerUnsoldProductsCount) }}</div>
                            <div class="metric-subtitle mt-1">Produk tanpa penjualan 30 hari terakhir</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card section-card h-100">
                    <div class="card-body">
                        <div class="section-heading">
                            <div>
                                <div class="section-title">Tren Penjualan</div>
                                <div class="section-subtitle">Pergerakan penjualan bulanan selama 12 bulan terakhir.</div>
                            </div>
                            <i class="bi bi-graph-up text-primary fs-4"></i>
                        </div>
                        <div class="chart-shell">
                            <div class="chart-skeleton" id="monthly-sales-skeleton">
                                <div class="sk-line w-75"></div>
                                <div class="sk-block"></div>
                            </div>
                            <div id="monthly-sales-chart" class="chart-ready"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card section-card h-100">
                    <div class="card-body">
                        <div class="section-heading">
                            <div>
                                <div class="section-title">Top 5 Produk Terlaris</div>
                                <div class="section-subtitle">Produk dengan penjualan tertinggi pada periode terpilih.</div>
                            </div>
                            <i class="bi bi-bar-chart-fill text-warning fs-4"></i>
                        </div>
                        <div class="chart-shell">
                            <div class="chart-skeleton" id="top-products-manager-skeleton">
                                <div class="sk-line w-50"></div>
                                <div class="sk-block"></div>
                            </div>
                            <div id="top-products-manager-chart" class="chart-ready"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card section-card mb-4">
            <div class="card-body">
                <div class="section-heading">
                    <div>
                        <div class="section-title">Risiko Operasional</div>
                        <div class="section-subtitle">Area yang butuh perhatian segera untuk menjaga stabilitas stok dan perputaran barang.</div>
                    </div>
                    <i class="bi bi-exclamation-triangle text-danger fs-4"></i>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-4">
                        <div class="summary-item">
                            <div class="summary-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-shield-exclamation"></i></div>
                            <div>
                                <div class="summary-label">Stok kritis</div>
                                <div class="summary-value">{{ number_format($lowStockCount) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="summary-item">
                            <div class="summary-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-calendar-x"></i></div>
                            <div>
                                <div class="summary-label">Barang hampir expired</div>
                                <div class="summary-value">{{ number_format($managerExpiredSoonCount) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="summary-item">
                            <div class="summary-icon bg-info bg-opacity-10 text-info"><i class="bi bi-cart-x"></i></div>
                            <div>
                                <div class="summary-label">Produk tidak terjual 30 hari</div>
                                <div class="summary-value">{{ number_format($managerUnsoldProductsCount) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="card border-0 bg-light h-100 mb-0">
                            <div class="card-body">
                                <div class="fw-semibold mb-2">Stok kritis teratas</div>
                                @if($lowStockItems->count() > 0)
                                    <ul class="list-unstyled mb-0">
                                        @foreach($lowStockItems->take(4) as $item)
                                            <li class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                <span>{{ $item->nama }}</span>
                                                <span class="badge bg-danger rounded-pill">{{ $item->stok }} stok</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="empty-state py-4 min-h-0">
                                        <i class="bi bi-check-circle"></i>
                                        <div class="fw-semibold text-dark">Tidak ada stok kritis</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card border-0 bg-light h-100 mb-0">
                            <div class="card-body">
                                <div class="fw-semibold mb-2">Barang hampir expired teratas</div>
                                @if($expiringSoonItems->count() > 0)
                                    <ul class="list-unstyled mb-0">
                                        @foreach($expiringSoonItems->take(4) as $item)
                                            <li class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                <span>{{ $item->nama }}</span>
                                                <span class="badge bg-warning text-dark rounded-pill">{{ $item->days_remaining <= 0 ? 'Hari ini' : $item->days_remaining . ' hari' }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="empty-state py-4 min-h-0">
                                        <i class="bi bi-shield-check"></i>
                                        <div class="fw-semibold text-dark">Tidak ada barang hampir expired</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card section-card mb-4">
            <div class="card-body">
                <div class="section-heading">
                    <div>
                        <div class="section-title">Aktivitas Terbaru</div>
                        <div class="section-subtitle">Transaksi, restock, dan perubahan data barang paling mutakhir.</div>
                    </div>
                    <i class="bi bi-lightning-charge text-info fs-4"></i>
                </div>
                @if($recentActivities->count() > 0)
                    <ul class="timeline-modern">
                        @foreach($recentActivities as $activity)
                            <li class="timeline-item-modern">
                                <div class="timeline-dot bg-{{ $activity['color'] }}">
                                    <i class="bi {{ $activity['icon'] }}"></i>
                                </div>
                                <div class="timeline-title">{{ $activity['title'] }}</div>
                                <div class="timeline-text">{{ $activity['description'] }}</div>
                                <div class="timeline-time">{{ $activity['timestamp']->format('d M Y, H:i') }}</div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <div class="fw-semibold text-dark mb-1">Belum ada aktivitas terbaru</div>
                        <div>Aktivitas transaksi, restock, dan perubahan data barang akan muncul di sini.</div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @unless(auth()->user()->isManajer())
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-label">
                        <span>Pendapatan</span>
                        <span class="stat-comparison {{ $revenueComparison >= 0 ? 'positive' : 'negative' }}">
                            <i class="bi {{ $revenueComparison >= 0 ? 'bi-arrow-up-short' : 'bi-arrow-down-short' }}"></i>
                            {{ number_format(abs($revenueComparison), 1) }}%
                        </span>
                    </div>
                    <div class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-label">
                        <span>Laba Kotor</span>
                        <span class="stat-comparison {{ $profitComparison >= 0 ? 'positive' : 'negative' }}">
                            <i class="bi {{ $profitComparison >= 0 ? 'bi-arrow-up-short' : 'bi-arrow-down-short' }}"></i>
                            {{ number_format(abs($profitComparison), 1) }}%
                        </span>
                    </div>
                    <div class="stat-value text-success">Rp {{ number_format($totalProfit, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-label">
                        <span>Biaya Operasional</span>
                    </div>
                    <div class="stat-value text-danger">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-label">
                        <span>Laba Bersih</span>
                    </div>
                    <div class="stat-value text-info">Rp {{ number_format($netProfit, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-label">
                        <span>Transaksi</span>
                        <span class="stat-comparison {{ $transactionComparison >= 0 ? 'positive' : 'negative' }}">
                             <i class="bi {{ $transactionComparison >= 0 ? 'bi-arrow-up-short' : 'bi-arrow-down-short' }}"></i>
                            {{ number_format(abs($transactionComparison), 1) }}%
                        </span>
                    </div>
                    <div class="stat-value">{{ number_format($totalTransactions) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Grafik Pendapatan & Laba</h3>
                    <div id="main-chart"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Produk Terlaris</h3></div>
                <div class="card-body">
                    <div id="top-products-chart" style="min-height: 250px;"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Mitra Terlaris</h3></div>
                <div class="card-body">
                    <div id="top-mitra-chart" style="min-height: 250px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <div class="card card-alert">
                <div class="card-body">
                    <div class="card-alert-header">
                        <h3 class="card-alert-title"><i class="bi bi-box-seam-fill text-danger"></i>Stok Segera Habis</h3>
                        @if(!auth()->user()->isManajer())
                            <a href="{{ url('/dashboard/goods?status=low_stock') }}" class="btn btn-sm btn-light">Lihat Semua</a>
                        @endif
                    </div>
                    <div class="row align-items-center">
                        <div class="col-12 col-md-6 mb-3 mb-md-0"><div id="low-stock-chart" style="min-height: 250px;"></div></div>
                        <div class="col-12 col-md-6">
                            @if($lowStockCount > 0)
                                <ul class="list-group list-group-flush">
                                    @foreach ($lowStockItems as $item)
                                        <li class="list-group-item">
                                            <div class="fw-bold">{{ $item->nama }} - <span class="text-danger fw-normal">Stok: {{ $item->stok }}</span></div>
                                            <div class="item-details"><div class="subtext">Mitra: {{ $item->category->nama ?? 'N/A' }}</div></div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-center text-muted py-5"><i class="bi bi-check-circle-fill fs-1 text-success"></i><p class="mt-2">Stok Aman!</p></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-alert">
                <div class="card-body">
                     <div class="card-alert-header">
                        <h3 class="card-alert-title"><i class="bi bi-calendar-x-fill text-warning"></i>Segera Expired</h3>
                        @if(!auth()->user()->isManajer())
                            <a href="{{ url('/dashboard/goods?status=expiring_soon') }}" class="btn btn-sm btn-light">Lihat Semua</a>
                        @endif
                    </div>
                    <div class="row align-items-center">
                        <div class="col-12 col-md-6 mb-3 mb-md-0"><div id="expiring-soon-chart" style="min-height: 250px;"></div></div>
                        <div class="col-12 col-md-6">
                            @if($expiringSoonCount > 0)
                                <ul class="list-group list-group-flush">
                                    @foreach ($expiringSoonItems as $item)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="fw-bold">{{ $item->nama }}</div>
                                                <div class="item-details"><div class="subtext">Exp: {{ \Carbon\Carbon::parse($item->expired_date)->format('d M Y') }}</div></div>
                                            </div>
                                            <span class="fw-bold {{ $item->days_remaining < 7 ? 'text-danger' : 'text-warning' }}">{{ $item->days_remaining <= 0 ? 'Hari Ini' : $item->days_remaining . ' hari' }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-center text-muted py-5"><i class="bi bi-shield-check-fill fs-1 text-success"></i><p class="mt-2">Aman dari Expired!</p></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endunless
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const formatCurrency = (value) => "Rp " + new Intl.NumberFormat('id-ID').format(value);

        @unless(auth()->user()->isManajer())
        const optionsMainChart = {
            series: [
                { name: 'Pendapatan', data: {!! $mainChartRevenue !!} },
                { name: 'Laba', data: {!! $mainChartProfit !!} }
            ],
            chart: { type: 'area', height: 350, toolbar: { show: false }, zoom: { enabled: false } },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            xaxis: { type: 'category', categories: {!! $mainChartLabels !!}, tooltip: { enabled: false } },
            yaxis: { labels: { formatter: (value) => formatCurrency(value) } },
            tooltip: { y: { formatter: (value) => formatCurrency(value) } },
            colors: ['#206bc4', '#2fb344'],
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1, stops: [0, 90, 100] } },
            legend: { horizontalAlign: 'right' }
        };
        new ApexCharts(document.querySelector("#main-chart"), optionsMainChart).render();

        const optionsTopProducts = {
            series: [{ name: 'Unit Terjual', data: {!! $topProductsSeries !!} }],
            chart: { type: 'bar', height: 250, toolbar: { show: false } },
            plotOptions: { bar: { borderRadius: 4, horizontal: true, distributed: true, } },
            dataLabels: { enabled: true, formatter: (val) => val + " unit", offsetX: 10, style: { colors: ['#333'] } },
            xaxis: { categories: {!! $topProductsLabels !!} },
            legend: { show: false },
            tooltip: { y: { formatter: (val) => val + " unit terjual" } }
        };
        if ({!! $topProductsSeries !!}.length > 0) {
            new ApexCharts(document.querySelector("#top-products-chart"), optionsTopProducts).render();
        } else {
            document.querySelector("#top-products-chart").innerHTML = '<div class="text-center text-muted py-5"><i class="bi bi-bar-chart-line fs-1"></i><p class="mt-2">Belum ada data penjualan.</p></div>';
        }

        const optionsTopMitra = {
            series: [{ name: 'Unit Terjual', data: {!! $topMitraSeries !!} }],
            chart: { type: 'bar', height: 250, toolbar: { show: false } },
            plotOptions: { bar: { borderRadius: 4, horizontal: true, distributed: true, } },
            dataLabels: { enabled: true, formatter: (val) => val + " unit", offsetX: 10, style: { colors: ['#333'] } },
            xaxis: { categories: {!! $topMitraLabels !!} },
            legend: { show: false },
            tooltip: { y: { formatter: (val) => val + " unit terjual" } }
        };
        if ({!! $topMitraSeries !!}.length > 0) {
            new ApexCharts(document.querySelector("#top-mitra-chart"), optionsTopMitra).render();
        } else {
            document.querySelector("#top-mitra-chart").innerHTML = '<div class="text-center text-muted py-5"><i class="bi bi-bar-chart-line fs-1"></i><p class="mt-2">Belum ada data penjualan.</p></div>';
        }

        const optionsLowStock = {
            series: {!! $lowStockChart_series !!},
            chart: { type: 'donut', height: 280 },
            labels: {!! $lowStockChart_labels !!},
            plotOptions: { pie: { donut: { labels: { show: true, total: { show: true, label: 'Total Barang', formatter: (w) => w.globals.seriesTotals.reduce((a, b) => a + b, 0) } } } } },
            colors: ['#d63939', '#f59f00', '#2fb344'],
            legend: { position: 'bottom', fontSize: '12px', markers: { width: 10, height: 10, radius: 4 }, itemMargin: { horizontal: 10 } },
            tooltip: { y: { formatter: (val) => val + " barang" } }
        };
        new ApexCharts(document.querySelector("#low-stock-chart"), optionsLowStock).render();
        @endunless

        @if(auth()->user()->isManajer())
        const managerChartEmpty = (icon, title, text) => `
            <div class="empty-state">
                <i class="bi ${icon}"></i>
                <div class="fw-semibold text-dark mb-1">${title}</div>
                <div>${text}</div>
            </div>
        `;

        const hideSkeleton = (elementId) => {
            const skeleton = document.getElementById(elementId);
            if (skeleton) {
                skeleton.style.display = 'none';
            }
        };

        const monthlyTrendLabels = {!! $monthlySalesTrendLabels !!};
        const monthlyTrendSeries = {!! $monthlySalesTrendSeries !!};
        const topProductsManagerLabels = {!! $topProductsLabels !!};
        const topProductsManagerSeries = {!! $topProductsSeries !!};

        const renderManagerChart = (selector, options, skeletonId, emptyStateHtml, hasData) => {
            const chartElement = document.querySelector(selector);
            if (!chartElement) {
                return;
            }

            if (!hasData) {
                chartElement.innerHTML = emptyStateHtml;
                hideSkeleton(skeletonId);
                return;
            }

            new ApexCharts(chartElement, options).render();
            hideSkeleton(skeletonId);
        };

        renderManagerChart(
            '#monthly-sales-chart',
            {
                series: [{ name: 'Penjualan', data: monthlyTrendSeries }],
                chart: { type: 'area', height: 320, toolbar: { show: false }, zoom: { enabled: false } },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                xaxis: { categories: monthlyTrendLabels },
                yaxis: { labels: { formatter: (value) => formatCurrency(value) } },
                tooltip: { y: { formatter: (value) => formatCurrency(value) } },
                colors: ['#1d4ed8'],
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.05, stops: [0, 100] } },
                grid: { borderColor: '#e2e8f0' }
            },
            'monthly-sales-skeleton',
            managerChartEmpty('bi-graph-up-arrow', 'Belum ada data penjualan bulanan', 'Grafik akan muncul setelah transaksi tersedia.'),
            Array.isArray(monthlyTrendSeries) && monthlyTrendSeries.some((value) => Number(value) > 0)
        );

        renderManagerChart(
            '#top-products-manager-chart',
            {
                series: [{ name: 'Unit Terjual', data: topProductsManagerSeries }],
                chart: { type: 'bar', height: 320, toolbar: { show: false } },
                plotOptions: { bar: { borderRadius: 10, horizontal: true, distributed: true, barHeight: '70%' } },
                dataLabels: { enabled: false },
                xaxis: { categories: topProductsManagerLabels },
                colors: ['#1d4ed8', '#0ea5e9', '#10b981', '#f59e0b', '#ef4444'],
                grid: { borderColor: '#e2e8f0' },
                tooltip: { y: { formatter: (value) => `${value} unit terjual` } }
            },
            'top-products-manager-skeleton',
            managerChartEmpty('bi-bar-chart-line', 'Belum ada produk terjual', 'Data top produk akan muncul setelah ada transaksi.'),
            Array.isArray(topProductsManagerSeries) && topProductsManagerSeries.some((value) => Number(value) > 0)
        );
        @endif

        function updateLiveClock() {
            const sekarang = new Date();
            const namaHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const clockElement = document.getElementById('live-clock');
            if (clockElement) {
                clockElement.textContent = `${namaHari[sekarang.getDay()]}, ${sekarang.getDate()} ${namaBulan[sekarang.getMonth()]} ${sekarang.getFullYear()} | ${String(sekarang.getHours()).padStart(2, '0')}:${String(sekarang.getMinutes()).padStart(2, '0')}`;
            }
        }
        updateLiveClock();
        setInterval(updateLiveClock, 1000);
    });
</script>
@endsection
