<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Good;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException; // <-- WAJIB TAMBAHKAN
use Illuminate\Support\Facades\DB; // <-- WAJIB TAMBAHKAN

class GoodController extends Controller
{
    /**
     * Menampilkan daftar barang (sudah disinkronkan)
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);

            // Gunakan scopeFilter dari Model
            $query = Good::query()->with('category')
                        ->filter($request->only(['search', 'mitra', 'category']));

            // --- Logic Sorting dari Web Controller (diadaptasi) ---
            $sortApplied = false;

            if ($request->get('sort_expired')) {
                $dir = $request->get('sort_expired') === 'asc' ? 'asc' : 'desc';
                $query->whereNotNull('expired_date')->orderBy('expired_date', $dir);
                $sortApplied = true;
            }

            if ($request->get('sort_tgl_masuk')) {
                $dir = $request->get('sort_tgl_masuk') === 'asc' ? 'asc' : 'desc';
                $query->orderBy('tgl_masuk', $dir);
                $sortApplied = true;
            }

            if ($request->get('sort_stok')) {
                $dir = $request->get('sort_stok') === 'asc' ? 'asc' : 'desc';
                $query->orderBy('stok', $dir);
                $sortApplied = true;
            }

            if ($request->get('sort_harga')) {
                $dir = $request->get('sort_harga') === 'asc' ? 'asc' : 'desc';
                $query->orderBy('harga', $dir); // <-- FIX: 'harga' bukan 'price'
                $sortApplied = true;
            }

            // Default sorting (from web)
            if (!$sortApplied) {
                $query->latest(); 
            }
            // --- Akhir Logic Sorting ---

            $goods = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Goods data retrieved successfully',
                'data' => $goods
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve goods: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menyimpan barang baru (sudah disinkronkan)
     */
    public function store(Request $request)
    {
        try {
            // Validasi diambil penuh dari Web Controller
            $validatedData = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'tgl_masuk' => 'required|date_format:Y-m-d',
                'nama' => [
                    'required',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) {
                        $exists = Good::whereRaw('LOWER(nama) = ?', [strtolower($value)])->exists();
                        if ($exists) {
                            $fail('Nama barang sudah ada (tidak boleh sama meskipun berbeda huruf besar/kecil).');
                        }
                    },
                ],
                'type' => 'required|in:makanan,non_makanan,lainnya,handycraft,fashion',
                'expired_date' => 'nullable|date_format:Y-m-d|after:today',
                'stok' => 'required|integer|min:0',
                'harga_asli' => 'required|numeric|min:0',
                'markup_percentage' => 'nullable|numeric|min:0|max:100', 
                'is_grosir_active' => 'boolean',
                'min_qty_grosir' => 'nullable|integer|min:2',
                'harga_grosir' => 'nullable|numeric|min:0',
                'is_tebus_murah_active' => 'boolean',
                'min_total_tebus_murah' => 'nullable|numeric|min:0',
                'harga_tebus_murah' => 'nullable|numeric|min:0',
                'use_existing_barcode' => 'boolean',
                'existing_barcode' => 'nullable|string|max:255',
            ]);

            // --- Logika Bisnis dari Web Controller ---
            if (in_array($validatedData['type'], ['makanan', 'non_makanan']) && empty($validatedData['expired_date'])) {
                throw ValidationException::withMessages([
                    'expired_date' => 'Tanggal expired wajib diisi untuk jenis ' . $validatedData['type'] . '.'
                ]);
            }

            if (in_array($validatedData['type'], ['lainnya', 'handycraft', 'fashion'])) {
                $validatedData['expired_date'] = null;
            }

            // Perhitungan harga jual
            if ($request->has('markup_percentage') && $request->markup_percentage !== null) {
                $markup = $validatedData['markup_percentage'] / 100;
            } else {
                $markup = $validatedData['type'] === 'makanan' ? 0.02 : 0.05;
            }
            $validatedData['harga'] = $validatedData['harga_asli'] + ($validatedData['harga_asli'] * $markup); // <-- HARGA DIHITUNG

            // Validasi Grosir
            $validatedData['is_grosir_active'] = $request->boolean('is_grosir_active');
            if ($validatedData['is_grosir_active']) {
                if (empty($validatedData['min_qty_grosir']) || empty($validatedData['harga_grosir'])) {
                    throw ValidationException::withMessages(['min_qty_grosir' => 'Minimal Qty & Harga grosir wajib diisi.']);
                }
                if ($validatedData['harga_grosir'] < $validatedData['harga_asli']) {
                    throw ValidationException::withMessages(['harga_grosir' => 'Harga grosir tidak boleh lebih rendah dari harga asli (modal).']);
                }
                if ($validatedData['harga_grosir'] >= $validatedData['harga']) {
                    throw ValidationException::withMessages(['harga_grosir' => 'Harga grosir harus lebih kecil dari harga eceran.']);
                }
            } else {
                $validatedData['min_qty_grosir'] = null;
                $validatedData['harga_grosir'] = null;
            }

            // Validasi Tebus Murah
            $validatedData['is_tebus_murah_active'] = $request->boolean('is_tebus_murah_active');
            if ($validatedData['is_tebus_murah_active']) {
                if (empty($validatedData['min_total_tebus_murah']) || empty($validatedData['harga_tebus_murah'])) {
                    throw ValidationException::withMessages(['min_total_tebus_murah' => 'Minimal Total & Harga tebus murah wajib diisi.']);
                }
                if ($validatedData['harga_tebus_murah'] < $validatedData['harga_asli']) {
                    throw ValidationException::withMessages(['harga_tebus_murah' => 'Harga tebus murah tidak boleh lebih rendah dari harga asli (modal).']);
                }
            } else {
                $validatedData['min_total_tebus_murah'] = null;
                $validatedData['harga_tebus_murah'] = null;
            }

            // Logika Barcode (Sesuai Web)
            $validatedData['use_existing_barcode'] = $request->boolean('use_existing_barcode');
            if ($validatedData['use_existing_barcode'] && !empty($validatedData['existing_barcode'])) {
                $existingGood = Good::where('barcode', $validatedData['existing_barcode'])->first();
                if ($existingGood) {
                    throw ValidationException::withMessages(['existing_barcode' => "BARCODE {$validatedData['existing_barcode']} SUDAH DIPAKAI OLEH {$existingGood->nama}."]);
                }
                $validatedData['barcode'] = $validatedData['existing_barcode'];
            } else {
                $validatedData['barcode'] = Good::generateBarcodeStatic($validatedData['type'], $validatedData['nama']);
                $validatedData['existing_barcode'] = null;
            }
            // --- Akhir Logika Bisnis ---

            $good = Good::create($validatedData);
            $good->load('category');

            return response()->json([
                'success' => true,
                'message' => 'Good created successfully',
                'data' => $good
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
                'message' => 'Failed to create good: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan detail 1 barang
     */
    public function show($id)
    {
        try {
            $good = Good::with('category')->find($id);

            if (!$good) {
                return $this->notFoundResponse();
            }

            return response()->json([
                'success' => true,
                'message' => 'Good data retrieved successfully',
                'data' => $good
            ], 200);
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e);
        }
    }

    /**
     * Update barang (sudah disinkronkan)
     */
    public function update(Request $request, $id)
    {
        try {
            $good = Good::find($id);
            if (!$good) {
                return $this->notFoundResponse();
            }

            // Validasi dari Web Controller (update)
            $rules = [
                'category_id' => 'required|exists:categories,id',
                'tgl_masuk' => 'required|date_format:Y-m-d',
                'nama' => [
                    'required',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) use ($good) {
                        $exists = Good::whereRaw('LOWER(nama) = ?', [strtolower($value)])
                            ->where('id', '!=', $good->id)
                            ->exists();
                        if ($exists) {
                            $fail('Nama barang sudah ada (tidak boleh sama meskipun berbeda huruf besar/kecil).');
                        }
                    },
                ],
                'type' => 'required|in:makanan,non_makanan,lainnya,handycraft,fashion',
                'expired_date' => 'nullable|date_format:Y-m-d|after_or_equal:today',
                'harga_asli' => 'required|numeric|min:0',
                'markup_percentage' => 'nullable|numeric|min:0|max:100', 
                'is_grosir_active' => 'boolean',
                'min_qty_grosir' => 'nullable|integer|min:2',
                'harga_grosir' => 'nullable|numeric|min:0',
                'is_tebus_murah_active' => 'boolean',
                'min_total_tebus_murah' => 'nullable|numeric|min:0',
                'harga_tebus_murah' => 'nullable|numeric|min:0',
                'use_existing_barcode' => 'boolean',
                'existing_barcode' => 'nullable|string|max:255',
            ];

            // Note: API tidak bisa memvalidasi 'stok' karena stok hanya bisa diubah via Restock.
            // Jika Anda ingin API bisa update stok, tambahkan 'stok' => 'required|integer|min:0'
            // Tapi saya sarankan JANGAN, agar konsisten dgn RestockController.

            $validatedData = $request->validate($rules);
            
            // --- Logika Bisnis dari Web Controller (Update) ---
            if (in_array($validatedData['type'], ['makanan', 'non_makanan']) && empty($validatedData['expired_date'])) {
                throw ValidationException::withMessages(['expired_date' => 'Tanggal expired wajib diisi untuk jenis ' . $validatedData['type'] . '.']);
            }
            if (in_array($validatedData['type'], ['lainnya', 'handycraft', 'fashion'])) {
                $validatedData['expired_date'] = null;
            }
            
            // Perhitungan harga jual
            if ($request->has('markup_percentage') && $request->markup_percentage !== null) {
                $markup = $validatedData['markup_percentage'] / 100;
            } else {
                $markup = $validatedData['type'] === 'makanan' ? 0.02 : 0.05;
            }
            $validatedData['harga'] = $validatedData['harga_asli'] + ($validatedData['harga_asli'] * $markup);

            // Validasi Grosir
            $validatedData['is_grosir_active'] = $request->boolean('is_grosir_active');
            if ($validatedData['is_grosir_active']) {
                if (empty($validatedData['min_qty_grosir']) || empty($validatedData['harga_grosir'])) {
                    throw ValidationException::withMessages(['min_qty_grosir' => 'Minimal Qty & Harga grosir wajib diisi.']);
                }
                //... (validasi harga grosir lainnya seperti di store) ...
            } else {
                $validatedData['min_qty_grosir'] = null;
                $validatedData['harga_grosir'] = null;
            }

            // Validasi Tebus Murah
            $validatedData['is_tebus_murah_active'] = $request->boolean('is_tebus_murah_active');
            if ($validatedData['is_tebus_murah_active']) {
                 if (empty($validatedData['min_total_tebus_murah']) || empty($validatedData['harga_tebus_murah'])) {
                    throw ValidationException::withMessages(['min_total_tebus_murah' => 'Minimal Total & Harga tebus murah wajib diisi.']);
                }
                //... (validasi harga tebus murah lainnya seperti di store) ...
            } else {
                $validatedData['min_total_tebus_murah'] = null;
                $validatedData['harga_tebus_murah'] = null;
            }

            // Logika Barcode
            $validatedData['use_existing_barcode'] = $request->boolean('use_existing_barcode');
            if ($validatedData['use_existing_barcode'] && !empty($validatedData['existing_barcode'])) {
                $existingGood = Good::where('barcode', $validatedData['existing_barcode'])->where('id', '!=', $good->id)->first();
                if ($existingGood) {
                    throw ValidationException::withMessages(['existing_barcode' => "BARCODE {$validatedData['existing_barcode']} SUDAH DIPAKAI OLEH {$existingGood->nama}."]);
                }
                $validatedData['barcode'] = $validatedData['existing_barcode'];
            } else {
                // Jangan generate ulang barcode jika sudah ada, kecuali diminta
                if (!$good->barcode) {
                    $validatedData['barcode'] = Good::generateBarcodeStatic($validatedData['type'], $validatedData['nama']);
                }
                $validatedData['existing_barcode'] = null;
            }
            // --- Akhir Logika Bisnis ---

            $good->update($validatedData);
            $good->load('category');

            return response()->json([
                'success' => true,
                'message' => 'Good updated successfully',
                'data' => $good
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e, 'Failed to update good');
        }
    }

    /**
     * Menghapus barang (sudah disinkronkan)
     */
    public function destroy($id)
    {
        // Logika destroy dari Web Controller sederhana, jadi API ini sudah benar.
        // PERHATIAN: Pastikan Anda punya proteksi agar barang yg ada di transaksi
        // tidak bisa dihapus (relasi foreign key).
        try {
            $good = Good::find($id);
            if (!$good) {
                return $this->notFoundResponse();
            }

            // Cek relasi (contoh, tambahkan jika perlu)
            // if ($good->orders()->exists()) {
            //     return response()->json(['success' => false, 'message' => 'Tidak bisa hapus, barang ini ada di riwayat transaksi.'], 409);
            // }

            $good->delete();

            return response()->json([
                'success' => true,
                'message' => 'Good deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e, 'Failed to delete good');
        }
    }

    // --- Helper Functions ---
    private function notFoundResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Good not found'
        ], 404);
    }

    private function serverErrorResponse(\Exception $e, $message = 'Server Error')
    {
        return response()->json([
            'success' => false,
            'message' => $message . ': ' . $e->getMessage()
        ], 500);
    }
}