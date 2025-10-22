<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\BiayaOperasional;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    /**
     * Mengambil laporan penjualan berdasarkan rentang tanggal
     */
    public function sales(Request $request)
    {
        try {
            $validated = $request->validate([
                'tgl_awal' => 'required|date_format:Y-m-d',
                'tgl_akhir' => 'required|date_format:Y-m-d|after_or_equal:tgl_awal',
            ]);

            $startDate = Carbon::parse($validated['tgl_awal'])->startOfDay();
            $endDate = Carbon::parse($validated['tgl_akhir'])->endOfDay();

            // Ambil data transaksi sesuai logika web (LUNAS)
            $query = Transaction::where('status', 'LUNAS')
                        ->whereBetween('created_at', [$startDate, $endDate]);
            
            // Ambil daftar transaksi
            $transactions = $query->with(['user', 'orders.good'])
                                  ->orderBy('created_at', 'desc')
                                  ->get();
            
            // Hitung total dari kolom 'total_harga' (sesuai web controller index)
            $totalSales = $transactions->sum('total_harga');
            $transactionCount = $transactions->count();

            return response()->json([
                'success' => true,
                'message' => 'Sales report retrieved successfully',
                'data' => [
                    'start_date' => $startDate->toIso8601String(),
                    'end_date' => $endDate->toIso8601String(),
                    'total_sales' => $totalSales,
                    'transaction_count' => $transactionCount,
                    'transactions' => $transactions 
                ]
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e, 'Failed to retrieve sales report');
        }
    }

    /**
     * Mengambil laporan pengeluaran berdasarkan rentang tanggal
     */
    public function expenses(Request $request)
    {
        try {
            $validated = $request->validate([
                'tgl_awal' => 'required|date_format:Y-m-d',
                'tgl_akhir' => 'required|date_format:Y-m-d|after_or_equal:tgl_awal',
            ]);

            $startDate = Carbon::parse($validated['tgl_awal'])->startOfDay();
            $endDate = Carbon::parse($validated['tgl_akhir'])->endOfDay();

            // Ambil data biaya operasional sesuai logika web (filter by 'tanggal')
            $query = BiayaOperasional::whereBetween('tanggal', [$startDate, $endDate]);

            $expenses = $query->orderBy('tanggal', 'desc')->get();
            
            // Hitung total dari kolom 'nominal' (sesuai web & model)
            $totalExpenses = $expenses->sum('nominal');
            $expenseCount = $expenses->count();

            return response()->json([
                'success' => true,
                'message' => 'Expenses report retrieved successfully',
                'data' => [
                    'start_date' => $startDate->toIso8601String(),
                    'end_date' => $endDate->toIso8601String(),
                    'total_expenses' => $totalExpenses,
                    'expense_count' => $expenseCount,
                    'expenses' => $expenses 
                ]
            ], 200);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e, 'Failed to retrieve expenses report');
        }
    }

    /**
     * Mengambil ringkasan Pemasukan - Pengeluaran (Profit)
     */
    public function summary(Request $request)
    {
        try {
            $validated = $request->validate([
                'tgl_awal' => 'required|date_format:Y-m-d',
                'tgl_akhir' => 'required|date_format:Y-m-d|after_or_equal:tgl_awal',
            ]);
            
            $startDate = Carbon::parse($validated['tgl_awal'])->startOfDay();
            $endDate = Carbon::parse($validated['tgl_akhir'])->endOfDay();

            // 1. Get total sales (sesuai logika web 'index' & 'cetakLaporanKeuangan')
            // Kita sum total_harga dari transaksi yang LUNAS
            $totalSales = Transaction::where('status', 'LUNAS')
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->sum('total_harga'); 

            // 2. Get total expenses (sesuai logika web)
            // Kita sum 'nominal' dari 'biaya_operasional' berdasarkan 'tanggal'
            $totalExpenses = BiayaOperasional::whereBetween('tanggal', [$startDate, $endDate])
                            ->sum('nominal');
            
            // 3. Calculate profit
            $profit = $totalSales - $totalExpenses;

            return response()->json([
                'success' => true,
                'message' => 'Summary report retrieved successfully',
                'data' => [
                    'start_date' => $startDate->toIso8601String(),
                    'end_date' => $endDate->toIso8601String(),
                    'total_sales' => (float) $totalSales,
                    'total_expenses' => (float) $totalExpenses,
                    'profit' => (float) $profit
                ]
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e, 'Failed to retrieve summary report');
        }
    }
    
    // --- Helper Function ---
    private function serverErrorResponse(\Exception $e, $message = 'Server Error')
    {
        Log::error($message . ': ' . $e->getMessage()); // Catat error
        return response()->json([
            'success' => false,
            'message' => $message . ': ' . $e->getMessage()
        ], 500);
    }
}