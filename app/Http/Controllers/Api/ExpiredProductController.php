<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Good;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpiredProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $status = $request->get('status');

            $query = Good::query();

            if ($status === 'expired') {
                $query->where('expired_date', '<', now());
            } elseif ($status === 'expiring_soon') {
                $query->whereBetween('expired_date', [now(), now()->addDays(30)]);
            }

            $products = $query->with('category')
                ->orderBy('expired_date', 'asc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Expired products retrieved successfully',
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve expired products: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = Good::with('category')->find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Product data retrieved successfully',
                'data' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function markExpired(Request $request, $id)
    {
        try {
            $product = Good::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            $validated = $request->validate([
                'expired_date' => 'required|date'
            ]);

            $product->update([
                'expired_date' => $validated['expired_date'],
                'is_expired' => true
            ]);

            $product->load('category');

            return response()->json([
                'success' => true,
                'message' => 'Product marked as expired successfully',
                'data' => $product
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark product as expired: ' . $e->getMessage()
            ], 500);
        }
    }
}
