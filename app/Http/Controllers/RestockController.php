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
     * Show the form for editing a specific restock record.
     */
    public function editRestock(Restock $restock)
    {
        return view('dashboard.restock.edit-restock', [
            'active' => 'restock',
            'restock' => $restock,
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

        Restock::create([
            'good_id' => $good->id,
            'user_id' => auth()->user()->id,
            'qty_restock' => $validatedData['stok_tambahan'],
            'keterangan' => $validatedData['keterangan'] ?? null,
            'tgl_restock' => now()->format('Y-m-d'),
            'stok_sebelum' => $stokSebelum,
        ]);

        return redirect('/dashboard/restock')->with('success',
            "Berhasil menambah stok {$good->nama} sebanyak {$validatedData['stok_tambahan']} unit. Stok sekarang: {$stokBaru} unit.");
    }

    /**
     * Update a specific restock record.
     */
    public function updateRestock(Request $request, Restock $restock)
    {
        $validatedData = $request->validate([
            'qty_restock' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
            'tgl_restock' => 'required|date',
        ]);

        // Calculate stock difference
        $oldQty = $restock->qty_restock;
        $newQty = $validatedData['qty_restock'];
        $qtyDifference = $newQty - $oldQty;

        // Update the good's stock based on the difference
        $good = $restock->good;
        $newStock = $good->stok + $qtyDifference;

        if ($newStock < 0) {
            return back()->withErrors(['qty_restock' => 'Jumlah restock tidak dapat dikurangi karena akan membuat stok menjadi negatif.'])->withInput();
        }

        $good->update(['stok' => $newStock]);

        // Update the restock record
        $restock->update($validatedData);

        return redirect('/dashboard/restock')->with('success',
            "Berhasil mengubah data restock {$good->nama}. Stok sekarang: {$newStock} unit.");
    }

    /**
     * Remove the specified restock record from storage.
     */
    public function destroy(Restock $restock)
    {
        $good = $restock->good;
        $qtyToRestore = $restock->qty_restock;

        // Check if removing this restock would make stock negative
        $newStock = $good->stok - $qtyToRestore;
        if ($newStock < 0) {
            return redirect('/dashboard/restock')->with('error',
                'Tidak dapat menghapus restock ini karena akan membuat stok menjadi negatif.');
        }

        // Restore stock by subtracting the restock quantity
        $good->update(['stok' => $newStock]);

        // Delete the restock record
        $restock->delete();

        return redirect('/dashboard/restock')->with('success',
            "Berhasil menghapus data restock {$good->nama}. Stok dikembalikan menjadi: {$newStock} unit.");
    }

    /**
     * Remove multiple restock records from storage.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:restocks,id',
        ]);

        $selectedIds = $request->input('selected_ids');

        if (empty($selectedIds)) {
            return redirect('/dashboard/restock')->with('error', 'Tidak ada data restock yang dipilih untuk dihapus.');
        }

        $restocks = Restock::whereIn('id', $selectedIds)->with('good')->get();
        $deletedCount = 0;
        $errors = [];

        foreach ($restocks as $restock) {
            $good = $restock->good;
            if (!$good) {
                continue; // Skip if good doesn't exist
            }

            $qtyToRestore = $restock->qty_restock;
            $newStock = $good->stok - $qtyToRestore;

            if ($newStock < 0) {
                $errors[] = "Tidak dapat menghapus restock untuk {$good->nama} (akan membuat stok negatif)";
                continue;
            }

            // Restore stock and delete restock
            $good->update(['stok' => $newStock]);
            $restock->delete();
            $deletedCount++;
        }

        if ($deletedCount > 0) {
            $message = $deletedCount . ' data restock berhasil dihapus dan stok telah disesuaikan.';
            if (!empty($errors)) {
                $message .= ' Beberapa data tidak dapat dihapus: ' . implode(', ', $errors);
            }
            return redirect('/dashboard/restock')->with('success', $message);
        }

        return redirect('/dashboard/restock')->with('error', 'Gagal menghapus data restock yang dipilih.');
    }
}
