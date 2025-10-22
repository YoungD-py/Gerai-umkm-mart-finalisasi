<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restock;
use App\Models\Good;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RestockController extends Controller
{

    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $search = $request->get('search');

            $query = Restock::with('good', 'user')->orderBy('tgl_restock', 'desc');

            if ($search) {
                $query->whereHas('good', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%');
                })->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            }
            
            $restocks = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Riwayat restock berhasil diambil',
                'data' => $restocks
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'good_id' => 'required|exists:goods,id',
                'stok_tambahan' => 'required|integer|min:1',
                'keterangan' => 'nullable|string|max:255',
                'tgl_restock' => 'required|date_format:Y-m-d',
            ]);

            $restock = null;

            DB::transaction(function () use ($validatedData, &$restock, $request) {
                $good = Good::find($validatedData['good_id']);

                $stokSebelum = $good->stok;
                $stokBaru = $stokSebelum + $validatedData['stok_tambahan'];

                $good->update(['stok' => $stokBaru]);

                $restock = Restock::create([
                    'good_id' => $good->id,
                    'user_id' => $request->user()->id,
                    'qty_restock' => $validatedData['stok_tambahan'],
                    'keterangan' => $validatedData['keterangan'] ?? null,
                    'tgl_restock' => $validatedData['tgl_restock'],
                    'stok_sebelum' => $stokSebelum,
                ]);
            });
            
            $restock->load('good', 'user');

            return response()->json([
                'success' => true,
                'message' => "Berhasil menambah stok {$restock->good->nama}",
                'data' => $restock
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
                'message' => 'Failed to create restock: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function show($id)
    {
        try {
            $restock = Restock::with('good', 'user')->find($id);

            if (!$restock) {
                return $this->notFoundResponse();
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail restock berhasil diambil',
                'data' => $restock
            ], 200);
        } catch (\Exception $e) {
            return response()->json([ 'success' => false, 'message' => $e->getMessage() ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $restock = Restock::find($id);
            if (!$restock) {
                return $this->notFoundResponse();
            }

            $validatedData = $request->validate([
                'qty_restock' => 'required|integer|min:1',
                'keterangan' => 'nullable|string|max:255',
                'tgl_restock' => 'required|date_format:Y-m-d',
            ]);

            DB::transaction(function () use ($restock, $validatedData) {
                $oldQty = $restock->qty_restock;
                $newQty = $validatedData['qty_restock'];
                $qtyDifference = $newQty - $oldQty;

                $good = $restock->good;
                $newStock = $good->stok + $qtyDifference;

                if ($newStock < 0) {
                    throw ValidationException::withMessages([
                        'qty_restock' => 'Stok barang akan menjadi negatif. Sisa stok: ' . $good->stok
                    ]);
                }

                $good->update(['stok' => $newStock]);

                $restock->update($validatedData);
            });

            $restock->load('good', 'user');

            return response()->json([
                'success' => true,
                'message' => 'Data restock berhasil diubah',
                'data' => $restock
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
                'message' => 'Failed to update restock: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $restock = Restock::find($id);
            if (!$restock) {
                return $this->notFoundResponse();
            }

            DB::transaction(function () use ($restock) {
                $good = $restock->good;
                $qtyToRestore = $restock->qty_restock;
                $newStock = $good->stok - $qtyToRestore;

                if ($newStock < 0) {
                    throw new \Exception('Gagal hapus: Stok barang akan menjadi negatif.');
                }

                $good->update(['stok' => $newStock]);

                $restock->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Data restock berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function notFoundResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Restock not found'
        ], 404);
    }
}