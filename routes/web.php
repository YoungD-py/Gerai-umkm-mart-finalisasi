<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GoodController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\RestockController;
use App\Http\Controllers\BiayaOperasionalController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [LoginController::class, 'index']);
Route::get('/loginadmin', [LoginController::class, 'adminLogin']);
Route::get('/error-unauthorized', [LoginController::class, 'errorUnauthorized']);
Route::get('/error-admin-only', [LoginController::class, 'errorAdminOnly']);
Route::post('/login', [LoginController::class, 'authenticate'])->name('login');
Route::post('/loginadmin', [LoginController::class, 'authenticateAdmin'])->name('loginadmin');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Captcha routes
Route::post('/store-math-captcha', [LoginController::class, 'storeMathCaptcha']);
Route::post('/store-captcha', [LoginController::class, 'storeCaptcha']);

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');
Route::get('/dashboard/cashier/quick-transaction', [CashierController::class, 'quickTransaction'])->middleware('auth');

// ADMIN ONLY ROUTES
Route::middleware(['auth', 'admin'])->group(function () {
    // Users
    Route::delete('/dashboard/users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulkDelete');
    Route::resource('/dashboard/users', UserController::class);
    
    // Transactions
    Route::delete('/dashboard/transactions/bulk-delete', [TransactionController::class, 'bulkDelete'])->name('transactions.bulkDelete');
    Route::resource('/dashboard/transactions', TransactionController::class);
});

// ROUTES YANG BISA DIAKSES ADMIN DAN KASIR
Route::middleware('auth')->group(function () {
    // Biaya Operasional
    Route::delete('/dashboard/biayaoperasional/bulk-delete', [BiayaOperasionalController::class, 'bulkDelete'])->name('biayaoperasional.bulkDelete');
    Route::resource('/dashboard/biayaoperasional', BiayaOperasionalController::class);

    // Categories
    Route::delete('/dashboard/categories/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('categories.bulkDelete');
    Route::resource('/dashboard/categories', CategoryController::class);

    // Goods
    Route::get('/dashboard/goods/cetakbarcode', [GoodController::class, 'showPrintMultipleBarcodesForm'])->name('goods.cetakbarcode.form');
    Route::post('/dashboard/goods/cetakbarcode', [GoodController::class, 'generateMultipleBarcodesPdf'])->name('goods.cetakbarcode.pdf');
    Route::delete('/dashboard/goods/bulk-delete', [GoodController::class, 'bulkDelete'])->name('goods.bulkDelete');
    Route::resource('/dashboard/goods', GoodController::class);
    Route::post('/dashboard/goods/{good}/generate-barcode', [GoodController::class, 'generateBarcode']);
    Route::get('/dashboard/goods/{good}/download-barcode', [GoodController::class, 'downloadBarcode']);
    Route::get('/dashboard/goods/{good}/print-barcode', [GoodController::class, 'printBarcode']);

    // Customers
    Route::resource('/dashboard/customers', CustomerController::class);
    
    // Returns
    Route::delete('/dashboard/returns/bulk-delete', [ReturnController::class, 'bulkDelete'])->name('returns.bulkDelete');
    Route::resource('/dashboard/returns', ReturnController::class);

    // RESTOCK ROUTES
    Route::get('/dashboard/restock', [RestockController::class, 'index']);
    Route::get('/dashboard/restock/{good}/edit', [RestockController::class, 'edit']);
    Route::put('/dashboard/restock/{good}', [RestockController::class, 'update']);

    // ORDER BY NOTA
    Route::match(['get', 'post'], '/dashboard/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/dashboard/orders/create', [OrderController::class, 'create']);
    Route::post('/dashboard/orders/store', [OrderController::class, 'store']);
    Route::post('/dashboard/transactions/checkout', [OrderController::class, 'checkout']);
    Route::delete('/dashboard/orders/{order}', [OrderController::class, 'destroy']);

    // KASIR ROUTES
    Route::get('/dashboard/cashier', [CashierController::class, 'index']);
    Route::get('/dashboard/cashier/create', [CashierController::class, 'createtransaction']);
    Route::post('/dashboard/cashier/create', [CashierController::class, 'storetransaction']);
    Route::get('/dashboard/cashier/createorder', [CashierController::class, 'createorder']);
    Route::post('/dashboard/cashier/createorder', [CashierController::class, 'createorder']);
    Route::post('/dashboard/cashier/storeorder', [CashierController::class, 'storeorder']);
    Route::post('/dashboard/cashier/store-barcode', [CashierController::class, 'storeOrderFromBarcode'])->name('cashier.storeBarcode');
    Route::delete('/dashboard/cashier/deleteorder/{order}', [CashierController::class, 'deleteorder'])->name('cashier.deleteOrder');
    Route::post('/dashboard/cashiers/checkout', [CashierController::class, 'checkout']);
    Route::post('/dashboard/cashiers/finishing', [CashierController::class, 'finishing']);
    Route::post('/dashboard/cashiers/nota', [CashierController::class, 'nota']);

    // New route for product search
    Route::get('/dashboard/cashier/search-goods', [CashierController::class, 'searchGoods'])->name('cashier.searchGoods');

    // REKAPITULASI
    Route::get('/dashboard/rekapitulasi', [ReportController::class, 'index']);
    Route::post('/dashboard/rekapitulasi/cetak-transaksi', [ReportController::class, 'cetakTransaksi']);
    Route::post('/dashboard/rekapitulasi/cetak-barang', [ReportController::class, 'cetakBarang']);
    Route::post('/dashboard/rekapitulasi/cetak-restock-return', [ReportController::class, 'cetakRestockReturn']);
    Route::post('/dashboard/rekapitulasi/cetak-biaya-operasional', [ReportController::class, 'cetakBiayaOperasional'])->name('reports.cetakBiayaOperasional');
    Route::post('/dashboard/rekapitulasi/cetak-laporan-keuangan', [ReportController::class, 'cetakLaporanKeuangan'])->name('reports.cetakLaporanKeuangan');


    // PRINT ROUTES
    Route::get('/dashboard/goods/print-all', [GoodController::class, 'printAll']);
    Route::get('/dashboard/returns/print-all', [ReturnController::class, 'printAll']);
});
