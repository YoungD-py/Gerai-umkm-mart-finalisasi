<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BiayaOperasional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Validation\ValidationException; 
use Illuminate\Support\Facades\Log;

class OperationalController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            
            $query = BiayaOperasional::query();
            if ($request->get('search')) {
                $query->where('uraian', 'like', '%' . $request->get('search') . '%');
            }

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

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'uraian' => 'required|max:255',
                'nominal' => 'required|numeric|min:0', 
                'tanggal' => 'required|date_format:Y-m-d',
                'qty' => 'required|integer|min:1',
                'bukti_resi' => 'nullable|image|file|max:2048'
            ]);

            $validatedData['nominal'] = $validatedData['nominal'] * $validatedData['qty'];

            if ($request->file('bukti_resi')) {
                $validatedData['bukti_resi'] = $request->file('bukti_resi')->store('bukti-operasional', 'public');
            }
            
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

    public function show($id)
    {
        try {
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

    public function update(Request $request, $id)
    {
        try {
            $expense = BiayaOperasional::find($id);
            if (!$expense) {
                return $this->notFoundResponse();
            }

            $validatedData = $request->validate([
                'uraian' => 'required|max:255',
                'nominal' => 'required|numeric|min:0', // Ini harga satuan
                'tanggal' => 'required|date_format:Y-m-d',
                'qty' => 'required|integer|min:1',
                'bukti_resi' => 'nullable|image|file|max:2048'
            ]);
            
            $validatedData['nominal'] = $validatedData['nominal'] * $validatedData['qty'];

            if ($request->file('bukti_resi')) {
                if ($expense->bukti_resi) {
                    Storage::disk('public')->delete($expense->bukti_resi);
                }
                $validatedData['bukti_resi'] = $request->file('bukti_resi')->store('bukti-operasional', 'public');
            }

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

    public function destroy($id)
    {
        try {
            $expense = BiayaOperasional::find($id);
            if (!$expense) {
                return $this->notFoundResponse();
            }

            if ($expense->bukti_resi) {
                Storage::disk('public')->delete($expense->bukti_resi);
            }

            $expense->delete();

            return response()->json([
                'success' => true,
                'message' => 'Operational expense deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e, 'Failed to delete operational expense');
        }
    }

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