<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('category')->get();
        return response()->json([
            'success' => true,
            'message' => 'Books retrieved successfully',
            'data' => $books,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'cover' => 'nullable|string',
        ]);

        $book = Book::create($validated);
        $book->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Book created successfully',
            'data' => $book,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load('category');
        return response()->json([
            'success' => true,
            'message' => 'Book retrieved successfully',
            'data' => $book,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'cover' => 'nullable|string',
        ]);

        $book->update($validated);
        $book->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Book updated successfully',
            'data' => $book,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Book deleted successfully',
        ]);
    }
}
