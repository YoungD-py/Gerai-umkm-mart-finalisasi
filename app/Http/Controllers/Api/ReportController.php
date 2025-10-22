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

    public function sales(Request $request)
    {
        try {
            $validated = $request->validate([
                'tgl_awal' => 'required|date_format:Y-m-d',
                'tgl_akhir' => 'required|date_format:Y-m-d|after_or_equal:tgl_awal',
            ]);

            $startDate = Carbon::parse($validated['tgl_awal'])->startOfDay();
            $endDate = Carbon::parse($validated['tgl_akhir'])->endOfDay();

            $query = Transaction::where('status', 'LUNAS')
                        ->whereBetween('created_at', [$startDate, $endDate]);
            
            $transactions = $query->with(['user', 'orders.good'])
                                  ->orderBy('created_at', 'desc')
                                  ->get();
            
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

    public function expenses(Request $request)
    {
        try {
            $validated = $request->validate([
                'tgl_awal' => 'required|date_format:Y-m-d',
                'tgl_akhir' => 'required|date_format:Y-m-d|after_or_equal:tgl_awal',
            ]);

            $startDate = Carbon::parse($validated['tgl_awal'])->startOfDay();
            $endDate = Carbon::parse($validated['tgl_akhir'])->endOfDay();

            $query = BiayaOperasional::whereBetween('tanggal', [$startDate, $endDate]);

            $expenses = $query->orderBy('tanggal', 'desc')->get();
            
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

    public function summary(Request $request)
    {
        try {
            $validated = $request->validate([
                'tgl_awal' => 'required|date_format:Y-m-d',
                'tgl_akhir' => 'required|date_format:Y-m-d|after_or_equal:tgl_awal',
            ]);
            
            $startDate = Carbon::parse($validated['tgl_awal'])->startOfDay();
            $endDate = Carbon::parse($validated['tgl_akhir'])->endOfDay();

            $totalSales = Transaction::where('status', 'LUNAS')
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->sum('total_harga'); 

            $totalExpenses = BiayaOperasional::whereBetween('tanggal', [$startDate, $endDate])
                            ->sum('nominal');
            
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
    
    private function serverErrorResponse(\Exception $e, $message = 'Server Error')
    {
        Log::error($message . ': ' . $e->getMessage()); 
        return response()->json([
            'success' => false,
            'message' => $message . ': ' . $e->getMessage()
        ], 500);
    }
}