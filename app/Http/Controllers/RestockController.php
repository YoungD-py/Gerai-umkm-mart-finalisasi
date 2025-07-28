<?php

namespace App\Http\Controllers;

use App\Models\Good;
use App\Models\Restock; // Import model Restock
use Illuminate\Http\Request;

class RestockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.restock.index', [
            'active' => 'restock',
            'goods' => Good::with('category')
                ->filter(request(['search']))
                ->paginate(10)
                ->withQueryString(),
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
