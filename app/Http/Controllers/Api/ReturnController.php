<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReturnBarang;         
use App\Models\Good;                 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $search = $request->get('search');
            $categoryId = $request->get('category_id');

            $query = ReturnBarang::query()->with(['good.category', 'user'])
                        ->filter(['search' => $search, 'category_id' => $categoryId])
                        ->latest();

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

    public function store(Request $request)
    {
        try {
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

                if ($validatedData['qty_return'] > $good->stok) {
                    throw ValidationException::withMessages([
                        'qty_return' => 'Jumlah return tidak boleh melebihi stok tersedia (' . $good->stok . ')'
                    ]);
                }

                $validatedData['user_id'] = $request->user()->id;

                $return = ReturnBarang::create($validatedData);

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

    public function update(Request $request, $id)
    {
        try {
            $return = ReturnBarang::find($id);
            if (!$return) {
                return $this->notFoundResponse();
            }

            $validatedData = $request->validate([
                'good_id'    => 'required|exists:goods,id',
                'tgl_return' => 'required|date_format:Y-m-d',
                'qty_return' => 'required|integer|min:1',
                'alasan'     => 'required|in:Rusak,Cacat,Kadaluarsa,Salah Kirim,Lainnya',
                'keterangan' => 'nullable|string',
            ]);
            
            DB::transaction(function () use ($return, $validatedData) {
                $newGood = Good::find($validatedData['good_id']);
                $oldGood = $return->good; 

                $oldGood->increment('stok', $return->qty_return);

                $currentStockOfNewGood = Good::find($newGood->id)->stok; 
                if ($validatedData['qty_return'] > $currentStockOfNewGood) {
                    throw ValidationException::withMessages([
                        'qty_return' => 'Jumlah return tidak boleh melebihi stok tersedia (' . $currentStockOfNewGood . ')'
                    ]);
                }

                $return->update($validatedData);

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
                    $good->increment('stok', $return->qty_return);
                }
                
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

    private function notFoundResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Return not found'
        ], 404);
    }
}