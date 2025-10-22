<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\BiayaOperasional;
use App\Models\Restock;
use App\Models\ReturnBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HistoryController extends Controller
{
    /**
     * Menampilkan riwayat transaksi (penjualan)
     */
    public function transactions(Request $request)
    {
        try {
            // Validasi input tanggal
            $request->validate([
                'tgl_awal' => 'nullable|date_format:Y-m-d',
                'tgl_akhir' => 'nullable|date_format:Y-m-d|after_or_equal:tgl_awal',
            ]);

            $perPage = $request->get('per_page', 15);
            $startDate = $request->get('tgl_awal');
            $endDate = $request->get('tgl_akhir');

            $query = Transaction::query();

            // Filter berdasarkan rentang tanggal
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }

            // FIX: Relasi yang benar adalah melalui 'orders'
            $transactions = $query->with(['user', 'orders.good'])
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Transaction history retrieved successfully',
                'data' => $transactions
            ], 200);
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e, 'Failed to retrieve transaction history');
        }
    }

    /**
     * Menampilkan riwayat biaya operasional
     */
    public function operational(Request $request)
    {
        try {
            $request->validate([
                'tgl_awal' => 'nullable|date_format:Y-m-d',
                'tgl_akhir' => 'nullable|date_format:Y-m-d|after_or_equal:tgl_awal',
            ]);

            $perPage = $request->get('per_page', 15);
            $startDate = $request->get('tgl_awal');
            $endDate = $request->get('tgl_akhir');

            $query = BiayaOperasional::query();

            // FIX: Filter berdasarkan kolom 'tanggal'
            if ($startDate && $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            }

            // FIX: Hapus with('user') karena tidak ada
            $expenses = $query->orderBy('tanggal', 'desc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Operational history retrieved successfully',
                'data' => $expenses
            ], 200);
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e, 'Failed to retrieve operational history');
        }
    }

    /**
     * Menampilkan riwayat restock
     */
    public function restock(Request $request)
    {
        try {
            $request->validate([
                'tgl_awal' => 'nullable|date_format:Y-m-d',
                'tgl_akhir' => 'nullable|date_format:Y-m-d|after_or_equal:tgl_awal',
            ]);

            $perPage = $request->get('per_page', 15);
            $startDate = $request->get('tgl_awal');
            $endDate = $request->get('tgl_akhir');
            
            $query = Restock::query();

            // FIX: Filter berdasarkan kolom 'tgl_restock'
            if ($startDate && $endDate) {
                $query->whereBetween('tgl_restock', [$startDate, $endDate]);
            }

            $restocks = $query->with('good', 'user')
                ->orderBy('tgl_restock', 'desc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Restock history retrieved successfully',
                'data' => $restocks
            ], 200);
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e, 'Failed to retrieve restock history');
        }
    }

    /**
     * Menampilkan riwayat return
     */
    public function return(Request $request)
    {
        try {
            $request->validate([
                'tgl_awal' => 'nullable|date_format:Y-m-d',
                'tgl_akhir' => 'nullable|date_format:Y-m-d|after_or_equal:tgl_awal',
            ]);
            
            $perPage = $request->get('per_page', 15);
            $startDate = $request->get('tgl_awal');
            $endDate = $request->get('tgl_akhir');

            $query = ReturnBarang::query();

            // FIX: Filter berdasarkan kolom 'tgl_return'
            if ($startDate && $endDate) {
                $query->whereBetween('tgl_return', [$startDate, $endDate]);
            }

            $returns = $query->with('good', 'user')
                ->orderBy('tgl_return', 'desc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Return history retrieved successfully',
                'data' => $returns
            ], 200);
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e, 'Failed to retrieve return history');
        }
    }

    // --- Helper Function ---
    private function serverErrorResponse(\Exception $e, $message = 'Server Error')
    {
        Log::error($message . ': ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $message . ': ' . $e->getMessage()
        ], 500);
    }
}