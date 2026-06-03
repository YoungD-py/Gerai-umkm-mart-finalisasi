<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Dashboard Manajer</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 12px; }
        h1, h2, h3 { margin: 0 0 8px; }
        .muted { color: #6b7280; }
        .card { border: 1px solid #d1d5db; border-radius: 10px; padding: 12px; margin-bottom: 14px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 8px; vertical-align: top; }
        th { background: #f3f4f6; text-align: left; }
        .grid { width: 100%; }
        .grid td { border: none; padding: 0 8px 0 0; }
    </style>
</head>
<body>
    @php
        $topProductLabels = json_decode($topProductsLabels, true) ?? [];
        $topProductSeries = json_decode($topProductsSeries, true) ?? [];
        $monthlyTrendLabels = json_decode($monthlySalesTrendLabels, true) ?? [];
    @endphp

    <h1>Dashboard Manajer</h1>
    <p class="muted">Periode: {{ $rangeTitle }} | Dicetak: {{ now()->format('d M Y H:i') }}</p>

    <table class="grid" style="margin-bottom: 12px;">
        <tr>
            <td style="width: 25%;">
                <div class="card">
                    <strong>KPI Utama</strong><br>
                    Pendapatan: Rp {{ number_format($totalRevenue, 0, ',', '.') }}<br>
                    Laba Bersih: Rp {{ number_format($netProfit, 0, ',', '.') }}<br>
                    Margin: {{ number_format($managerProfitMarginPercentage, 1) }}%
                </div>
            </td>
            <td style="width: 25%;">
                <div class="card">
                    <strong>Tren Penjualan</strong><br>
                    {{ count($monthlyTrendLabels) }} periode bulanan
                </div>
            </td>
            <td style="width: 25%;">
                <div class="card">
                    <strong>Top 5 Produk Terlaris</strong><br>
                    {{ $managerTopProductName ?? '-' }}
                </div>
            </td>
            <td style="width: 25%;">
                <div class="card">
                    <strong>Risiko Operasional</strong><br>
                    Stok kritis: {{ number_format($lowStockCount) }}<br>
                    Hampir expired: {{ number_format($managerExpiredSoonCount) }}
                </div>
            </td>
        </tr>
    </table>

    <table class="grid" style="margin-bottom: 12px;">
        <tr>
            <td style="width: 50%;">
                <div class="card">
                    <h3>Top 5 Produk Terlaris</h3>
                    <table>
                        @forelse($topProductLabels as $index => $productName)
                            <tr>
                                <th>{{ $index + 1 }}</th>
                                <td>{{ $productName }} - {{ number_format($topProductSeries[$index] ?? 0) }} unit</td>
                            </tr>
                        @empty
                            <tr><td colspan="2">Belum ada data produk terlaris.</td></tr>
                        @endforelse
                    </table>
                </div>
            </td>
            <td style="width: 50%;">
                <div class="card">
                    <h3>Aktivitas Terbaru</h3>
                    <table>
                        <tr>
                            <th>Waktu</th>
                            <th>Aktivitas</th>
                        </tr>
                        @forelse ($recentActivities as $activity)
                            <tr>
                                <td>{{ $activity['timestamp']->format('d M Y H:i') }}</td>
                                <td>{{ $activity['title'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">Belum ada aktivitas terbaru.</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <table class="grid">
        <tr>
            <td style="width: 50%;">
                <div class="card">
                    <h3>Risiko Operasional</h3>
                    <table>
                        <tr><th>Produk tidak terjual 30 hari</th><td>{{ number_format($managerUnsoldProductsCount) }}</td></tr>
                        <tr><th>Barang hampir expired</th><td>{{ number_format($managerExpiredSoonCount) }}</td></tr>
                        <tr><th>Stok kritis</th><td>{{ number_format($lowStockCount) }}</td></tr>
                    </table>
                </div>
            </td>
            <td style="width: 50%;">
                <div class="card">
                    <h3>Tren Penjualan</h3>
                    <table>
                        <tr><th>Periode</th><td>{{ count($monthlyTrendLabels) }} bulan</td></tr>
                        <tr><th>Rata-rata transaksi harian</th><td>{{ number_format($managerAverageDailyTransactions, 1) }}</td></tr>
                        <tr><th>Margin profit</th><td>{{ number_format($managerProfitMarginPercentage, 1) }}%</td></tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>