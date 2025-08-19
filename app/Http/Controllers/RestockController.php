<?php

namespace App\Http\Controllers;

use App\Models\Good;
use App\Models\Restock; // Import model Restock
use App\Models\Category; // Added Category import for mitra filter
use Illuminate\Http\Request;

class RestockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Good::with('category')
            ->filter(request(['search', 'mitra']));

        // Handle sorting
        if (request('sort_stok')) {
            if (request('sort_stok') === 'asc') {
                $query->orderBy('stok', 'asc');
            } elseif (request('sort_stok') === 'desc') {
                $query->orderBy('stok', 'desc');
            }
        }

        if (request('sort_mitra')) {
            if (request('sort_mitra') === 'asc') {
                $query->join('categories', 'goods.category_id', '=', 'categories.id')
                      ->orderBy('categories.nama', 'asc')
                      ->select('goods.*');
            } elseif (request('sort_mitra') === 'desc') {
                $query->join('categories', 'goods.category_id', '=', 'categories.id')
                      ->orderBy('categories.nama', 'desc')
                      ->select('goods.*');
            }
        }

        if (request('filter_status')) {
            if (request('filter_status') === 'aman') {
                // Show only "Stok Aman" (stock > 20)
                $query->where('stok', '>', 20);
            } elseif (request('filter_status') === 'sedang') {
                // Show only "Stok Sedang" (stock 6-20)
                $query->whereBetween('stok', [6, 20]);
            } elseif (request('filter_status') === 'rendah') {
                // Show only "Stok Rendah" (stock <= 5)
                $query->where('stok', '<=', 5);
            }
        }

        $restockHistory = Restock::with(['good.category', 'user'])
            ->orderBy('tgl_restock', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'history_page');

        return view('dashboard.restock.index', [
            'active' => 'restock',
            'goods' => $query->paginate(10)->withQueryString(),
            'categories' => Category::all(), // Added categories for mitra filter dropdown
            'restockHistory' => $restockHistory, // Added restock history data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Good $good)
    {
        return view('dashboard.restock.edit', [
            'active' => 'restock',
            'good' => $good,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Good $good)
    {
        $validatedData = $request->validate([
            'stok_tambahan' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $stokSebelum = $good->stok;

        // Add new stock to existing stock
        $stokBaru = $good->stok + $validatedData['stok_tambahan'];

        $good->update([
            'stok' => $stokBaru,
        ]);

        // Create a new Restock record
        Restock::create([
            'good_id' => $good->id,
            'user_id' => auth()->user()->id, // Assuming authenticated user performs restock
            'qty_restock' => $validatedData['stok_tambahan'],
            'keterangan' => $validatedData['keterangan'],
            'tgl_restock' => now()->toDateString(), // Use current date for restock
        ]);

        return redirect('/dashboard/restock')->with('success',
            "Berhasil menambah stok {$good->nama} sebanyak {$validatedData['stok_tambahan']} unit. Stok sekarang: {$stokBaru} unit.");
    }
}
