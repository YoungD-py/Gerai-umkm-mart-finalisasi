<?php

namespace App\Http\Controllers;

use App\Models\ReturnBarang;
use App\Models\Good;
use App\Models\User;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index()
    {
        return view('dashboard.returns.index', [
            'active' => 'returns',
            'returns' => ReturnBarang::with(['good.category', 'user']) // Eager load good and its category
                ->latest()
                ->filter(request(['search']))
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('dashboard.returns.create', [
            'active' => 'data',
            'goods' => Good::where('stok', '>', 0)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'good_id'     => 'required|exists:goods,id',
            'tgl_return'  => 'required|date',
            'qty_return'  => 'required|integer|min:1',
            'alasan'      => 'required|in:Rusak,Cacat,Kadaluarsa,Salah Kirim,Lainnya',
            'keterangan'  => 'nullable|string',
        ]);

        // Automatically set the user_id to the authenticated user
        $validatedData['user_id'] = auth()->user()->id;

        $good = Good::find($validatedData['good_id']);

        if ($validatedData['qty_return'] > $good->stok) {
            return back()->withErrors([
                'qty_return' => 'Jumlah return tidak boleh melebihi stok tersedia (' . $good->stok . ')'
            ])->withInput();
        }

        ReturnBarang::create($validatedData);

        $good->decrement('stok', $validatedData['qty_return']);

        return redirect('/dashboard/returns')->with('success', 'Return barang berhasil ditambahkan.');
    }

    public function edit(ReturnBarang $return)
    {
        return view('dashboard.returns.edit', [
            'active' => 'data',
            'return' => $return,
            'goods'  => Good::all(),
            'users'  => User::all(),
        ]);
    }

    public function update(Request $request, ReturnBarang $return)
    {
        $validatedData = $request->validate([
            'good_id'     => 'required|exists:goods,id',
            'user_id'     => 'required|exists:users,id',
            'tgl_return'  => 'required|date',
            'qty_return'  => 'required|integer|min:1',
            'alasan'      => 'required|in:Rusak,Cacat,Kadaluarsa,Salah Kirim,Lainnya',
            'keterangan'  => 'nullable|string',
        ]);

        $newGood = Good::find($validatedData['good_id']);
        $oldGood = Good::find($return->good_id);

        // Balikin stok lama
        $oldGood->increment('stok', $return->qty_return);

        // Validasi stok baru
        if ($validatedData['qty_return'] > $newGood->stok) {
            // Balikin ke stok awal karena gagal
            $oldGood->decrement('stok', $return->qty_return);

            return back()->withErrors([
                'qty_return' => 'Jumlah return tidak boleh melebihi stok tersedia (' . $newGood->stok . ')'
            ])->withInput();
        }

        $return->update($validatedData);

        $newGood->decrement('stok', $validatedData['qty_return']);

        return redirect('/dashboard/returns')->with('success', 'Data return berhasil diperbarui.');
    }

    public function destroy(ReturnBarang $return)
    {
        $good = Good::find($return->good_id);
        $good->increment('stok', $return->qty_return);

        $return->delete();

        return redirect('/dashboard/returns')->with('success', 'Data return berhasil dihapus dan stok dikembalikan.');
    }
}
