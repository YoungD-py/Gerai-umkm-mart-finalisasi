<?php

namespace App\Http\Controllers\Api; // <-- DIPERBAIKI

use App\Http\Controllers\Controller; // <-- DIPERBAIKI
use App\Models\ReturnBarang;         // <-- DIPERBAIKI
use App\Models\Good;                 // <-- DIPERBAIKI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReturnController extends Controller
{
    /**
     * Menampilkan daftar RIWAYAT return
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $search = $request->get('search');
            $categoryId = $request->get('category_id');

            // Ambil logika filter dari Model
            $query = ReturnBarang::query()->with(['good.category', 'user'])
                        ->filter(['search' => $search, 'category_id' => $categoryId])
                        ->latest(); // Urutkan berdasarkan terbaru

            $returns = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Return data retrieved successfully',
                'data' => $returns
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve return data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menyimpan data return baru
     * (Logika disamakan dengan web controller store)
     */
    public function store(Request $request)
    {
        try {
            // Validasi disamakan dengan WEB controller
            $validatedData = $request->validate([
                'good_id'    => 'required|exists:goods,id',
                'tgl_return' => 'required|date_format:Y-m-d',
                'qty_return' => 'required|integer|min:1',
                'alasan'     => 'required|in:Rusak,Cacat,Kadaluarsa,Salah Kirim,Lainnya',
                'keterangan' => 'nullable|string',
            ]);

            $return = null;

            DB::transaction(function () use ($validatedData, $request, &$return) {
                $good = Good::find($validatedData['good_id']);

                // Validasi stok (logika dari web)
                if ($validatedData['qty_return'] > $good->stok) {
                    throw ValidationException::withMessages([
                        'qty_return' => 'Jumlah return tidak boleh melebihi stok tersedia (' . $good->stok . ')'
                    ]);
                }

                // Tambahkan user_id
                $validatedData['user_id'] = $request->user()->id;

                // 1. Buat data return
                $return = ReturnBarang::create($validatedData);

                // 2. Kurangi stok barang
                $good->decrement('stok', $validatedData['qty_return']);
            });
            
            $return->load('good', 'user');

            return response()->json([
                'success' => true,
                'message' => 'Return barang berhasil ditambahkan.',
                'data' => $return
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create return: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan detail 1 riwayat return
     */
    public function show($id)
    {
        try {
            $return = ReturnBarang::with('good', 'user')->find($id);

            if (!$return) {
                return $this->notFoundResponse();
            }

            return response()->json([
                'success' => true,
                'message' => 'Return data retrieved successfully',
                'data' => $return
            ], 200);
        } catch (\Exception $e) {
            return response()->json([ 'success' => false, 'message' => $e->getMessage() ], 500);
        }
    }

    /**
     * Update data riwayat return
     * (Logika disamakan dengan web controller update)
     */
    public function update(Request $request, $id)
    {
        try {
            $return = ReturnBarang::find($id);
            if (!$return) {
                return $this->notFoundResponse();
            }

            // Validasi disamakan dengan WEB controller
            $validatedData = $request->validate([
                'good_id'    => 'required|exists:goods,id',
                'tgl_return' => 'required|date_format:Y-m-d',
                'qty_return' => 'required|integer|min:1',
                'alasan'     => 'required|in:Rusak,Cacat,Kadaluarsa,Salah Kirim,Lainnya',
                'keterangan' => 'nullable|string',
            ]);
            
            DB::transaction(function () use ($return, $validatedData) {
                $newGood = Good::find($validatedData['good_id']);
                $oldGood = $return->good; // Ambil good lama dari relasi

                // 1. Balikin stok lama (LOGIKA DARI WEB)
                $oldGood->increment('stok', $return->qty_return);

                // 2. Validasi stok baru (LOGIKA DARI WEB)
                // Cek stok *setelah* dikembalikan
                $currentStockOfNewGood = Good::find($newGood->id)->stok; 
                if ($validatedData['qty_return'] > $currentStockOfNewGood) {
                    throw ValidationException::withMessages([
                        'qty_return' => 'Jumlah return tidak boleh melebihi stok tersedia (' . $currentStockOfNewGood . ')'
                    ]);
                    // Transaksi akan otomatis di-rollback
                }

                // 3. Update data return
                $return->update($validatedData);

                // 4. Kurangi stok baru (LOGIKA DARI WEB)
                $newGood->decrement('stok', $validatedData['qty_return']);
            });

            $return->load('good', 'user');

            return response()->json([
                'success' => true,
                'message' => 'Data return berhasil diperbarui.',
                'data' => $return
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update return: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus data riwayat return
     * (Logika disamakan dengan web controller destroy)
     */
    public function destroy($id)
    {
        try {
            $return = ReturnBarang::find($id);
            if (!$return) {
                return $this->notFoundResponse();
            }

            DB::transaction(function () use ($return) {
                $good = $return->good;
                if ($good) {
                    // 1. Kembalikan stok barang (LOGIKA YANG HILANG)
                    $good->increment('stok', $return->qty_return);
                }
                
                // 2. Hapus data return
                $return->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Data return berhasil dihapus dan stok dikembalikan.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete return: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper function untuk respon Not Found
     */
    private function notFoundResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Return not found'
        ], 404);
    }
}