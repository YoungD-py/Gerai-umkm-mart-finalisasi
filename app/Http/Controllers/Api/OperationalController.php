<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BiayaOperasional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <-- WAJIB TAMBAHKAN
use Illuminate\Validation\ValidationException; // <-- WAJIB TAMBAHKAN
use Illuminate\Support\Facades\Log;

class OperationalController extends Controller
{
    /**
     * Menampilkan daftar biaya operasional (sudah sinkron)
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            
            // Logika search dari web controller
            $query = BiayaOperasional::query();
            if ($request->get('search')) {
                $query->where('uraian', 'like', '%' . $request->get('search') . '%');
            }

            // Dihilangkan ->with('user') karena tidak ada di Model
            $expenses = $query->latest()->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Operational expenses retrieved successfully',
                'data' => $expenses
            ], 200);
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e, 'Failed to retrieve operational expenses');
        }
    }

    /**
     * Menyimpan biaya operasional baru (sudah sinkron)
     * PENTING: Request ini harus dikirim sebagai 'multipart/form-data', BUKAN raw/json
     */
    public function store(Request $request)
    {
        try {
            // Validasi disamakan dengan WEB controller
            $validatedData = $request->validate([
                'uraian' => 'required|max:255',
                'nominal' => 'required|numeric|min:0', // Ini adalah harga satuan
                'tanggal' => 'required|date_format:Y-m-d',
                'qty' => 'required|integer|min:1',
                'bukti_resi' => 'nullable|image|file|max:2048'
            ]);

            // --- LOGIKA BISNIS DARI WEB ---
            // 1. Hitung total nominal
            $validatedData['nominal'] = $validatedData['nominal'] * $validatedData['qty'];

            // 2. Handle file upload
            if ($request->file('bukti_resi')) {
                $validatedData['bukti_resi'] = $request->file('bukti_resi')->store('bukti-operasional', 'public');
            }
            // --- AKHIR LOGIKA BISNIS ---
            
            // Dihilangkan user_id karena tidak ada di Model
            $expense = BiayaOperasional::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Operational expense created successfully',
                'data' => $expense
            ], 201);
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e, 'Failed to create operational expense');
        }
    }

    /**
     * Menampilkan detail 1 biaya operasional (sudah sinkron)
     */
    public function show($id)
    {
        try {
            // Dihilangkan ->with('user')
            $expense = BiayaOperasional::find($id);

            if (!$expense) {
                return $this->notFoundResponse();
            }

            return response()->json([
                'success' => true,
                'message' => 'Operational expense retrieved successfully',
                'data' => $expense
            ], 200);
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e, 'Failed to retrieve operational expense');
        }
    }

    /**
     * Update biaya operasional (sudah sinkron)
     * PENTING: Karena ada file upload, method di Postman harus POST
     * dan URL-nya harus /api/operational/{id}
     * Di form-data, tambahkan field _method dengan value PUT
     */
    public function update(Request $request, $id)
    {
        try {
            $expense = BiayaOperasional::find($id);
            if (!$expense) {
                return $this->notFoundResponse();
            }

            // Validasi disamakan dengan WEB controller
            $validatedData = $request->validate([
                'uraian' => 'required|max:255',
                'nominal' => 'required|numeric|min:0', // Ini harga satuan
                'tanggal' => 'required|date_format:Y-m-d',
                'qty' => 'required|integer|min:1',
                'bukti_resi' => 'nullable|image|file|max:2048'
            ]);
            
            // --- LOGIKA BISNIS DARI WEB ---
            // 1. Hitung total nominal
            $validatedData['nominal'] = $validatedData['nominal'] * $validatedData['qty'];

            // 2. Handle file upload (hapus yg lama jika ada yg baru)
            if ($request->file('bukti_resi')) {
                if ($expense->bukti_resi) {
                    Storage::disk('public')->delete($expense->bukti_resi);
                }
                $validatedData['bukti_resi'] = $request->file('bukti_resi')->store('bukti-operasional', 'public');
            }
            // --- AKHIR LOGIKA BISNIS ---

            $expense->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Operational expense updated successfully',
                'data' => $expense
            ], 200);
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e, 'Failed to update operational expense');
        }
    }

    /**
     * Menghapus biaya operasional (sudah sinkron)
     */
    public function destroy($id)
    {
        try {
            $expense = BiayaOperasional::find($id);
            if (!$expense) {
                return $this->notFoundResponse();
            }

            // --- LOGIKA BISNIS DARI WEB ---
            // 1. Hapus file dari storage
            if ($expense->bukti_resi) {
                Storage::disk('public')->delete($expense->bukti_resi);
            }
            // --- AKHIR LOGIKA BISNIS ---

            $expense->delete();

            return response()->json([
                'success' => true,
                'message' => 'Operational expense deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e, 'Failed to delete operational expense');
        }
    }

    // --- Helper Functions ---
    private function notFoundResponse()
    {
        return response()->json(['success' => false, 'message' => 'Operational expense not found'], 404);
    }
    
    private function validationErrorResponse(ValidationException $e)
    {
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $e->errors()
        ], 422);
    }

    private function serverErrorResponse(\Exception $e, $message = 'Server Error')
    {
        Log::error($message . ': ' . $e->getMessage()); // Catat error
        return response()->json([
            'success' => false,
            'message' => $message . ': ' . $e->getMessage()
        ], 500);
    }
}