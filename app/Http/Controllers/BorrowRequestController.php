<?php

namespace App\Http\Controllers;

use App\Models\BorrowRequest;
use App\Models\Borrowing;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BorrowRequestController extends Controller
{
    // Function untuk menampilkan semua permintaan peminjaman buku
    // Return: list semua borrow requests
    public function index()
    {
        $borrowRequests = BorrowRequest::with('user', 'book')->get();
        return response()->json([
            'success' => true,
            'message' => 'Borrow requests retrieved successfully',
            'data' => $borrowRequests,
        ]);
    }

    // Function untuk membuat permintaan peminjaman buku baru
    // Return: data permintaan peminjaman yang dibuat
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

    // Function untuk menampilkan detail permintaan peminjaman spesifik
    // Return: data permintaan peminjaman berdasarkan ID
    public function show(BorrowRequest $borrowRequest)
    {
        $borrowRequest->load('user', 'book');
        return response()->json([
            'success' => true,
            'message' => 'Borrow request retrieved successfully',
            'data' => $borrowRequest,
        ]);
    }

    // Function untuk menyetujui permintaan peminjaman
    // Ketika disetujui: status berubah jadi 'approved', buku berkurang 1 stock, dan membuat record peminjaman
    // Return: data permintaan dan record peminjaman yang dibuat
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

        // Set borrow date as today (when approved)
        $borrow_date = Carbon::now();
        
        // Use due_date from member's request 
        $due_date = Carbon::parse($borrowRequest->due_date);

        // Create borrowing record with due_date from request
        $borrowing = Borrowing::create([
            'user_id' => $borrowRequest->user_id,
            'book_id' => $borrowRequest->book_id,
            'borrow_date' => $borrow_date->toDateString(),
            'due_date' => $due_date->toDateString(),
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

    // Function untuk menolak permintaan peminjaman
    // Ketika ditolak: status berubah jadi 'rejected'
    // Return: data permintaan yang ditolak
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

    // Function untuk menghapus permintaan peminjaman
    // Return: pesan permintaan berhasil dihapus
    public function destroy(BorrowRequest $borrowRequest)
    {
        $borrowRequest->delete();

        return response()->json([
            'success' => true,
            'message' => 'Borrow request deleted successfully',
        ]);
    }
}
