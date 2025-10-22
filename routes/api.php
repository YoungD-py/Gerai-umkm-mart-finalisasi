<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RestockController;
use App\Http\Controllers\Api\ReturnController;
use App\Http\Controllers\Api\GoodController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ExpiredProductController;
use App\Http\Controllers\Api\HistoryController;
use App\Http\Controllers\Api\OperationalController;
use App\Http\Controllers\Api\BarcodeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes (tidak perlu auth)
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth endpoints
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // User CRUD endpoints
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/all', [UserController::class, 'all']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::patch('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::post('/users/bulk-delete', [UserController::class, 'bulkDelete']);

    Route::get('/restock', [RestockController::class, 'index']);
    Route::post('/restock', [RestockController::class, 'store']);
    Route::get('/restock/{id}', [RestockController::class, 'show']);
    Route::put('/restock/{id}', [RestockController::class, 'update']);
    Route::delete('/restock/{id}', [RestockController::class, 'destroy']);

    Route::get('/return', [ReturnController::class, 'index']);
    Route::post('/return', [ReturnController::class, 'store']);
    Route::get('/return/{id}', [ReturnController::class, 'show']);
    Route::put('/return/{id}', [ReturnController::class, 'update']);
    Route::delete('/return/{id}', [ReturnController::class, 'destroy']);

    Route::get('/goods', [GoodController::class, 'index']);
    Route::post('/goods', [GoodController::class, 'store']);
    Route::get('/goods/{id}', [GoodController::class, 'show']);
    Route::put('/goods/{id}', [GoodController::class, 'update']);
    Route::delete('/goods/{id}', [GoodController::class, 'destroy']);

    Route::get('/reports/summary', [ReportController::class, 'summary']);
    Route::get('/reports/sales', [ReportController::class, 'sales']);
    Route::get('/reports/expenses', [ReportController::class, 'expenses']);

    Route::get('/expired-products', [ExpiredProductController::class, 'index']);
    Route::get('/expired-products/{id}', [ExpiredProductController::class, 'show']);
    Route::put('/expired-products/{id}/mark-expired', [ExpiredProductController::class, 'markExpired']);

    Route::get('/history', [HistoryController::class, 'index']);
    Route::get('/history/transactions', [HistoryController::class, 'transactions']);
    Route::get('/history/operational', [HistoryController::class, 'operational']);
    Route::get('/history/restock', [HistoryController::class, 'restock']);
    Route::get('/history/return', [HistoryController::class, 'return']);

    Route::get('/operational', [OperationalController::class, 'index']);
    Route::post('/operational', [OperationalController::class, 'store']);
    Route::get('/operational/{id}', [OperationalController::class, 'show']);
    Route::put('/operational/{id}', [OperationalController::class, 'update']);
    Route::delete('/operational/{id}', [OperationalController::class, 'destroy']);

    Route::post('/barcode/generate', [BarcodeController::class, 'generate']);
    Route::post('/barcode/validate', [BarcodeController::class, 'validate']);
    Route::get('/barcode/{barcode}', [BarcodeController::class, 'getByBarcode']);
    Route::post('/barcode/scan', [BarcodeController::class, 'scan']);
});
