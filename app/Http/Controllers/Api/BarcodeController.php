<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Good;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BarcodeController extends Controller
{
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'nama' => 'required|string|max:255',
        ]);

        $barcode = Good::generateBarcodeStatic($validated['type'], $validated['nama']);

        return response()->json([
            'success' => true,
            'message' => 'Barcode berhasil digenerate',
            'data' => [
                'barcode' => $barcode,
            ],
        ], 200);
    }

    public function validateBarcode(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|string',
        ]);

        $barcode = trim($validated['barcode']);
        $good = Good::with('category')
            ->whereRaw('LOWER(barcode) = ?', [strtolower($barcode)])
            ->first();

        if (!$good) {
            return response()->json([
                'success' => false,
                'message' => 'Barcode tidak ditemukan',
                'data' => [
                    'exists' => false,
                ],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Barcode valid',
            'data' => [
                'exists' => true,
                'good' => $good,
            ],
        ], 200);
    }

    public function getByBarcode($barcode)
    {
        $good = Good::with('category')
            ->whereRaw('LOWER(barcode) = ?', [strtolower(trim($barcode))])
            ->first();

        if (!$good) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk ditemukan',
            'data' => $good,
        ], 200);
    }

    public function scan(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|string',
            'qty' => 'nullable|integer|min:1',
        ]);

        $barcode = trim($validated['barcode']);
        $good = Good::with('category')
            ->whereRaw('LOWER(barcode) = ?', [strtolower($barcode)])
            ->first();

        if (!$good) {
            return response()->json([
                'success' => false,
                'message' => 'Barcode tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Barcode berhasil dipindai',
            'data' => [
                'good' => $good,
                'qty' => (int) ($validated['qty'] ?? 1),
            ],
        ], 200);
    }
}