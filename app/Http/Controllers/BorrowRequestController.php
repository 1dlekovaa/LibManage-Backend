<?php

namespace App\Http\Controllers;

use App\Models\BorrowRequest;
use App\Models\Borrowing;
use App\Models\Book;
use Illuminate\Http\Request;

class BorrowRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $borrowRequests = BorrowRequest::with('user', 'book')->get();
        return response()->json([
            'success' => true,
            'message' => 'Borrow requests retrieved successfully',
            'data' => $borrowRequests,
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
            'request_date' => 'required|date',
            'due_date' => 'required|date|after:request_date',
        ]);

        $borrowRequest = BorrowRequest::create($validated);
        $borrowRequest->load('user', 'book');

        return response()->json([
            'success' => true,
            'message' => 'Borrow request created successfully',
            'data' => $borrowRequest,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(BorrowRequest $borrowRequest)
    {
        $borrowRequest->load('user', 'book');
        return response()->json([
            'success' => true,
            'message' => 'Borrow request retrieved successfully',
            'data' => $borrowRequest,
        ]);
    }

    /**
     * Approve a borrow request (only for petugas/admin)
     */
    public function approve(BorrowRequest $borrowRequest)
    {
        if ($borrowRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending requests can be approved',
            ], 400);
        }

        // Check book stock
        $book = Book::find($borrowRequest->book_id);
        if ($book->stock <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Book stock is not available',
            ], 400);
        }

        // Update request status
        $borrowRequest->update(['status' => 'approved']);

        // Create borrowing record
        $borrowing = Borrowing::create([
            'user_id' => $borrowRequest->user_id,
            'book_id' => $borrowRequest->book_id,
            'borrow_date' => now()->toDateString(),
            'status' => 'dipinjam',
        ]);

        // Decrease book stock
        $book->decrement('stock');

        return response()->json([
            'success' => true,
            'message' => 'Borrow request approved successfully',
            'data' => [
                'borrow_request' => $borrowRequest->load('user', 'book'),
                'borrowing' => $borrowing->load('user', 'book'),
            ],
        ]);
    }

    /**
     * Reject a borrow request (only for petugas/admin)
     */
    public function reject(BorrowRequest $borrowRequest)
    {
        if ($borrowRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending requests can be rejected',
            ], 400);
        }

        $borrowRequest->update(['status' => 'rejected']);

        return response()->json([
            'success' => true,
            'message' => 'Borrow request rejected successfully',
            'data' => $borrowRequest->load('user', 'book'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BorrowRequest $borrowRequest)
    {
        $borrowRequest->delete();

        return response()->json([
            'success' => true,
            'message' => 'Borrow request deleted successfully',
        ]);
    }
}
