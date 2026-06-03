<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Good;
use App\Models\Transaction;
use App\Models\Category; 
use App\Models\User;
use App\Models\BiayaOperasional;
use App\Models\Order; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Restock;
use App\Models\ReturnBarang;

use Barryvdh\DomPDF\Facade\Pdf;
class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard.index', $this->buildDashboardData($request));
    }

    public function exportPdf(Request $request)
    {
        $data = $this->buildDashboardData($request);
        $pdf = Pdf::loadView('dashboard.exports.manager-dashboard', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('dashboard-manajer-' . now()->format('Ymd-His') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $data = $this->buildDashboardData($request);

        return response()->streamDownload(function () use ($data) {
            echo $this->buildDashboardExcelHtml($data);
        }, 'dashboard-manajer-' . now()->format('Ymd-His') . '.xls', [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    private function buildDashboardData(Request $request): array
    {
        $range = $request->get('range', 'all_time');
        [$startDate, $endDate, $rangeTitle] = $this->parseDateRange($range);
        [$prevStartDate, $prevEndDate] = $this->parseDateRange($range, true);

        $totalRevenue = Transaction::where('status', 'LUNAS')->whereBetween('created_at', [$startDate, $endDate])->sum('total_harga');
        $totalTransactions = Transaction::where('status', 'LUNAS')->whereBetween('created_at', [$startDate, $endDate])->count();
        $totalProfit = $this->calculateProfit($startDate, $endDate);
        $totalExpenses = BiayaOperasional::whereBetween('created_at', [$startDate, $endDate])->sum('nominal');
        $netProfit = $totalProfit - $totalExpenses;

        $prevTotalRevenue = Transaction::where('status', 'LUNAS')->whereBetween('created_at', [$prevStartDate, $prevEndDate])->sum('total_harga');
        $prevTotalProfit = $this->calculateProfit($prevStartDate, $prevEndDate);
        $prevTotalTransactions = Transaction::where('status', 'LUNAS')->whereBetween('created_at', [$prevStartDate, $prevEndDate])->count();

        $revenueComparison = $this->calculatePercentageChange($totalRevenue, $prevTotalRevenue);
        $profitComparison = $this->calculatePercentageChange($totalProfit, $prevTotalProfit);
        $transactionComparison = $this->calculatePercentageChange($totalTransactions, $prevTotalTransactions);

        $chartData = $this->getChartData($startDate, $endDate, $range);

        $topSellingProducts = Order::join('transactions', 'orders.no_nota', '=', 'transactions.no_nota')
            ->join('goods', 'orders.good_id', '=', 'goods.id')
            ->where('transactions.status', 'LUNAS')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->select('goods.nama', DB::raw('SUM(orders.qty) as total_terjual'))
            ->groupBy('goods.nama')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get();

        $topProductsLabels = $topSellingProducts->pluck('nama');
        $topProductsSeries = $topSellingProducts->pluck('total_terjual');

        $topSellingMitra = Order::join('transactions', 'orders.no_nota', '=', 'transactions.no_nota')
            ->join('goods', 'orders.good_id', '=', 'goods.id')
            ->join('categories', 'goods.category_id', '=', 'categories.id')
            ->where('transactions.status', 'LUNAS')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->select('categories.nama', DB::raw('SUM(orders.qty) as total_terjual'))
            ->groupBy('categories.nama')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get();

        $topMitraLabels = $topSellingMitra->pluck('nama');
        $topMitraSeries = $topSellingMitra->pluck('total_terjual');

        $lowStockThreshold = 5;
        $lowStockItems = Good::where('stok', '<=', $lowStockThreshold)->orderBy('stok', 'asc')->limit(5)->get();
        $lowStockCount = Good::where('stok', '<=', $lowStockThreshold)->count();
        $veryLowStockCount = Good::whereBetween('stok', [1, 2])->count();
        $lowStockMidCount = Good::whereBetween('stok', [3, $lowStockThreshold])->count();
        $normalStockCount = Good::where('stok', '>', $lowStockThreshold)->count();

        $expiringSoonThresholdDays = 30;
        $now = Carbon::now();
        $expiringSoonItems = Good::whereNotNull('expired_date')->whereBetween('expired_date', [$now, $now->copy()->addDays($expiringSoonThresholdDays)])->select('*', DB::raw('DATEDIFF(expired_date, NOW()) as days_remaining'))->orderBy('expired_date', 'asc')->limit(5)->get();
        $expiringSoonCount = Good::whereNotNull('expired_date')->whereBetween('expired_date', [$now, $now->copy()->addDays($expiringSoonThresholdDays)])->count();
        $expiredVerySoonCount = Good::whereNotNull('expired_date')->whereBetween('expired_date', [$now, $now->copy()->addDays(6)])->count();
        $expiredSoonCount = Good::whereNotNull('expired_date')->whereBetween('expired_date', [$now->copy()->addDays(7), $now->copy()->addDays(30)])->count();
        $expiredSafeCount = Good::whereNotNull('expired_date')->where('expired_date', '>', $now->copy()->addDays(30))->count();
        $noExpiredDateCount = Good::whereNull('expired_date')->count();

        $rangeDays = max(1, $startDate->copy()->startOfDay()->diffInDays($endDate->copy()->endOfDay()) + 1);
        $profitMarginPercentage = $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0;
        $averageDailyTransactions = $totalTransactions / $rangeDays;
        $unsoldProductsLast30Days = Good::whereDoesntHave('orders', function ($query) use ($now) {
            $query->whereHas('transaction', function ($transactionQuery) use ($now) {
                $transactionQuery->where('status', 'LUNAS')->where('created_at', '>=', $now->copy()->subDays(30));
            });
        })->count();

        $monthlySalesTrend = $this->buildMonthlySalesTrend();
        $busyHourChart = $this->buildBusyHourChart($startDate, $endDate);
        $categoryPieChart = $this->buildCategoryPieChart($startDate, $endDate);
        $recentActivities = $this->buildRecentActivities();

        $hour = Carbon::now()->hour;
        $greeting = 'Selamat Datang';
        if ($hour < 11) {
            $greeting = 'Selamat Pagi';
        } elseif ($hour < 15) {
            $greeting = 'Selamat Siang';
        } elseif ($hour < 19) {
            $greeting = 'Selamat Sore';
        } else {
            $greeting = 'Selamat Malam';
        }

        return [
            'active' => 'dashboard',
            'greeting' => $greeting,
            'range' => $range,
            'rangeTitle' => $rangeTitle,
            'totalRevenue' => $totalRevenue,
            'totalProfit' => $totalProfit,
            'totalTransactions' => $totalTransactions,
            'netProfit' => $netProfit,
            'totalExpenses' => $totalExpenses,
            'revenueComparison' => $revenueComparison,
            'profitComparison' => $profitComparison,
            'transactionComparison' => $transactionComparison,
            'mainChartLabels' => json_encode($chartData['labels']),
            'mainChartRevenue' => json_encode($chartData['revenues']),
            'mainChartProfit' => json_encode($chartData['profits']),
            'topProductsLabels' => json_encode($topProductsLabels),
            'topProductsSeries' => json_encode($topProductsSeries),
            'topMitraLabels' => json_encode($topMitraLabels),
            'topMitraSeries' => json_encode($topMitraSeries),
            'lowStockItems' => $lowStockItems,
            'lowStockCount' => $lowStockCount,
            'lowStockChart_series' => json_encode([$veryLowStockCount, $lowStockMidCount, $normalStockCount]),
            'lowStockChart_labels' => json_encode(['Sangat Menipis (1-2)', "Menipis (3-{$lowStockThreshold})", "Stok Cukup (>{$lowStockThreshold})"]),
            'expiringSoonItems' => $expiringSoonItems,
            'expiringSoonCount' => $expiringSoonCount,
            'expiringSoonChart_series' => json_encode([$expiredVerySoonCount, $expiredSoonCount, $expiredSafeCount, $noExpiredDateCount]),
            'expiringSoonChart_labels' => json_encode(['Expired < 7 Hari', 'Expired 7-30 Hari', 'Expired > 30 Hari', 'Tanpa Tgl Expired']),
            'managerProfitMarginPercentage' => $profitMarginPercentage,
            'managerAverageDailyTransactions' => $averageDailyTransactions,
            'managerExpiredSoonCount' => $expiringSoonCount,
            'managerUnsoldProductsCount' => $unsoldProductsLast30Days,
            'managerTopProductName' => optional($topSellingProducts->first())->nama,
            'monthlySalesTrendLabels' => json_encode($monthlySalesTrend['labels']),
            'monthlySalesTrendSeries' => json_encode($monthlySalesTrend['series']),
            'busyHourChartLabels' => json_encode($busyHourChart['labels']),
            'busyHourChartSeries' => json_encode($busyHourChart['series']),
            'categoryPieChartLabels' => json_encode($categoryPieChart['labels']),
            'categoryPieChartSeries' => json_encode($categoryPieChart['series']),
            'recentActivities' => $recentActivities,
        ];
    }

    private function buildMonthlySalesTrend(): array
    {
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $monthlyData = Transaction::where('status', 'LUNAS')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month_key, SUM(total_harga) as total_revenue")
            ->groupBy('month_key')
            ->orderBy('month_key')
            ->get()
            ->keyBy('month_key');

        $labels = [];
        $series = [];
        $cursor = $startDate->copy();

        while ($cursor <= $endDate) {
            $key = $cursor->format('Y-m');
            $labels[] = $cursor->format('M Y');
            $series[] = (float) ($monthlyData[$key]->total_revenue ?? 0);
            $cursor->addMonth();
        }

        return ['labels' => $labels, 'series' => $series];
    }

    private function buildBusyHourChart(Carbon $startDate, Carbon $endDate): array
    {
        $hourlyData = Transaction::where('status', 'LUNAS')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('HOUR(created_at) as hour_key, COUNT(*) as total_transactions')
            ->groupBy('hour_key')
            ->pluck('total_transactions', 'hour_key');

        $labels = [];
        $series = [];

        for ($hour = 0; $hour < 24; $hour++) {
            $labels[] = str_pad((string) $hour, 2, '0', STR_PAD_LEFT) . ':00';
            $series[] = (int) ($hourlyData[$hour] ?? 0);
        }

        return ['labels' => $labels, 'series' => $series];
    }

    private function buildCategoryPieChart(Carbon $startDate, Carbon $endDate): array
    {
        $categoryData = Order::join('transactions', 'orders.no_nota', '=', 'transactions.no_nota')
            ->join('goods', 'orders.good_id', '=', 'goods.id')
            ->leftJoin('categories', 'goods.category_id', '=', 'categories.id')
            ->where('transactions.status', 'LUNAS')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->selectRaw('COALESCE(categories.nama, "Tanpa Kategori") as category_name, SUM(orders.qty) as total_sold')
            ->groupBy('category_name')
            ->orderByDesc('total_sold')
            ->limit(6)
            ->get();

        return [
            'labels' => $categoryData->pluck('category_name')->values(),
            'series' => $categoryData->pluck('total_sold')->values(),
        ];
    }

    private function buildRecentActivities()
    {
        $activities = collect();

        Transaction::with('user')
            ->latest()
            ->limit(5)
            ->get()
            ->each(function ($transaction) use (&$activities) {
                $activities->push([
                    'type' => 'transaction',
                    'icon' => 'bi-receipt-cutoff',
                    'color' => 'primary',
                    'title' => 'Transaksi ' . $transaction->no_nota,
                    'description' => 'Total Rp ' . number_format($transaction->total_harga ?? 0, 0, ',', '.') . ' oleh ' . optional($transaction->user)->nama,
                    'timestamp' => Carbon::parse($transaction->created_at),
                ]);
            });

        Restock::with(['good', 'user'])
            ->orderBy('tgl_restock', 'desc')
            ->limit(5)
            ->get()
            ->each(function ($restock) use (&$activities) {
                $activities->push([
                    'type' => 'restock',
                    'icon' => 'bi-box-arrow-in-down',
                    'color' => 'success',
                    'title' => 'Restock ' . optional($restock->good)->nama,
                    'description' => 'Tambah stok ' . number_format($restock->qty_restock ?? 0) . ' unit oleh ' . optional($restock->user)->nama,
                    'timestamp' => Carbon::parse($restock->tgl_restock),
                ]);
            });

        Good::with('category')
            ->whereColumn('updated_at', '>', 'created_at')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get()
            ->each(function ($good) use (&$activities) {
                $activities->push([
                    'type' => 'good-update',
                    'icon' => 'bi-pencil-square',
                    'color' => 'warning',
                    'title' => 'Perubahan data barang ' . $good->nama,
                    'description' => 'Kategori: ' . optional($good->category)->nama . ' | Stok: ' . number_format($good->stok ?? 0),
                    'timestamp' => Carbon::parse($good->updated_at),
                ]);
            });

        ReturnBarang::with(['good', 'user'])
            ->latest()
            ->limit(5)
            ->get()
            ->each(function ($returnItem) use (&$activities) {
                $activities->push([
                    'type' => 'return',
                    'icon' => 'bi-arrow-counterclockwise',
                    'color' => 'danger',
                    'title' => 'Return ' . optional($returnItem->good)->nama,
                    'description' => 'Qty return ' . number_format($returnItem->qty_return ?? 0) . ' unit',
                    'timestamp' => Carbon::parse($returnItem->created_at),
                ]);
            });

        return $activities->sortByDesc('timestamp')->values()->take(10);
    }

    private function buildDashboardExcelHtml(array $data): string
    {
        $rows = [];
        $rows[] = '<html><head><meta charset="UTF-8"></head><body>';
        $rows[] = '<table border="1" cellspacing="0" cellpadding="6">';
        $rows[] = '<tr><th colspan="2">Dashboard Manajer</th></tr>';
        $rows[] = '<tr><td>Rentang</td><td>' . e($data['rangeTitle']) . '</td></tr>';
        $rows[] = '<tr><td>Pendapatan</td><td>Rp ' . number_format($data['totalRevenue'], 0, ',', '.') . '</td></tr>';
        $rows[] = '<tr><td>Laba Bersih</td><td>Rp ' . number_format($data['netProfit'], 0, ',', '.') . '</td></tr>';
        $rows[] = '<tr><td>Margin Profit</td><td>' . number_format($data['managerProfitMarginPercentage'], 1) . '%</td></tr>';
        $rows[] = '<tr><td>Rata-rata Transaksi Harian</td><td>' . number_format($data['managerAverageDailyTransactions'], 1) . '</td></tr>';
        $rows[] = '<tr><td>Barang Hampir Expired</td><td>' . number_format($data['managerExpiredSoonCount']) . '</td></tr>';
        $rows[] = '<tr><td>Produk Tidak Terjual 30 Hari</td><td>' . number_format($data['managerUnsoldProductsCount']) . '</td></tr>';
        $rows[] = '</table>';
        $rows[] = '</body></html>';

        return implode('', $rows);
    }
    private function calculateProfit($startDate, $endDate)
    {
        return Order::join('transactions', 'orders.no_nota', '=', 'transactions.no_nota')
            ->join('goods', 'orders.good_id', '=', 'goods.id')
            ->where('transactions.status', 'LUNAS')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->sum(DB::raw('orders.qty * (orders.price - goods.harga_asli)'));
    }

    private function calculatePercentageChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return (($current - $previous) / $previous) * 100;
    }

    private function getChartData($startDate, $endDate, $range)
    {
        $labels = [];
        $revenues = [];
        $profits = [];
        $currentDate = $startDate->copy();

        if ($range === 'all_time' || $startDate->diffInDays($endDate) > 60) {
            $queryData = Order::join('transactions', 'orders.no_nota', '=', 'transactions.no_nota')
                ->join('goods', 'orders.good_id', '=', 'goods.id')
                ->where('transactions.status', 'LUNAS')
                ->whereBetween('transactions.created_at', [$startDate, $endDate])
                ->select(
                    DB::raw("DATE_FORMAT(transactions.created_at, '%b %Y') as month"),
                    DB::raw('SUM(orders.qty * orders.price) as total_revenue'),
                    DB::raw('SUM(orders.qty * (orders.price - goods.harga_asli)) as total_profit')
                )
                ->groupBy(DB::raw("YEAR(transactions.created_at)"), DB::raw("MONTH(transactions.created_at)"), 'month')
                ->orderByRaw('MIN(transactions.created_at)')
                ->get();

            foreach ($queryData as $data) {
                $labels[] = $data->month;
                $revenues[] = $data->total_revenue;
                $profits[] = $data->total_profit;
            }
        } else {
            while ($currentDate <= $endDate) {
                $labels[] = $currentDate->format('d M');
                $revenues[] = Transaction::where('status', 'LUNAS')->whereDate('created_at', $currentDate)->sum('total_harga');
                $profits[] = $this->calculateProfit($currentDate, $currentDate->copy()->endOfDay());
                $currentDate->addDay();
            }
        }
        return ['labels' => $labels, 'revenues' => $revenues, 'profits' => $profits];
    }

    private function parseDateRange($range, $getPrevious = false)
    {
        $now = Carbon::now();
        $title = '';

        if ($range === 'today') {
            $startDate = $now->copy()->startOfDay();
            $endDate = $now->copy()->endOfDay();
            if ($getPrevious) { $startDate->subDay(); $endDate->subDay(); }
            $title = 'Hari Ini';
        } elseif ($range === '7_days') {
            $startDate = $now->copy()->subDays(6)->startOfDay();
            $endDate = $now->copy()->endOfDay();
            if ($getPrevious) { $startDate->subDays(7); $endDate->subDays(7); }
            $title = '7 Hari Terakhir';
        } elseif ($range === '30_days') {
            $startDate = $now->copy()->subDays(29)->startOfDay();
            $endDate = $now->copy()->endOfDay();
            if ($getPrevious) { $startDate->subDays(30); $endDate->subDays(30); }
            $title = '30 Hari Terakhir';
        } elseif ($range === 'this_month') {
            $startDate = $now->copy()->startOfMonth();
            $endDate = $now->copy()->endOfMonth();
            if ($getPrevious) { $startDate->subMonth(); $endDate = $startDate->copy()->endOfMonth(); }
            $title = 'Bulan Ini';
        } else {
            $range = 'all_time';
            $startDate = Carbon::create(2000, 1, 1);
            $endDate = Carbon::now();
            if ($getPrevious) {
                $startDate = Carbon::create(1999, 1, 1);
                $endDate = Carbon::create(1999, 12, 31);
            }
            $title = 'Semua Waktu';
        }
        return [$startDate, $endDate, $title];
    }
}
