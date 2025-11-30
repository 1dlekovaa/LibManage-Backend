<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $borrowings = Borrowing::with('user', 'book')->get();
        return response()->json([
            'success' => true,
            'message' => 'Borrowings retrieved successfully',
            'data' => $borrowings,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'required|date',
        ]);

        $borrowing = Borrowing::create($validated);
        $borrowing->load('user', 'book');

        return response()->json([
            'success' => true,
            'message' => 'Borrowing created successfully',
            'data' => $borrowing,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load('user', 'book');
        return response()->json([
            'success' => true,
            'message' => 'Borrowing retrieved successfully',
            'data' => $borrowing,
        ]);
    }

    /**
     * Return a borrowed book
     */
    public function return(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'dipinjam') {
            return response()->json([
                'success' => false,
                'message' => 'Only borrowed books can be returned',
            ], 400);
        }

        $returnDate = now()->toDateString();
        $borrowing->update([
            'return_date' => $returnDate,
            'status' => 'dikembalikan',
        ]);

        // Increase book stock
        $borrowing->book->increment('stock');

        $borrowing->load('user', 'book');

        return response()->json([
            'success' => true,
            'message' => 'Book returned successfully',
            'data' => $borrowing,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrowing $borrowing)
    {
        $borrowing->delete();

        return response()->json([
            'success' => true,
            'message' => 'Borrowing deleted successfully',
        ]);
    }
}
