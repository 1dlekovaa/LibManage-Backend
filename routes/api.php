<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BorrowRequestController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\FineController;

/**
 * Authentication Routes (Public)
 */
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('auth/me', [AuthController::class, 'me'])->middleware('auth:sanctum');

/**
 * Public Routes
 */

/**
 * Category Routes (Admin & Petugas)
 */
Route::apiResource('categories', CategoryController::class)->middleware(['auth:sanctum', 'role:admin,petugas']);
Route::get('categories', [CategoryController::class, 'index']);

/**
 * Book Routes (Semua bisa lihat, hanya Admin & Petugas bisa CRUD)
 */
Route::get('books', [BookController::class, 'index']);
Route::get('books/{book}', [BookController::class, 'show']);
Route::post('books', [BookController::class, 'store'])->middleware(['auth:sanctum', 'role:admin,petugas']);
Route::put('books/{book}', [BookController::class, 'update'])->middleware(['auth:sanctum', 'role:admin,petugas']);
Route::delete('books/{book}', [BookController::class, 'destroy'])->middleware(['auth:sanctum', 'role:admin,petugas']);

/**
 * User Routes (Admin only)
 */
Route::apiResource('users', UserController::class)->middleware(['auth:sanctum', 'role:admin']);

/**
 * Borrow Request Routes
 */
Route::get('borrow-requests', [BorrowRequestController::class, 'index'])->middleware('auth:sanctum');
Route::post('borrow-requests', [BorrowRequestController::class, 'store'])->middleware('auth:sanctum');
Route::get('borrow-requests/{borrowRequest}', [BorrowRequestController::class, 'show'])->middleware('auth:sanctum');
Route::post('borrow-requests/{borrowRequest}/approve', [BorrowRequestController::class, 'approve'])->middleware(['auth:sanctum', 'role:admin,petugas']);
Route::post('borrow-requests/{borrowRequest}/reject', [BorrowRequestController::class, 'reject'])->middleware(['auth:sanctum', 'role:admin,petugas']);
Route::delete('borrow-requests/{borrowRequest}', [BorrowRequestController::class, 'destroy'])->middleware('auth:sanctum');

/**
 * Borrowing Routes
 */
Route::get('borrowings', [BorrowingController::class, 'index'])->middleware('auth:sanctum');
Route::post('borrowings', [BorrowingController::class, 'store'])->middleware(['auth:sanctum', 'role:admin,petugas']);
Route::get('borrowings/{borrowing}', [BorrowingController::class, 'show'])->middleware('auth:sanctum');
Route::post('borrowings/{borrowing}/return', [BorrowingController::class, 'return'])->middleware(['auth:sanctum', 'role:admin,petugas']);
Route::delete('borrowings/{borrowing}', [BorrowingController::class, 'destroy'])->middleware(['auth:sanctum', 'role:admin']);

/**
 * Fine Routes
 */
Route::get('fines', [FineController::class, 'index'])->middleware('auth:sanctum');
Route::get('fines/{fine}', [FineController::class, 'show'])->middleware('auth:sanctum');
Route::post('fines/{fine}/mark-as-paid', [FineController::class, 'markAsPaid'])->middleware(['auth:sanctum', 'role:admin,petugas']);
Route::delete('fines/{fine}', [FineController::class, 'destroy'])->middleware(['auth:sanctum', 'role:admin']);
