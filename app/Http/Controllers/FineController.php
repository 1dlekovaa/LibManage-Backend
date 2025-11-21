<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use Illuminate\Http\Request;

class FineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fines = Fine::with('borrowing.user', 'borrowing.book')->get();
        return response()->json([
            'success' => true,
            'message' => 'Fines retrieved successfully',
            'data' => $fines,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Fine $fine)
    {
        $fine->load('borrowing.user', 'borrowing.book');
        return response()->json([
            'success' => true,
            'message' => 'Fine retrieved successfully',
            'data' => $fine,
        ]);
    }

    /**
     * Mark fine as paid
     */
    public function markAsPaid(Fine $fine)
    {
        if ($fine->paid) {
            return response()->json([
                'success' => false,
                'message' => 'Fine is already paid',
            ], 400);
        }

        $fine->update(['paid' => true]);
        $fine->load('borrowing.user', 'borrowing.book');

        return response()->json([
            'success' => true,
            'message' => 'Fine marked as paid successfully',
            'data' => $fine,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fine $fine)
    {
        $fine->delete();

        return response()->json([
            'success' => true,
            'message' => 'Fine deleted successfully',
        ]);
    }
}
