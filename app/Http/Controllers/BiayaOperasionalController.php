<?php

namespace App\Http\Controllers;

use App\Models\BiayaOperasional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BiayaOperasionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mulai query, jangan langsung ambil semua data
        $query = BiayaOperasional::query();

        // Cek jika ada input 'search' dari URL
        if (request('search')) {
            // Tambahkan kondisi WHERE untuk mencari di kolom 'uraian'
            $query->where('uraian', 'like', '%' . request('search') . '%');
        }

        // Eksekusi query dengan urutan terbaru & paginasi
        // withQueryString() agar link pagination tetap membawa parameter search
        $biayaOperasional = $query->latest()->paginate(10)->withQueryString();

        // Kirim data yang sudah difilter (atau semua data) ke view
        return view('dashboard.biayaoperasional.index', compact('biayaOperasional'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.biayaoperasional.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'uraian' => 'required|max:255',
            'nominal' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'qty' => 'required|integer|min:1',
            'bukti_resi' => 'nullable|image|file|max:2048'
        ]);

        // [PERBAIKAN] Kalikan nominal dengan kuantitas sebelum menyimpan
        $validatedData['nominal'] = $validatedData['nominal'] * $validatedData['qty'];

        if ($request->file('bukti_resi')) {
            $validatedData['bukti_resi'] = $request->file('bukti_resi')->store('bukti-operasional', 'public');
        }

        BiayaOperasional::create($validatedData);
        return redirect('/dashboard/biayaoperasional')->with('success', 'Biaya operasional berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $biayaOperasional = BiayaOperasional::findOrFail($id);
        return view('dashboard.biayaoperasional.edit', compact('biayaOperasional'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $biayaOperasional = BiayaOperasional::findOrFail($id);

        $rules = [
            'uraian' => 'required|max:255',
            'nominal' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'qty' => 'required|integer|min:1',
            'bukti_resi' => 'nullable|image|file|max:2048'
        ];

        $validatedData = $request->validate($rules);

        // [PERBAIKAN] Kalikan nominal dengan kuantitas sebelum memperbarui
        $validatedData['nominal'] = $validatedData['nominal'] * $validatedData['qty'];

        if ($request->file('bukti_resi')) {
            if ($biayaOperasional->bukti_resi) {
                Storage::disk('public')->delete($biayaOperasional->bukti_resi);
            }
            $validatedData['bukti_resi'] = $request->file('bukti_resi')->store('bukti-operasional', 'public');
        }
        
        $biayaOperasional->update($validatedData);
        return redirect('/dashboard/biayaoperasional')->with('success', 'Biaya operasional berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $biayaOperasional = BiayaOperasional::findOrFail($id);

        try {
            if ($biayaOperasional->bukti_resi) {
                Storage::disk('public')->delete($biayaOperasional->bukti_resi);
            }

            $biayaOperasional->delete();
            return redirect('/dashboard/biayaoperasional')->with('success', 'Biaya operasional berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus biaya operasional: ' . $e->getMessage());
            return redirect('/dashboard/biayaoperasional')->with('error', 'Gagal menghapus data. Data ini mungkin terhubung dengan data lain.');
        }
    }
}
