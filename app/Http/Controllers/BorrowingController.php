<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    // Function untuk menampilkan semua data peminjaman buku
    // Return: list semua peminjaman dengan status dipinjam atau dikembalikan
    public function index()
    {
        $borrowings = Borrowing::with('user', 'book')->get();
        return response()->json([
            'success' => true,
            'message' => 'Borrowings retrieved successfully',
            'data' => $borrowings,
        ]);
    }

    // Function untuk membuat record peminjaman baru
    // Return: data peminjaman yang dibuat
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

    // Function untuk menampilkan detail peminjaman spesifik
    // Return: data peminjaman berdasarkan ID
    public function show(Borrowing $borrowing)
    {
        $borrowing->load('user', 'book');
        return response()->json([
            'success' => true,
            'message' => 'Borrowing retrieved successfully',
            'data' => $borrowing,
        ]);
    }

    // Function untuk mengembalikan buku yang sudah dipinjam
    // Ketika dikembalikan: status berubah jadi 'dikembalikan', return_date diisi, dan stock buku bertambah 1
    // Return: data peminjaman yang sudah diupdate
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

    // Function untuk menghapus record peminjaman
    // Return: pesan record peminjaman berhasil dihapus
    public function destroy(Borrowing $borrowing)
    {
        $borrowing->delete();

        return response()->json([
            'success' => true,
            'message' => 'Borrowing deleted successfully',
        ]);
    }
}
