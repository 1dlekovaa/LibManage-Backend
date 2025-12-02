<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    // Function untuk menampilkan semua data buku dengan kategori dan URL cover
    // Return: list semua buku dengan URL gambar cover
    public function index()
    {
        $books = Book::with('category')->get()->map(function ($book) {
            if ($book->cover) {
                $book->cover_url = asset('storage/books/' . $book->cover);
            }
            return $book;
        });
        return response()->json([
            'success' => true,
            'message' => 'Books retrieved successfully',
            'data' => $books,
        ]);
    }

    // Function untuk membuat buku baru dengan upload gambar cover
    // Return: data buku yang baru dibuat
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sinopsis' => 'nullable|string',
        ]);

        // Handle cover upload
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $filename = time() . '_' . $file->getClientOriginalName();
            $validated['cover'] = $file->storeAs('books', $filename, 'public');
        }

        $book = Book::create($validated);
        $book->load('category');

        if ($book->cover) {
            $book->cover_url = asset('storage/' . $book->cover);
        }

        return response()->json([
            'success' => true,
            'message' => 'Book created successfully',
            'data' => $book,
        ], 201);
    }

    // Function untuk menampilkan detail buku spesifik dengan kategori dan cover
    // Return: data buku berdasarkan ID
    public function show(Book $book)
    {
        $book->load('category');
        if ($book->cover) {
            $book->cover_url = asset('storage/' . $book->cover);
        }
        return response()->json([
            'success' => true,
            'message' => 'Book retrieved successfully',
            'data' => $book,
        ]);
    }

    // Function untuk mengubah data buku dan bisa update gambar cover
    // Return: data buku yang sudah diupdate
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sinopsis' => 'nullable|string',
        ]);

        // Handle cover upload
        if ($request->hasFile('cover')) {
            // Delete old cover if exists
            if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            }
            $file = $request->file('cover');
            $filename = time() . '_' . $file->getClientOriginalName();
            $validated['cover'] = $file->storeAs('books', $filename, 'public');
        }

        $book->update($validated);
        $book->load('category');

        if ($book->cover) {
            $book->cover_url = asset('storage/' . $book->cover);
        }

        return response()->json([
            'success' => true,
            'message' => 'Book updated successfully',
            'data' => $book,
        ]);
    }

    // Function untuk menghapus buku dan file gambar covernya
    // Return: pesan buku berhasil dihapus
    public function destroy(Book $book)
    {
        // Delete cover file if exists
        if ($book->cover && Storage::disk('public')->exists($book->cover)) {
            Storage::disk('public')->delete($book->cover);
        }
        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Book deleted successfully',
        ]);
    }
}
