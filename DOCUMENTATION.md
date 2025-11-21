# LibManage - Library Management System

Sistem manajemen perpustakaan berbasis Laravel dengan fitur multi-role, peminjaman buku, request peminjaman, dan denda keterlambatan.

## 📋 Daftar Isi

-   [Features](#features)
-   [Tech Stack](#tech-stack)
-   [Requirements](#requirements)
-   [Installation](#installation)
-   [Database](#database)
-   [API Documentation](#api-documentation)
-   [Authentication](#authentication)
-   [Testing](#testing)
-   [Project Structure](#project-structure)

---

## ✨ Features

### User Management

-   **Multi-Role System**: Admin, Petugas (Librarian), Anggota (Member)
-   **Role-Based Access Control**: Middleware untuk kontrol akses berbasis role
-   **User CRUD**: Admin dapat mengelola user

### Book Management

-   **Category Management**: Kelola kategori buku
-   **Book CRUD**: Kelola inventaris buku dengan stok tracking
-   **Book Display**: Anggota dapat melihat daftar buku tersedia

### Borrow System

-   **Borrow Requests**: Anggota dapat request peminjaman
-   **Request Approval**: Petugas approve/reject request
-   **Automatic Borrowing**: Otomatis membuat record peminjaman saat request diapprove
-   **Stok Management**: Stok otomatis berkurang saat dipinjam dan bertambah saat dikembalikan

### Fine Management

-   **Automatic Fine Calculation**: Fine otomatis dibuat jika pengembalian terlambat (>14 hari)
-   **Fine Payment**: Tracking pembayaran denda
-   **Fine Rate**: Rp 5.000 per hari keterlambatan

---

## 🛠 Tech Stack

-   **Framework**: Laravel 11
-   **Database**: MySQL
-   **Authentication**: Laravel Sanctum (API tokens)
-   **ORM**: Eloquent
-   **API Format**: RESTful JSON

---

## 📦 Requirements

-   PHP 8.2+
-   MySQL 8.0+
-   Composer
-   Node.js & npm (optional, untuk frontend)

---

## 🚀 Installation

### 1. Clone/Setup Project

```bash
cd "d:\Project Kelas 12\LibManage\Backend"
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Setup

```bash
# Copy env file
cp .env.example .env

# Generate app key
php artisan key:generate
```

### 4. Database Configuration

Pastikan `.env` memiliki konfigurasi database yang benar:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=libmanage
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Migrations & Seeding

```bash
# Fresh migration (drop & recreate tables)
php artisan migrate

# Seed dengan data default
php artisan db:seed
```

### 6. Start Development Server

```bash
php artisan serve
```

Server akan berjalan di `http://localhost:8000`

---

## 🗄 Database

### ERD (Entity Relationship Diagram)

```
users
├── id (PK)
├── name
├── email
├── password
├── role (admin, petugas, anggota)
└── timestamps

categories
├── id (PK)
├── name
└── timestamps

books
├── id (PK)
├── title
├── author
├── category_id (FK)
├── stock
├── cover
└── timestamps

borrow_requests
├── id (PK)
├── user_id (FK)
├── book_id (FK)
├── request_date
├── status (pending, approved, rejected)
└── timestamps

borrowings
├── id (PK)
├── user_id (FK)
├── book_id (FK)
├── borrow_date
├── return_date
├── status (dipinjam, dikembalikan, terlambat)
└── timestamps

fines
├── id (PK)
├── borrowing_id (FK)
├── amount
├── paid (boolean)
└── timestamps
```

### Migrations

Semua migration files tersedia di `database/migrations/`:

-   `0001_01_01_000000_create_users_table.php` - Users table
-   `2025_11_21_032627_add_role_to_users_table.php` - Add role column
-   `2025_11_21_033000_create_categories_table.php` - Categories table
-   `2025_11_21_033001_create_books_table.php` - Books table
-   `2025_11_21_033002_create_borrow_requests_table.php` - Borrow requests table
-   `2025_11_21_033003_create_borrowings_table.php` - Borrowings table
-   `2025_11_21_033004_create_fines_table.php` - Fines table

---

## 📡 API Documentation

### Base URL

```
http://localhost:8000/api
```

### Response Format

Semua response dalam format JSON:

**Success Response:**

```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... }
}
```

**Error Response:**

```json
{
    "success": false,
    "message": "Error description"
}
```

---

## 🔐 Authentication

### 1. Register (Public)

```
POST /api/auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123"
}
```

**Response (201):**

```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "anggota"
        },
        "token": "1|abc123..."
    }
}
```

### 2. Login (Public)

```
POST /api/auth/login
Content-Type: application/json

{
  "email": "admin@libmanage.com",
  "password": "admin123"
}
```

**Response (200):**

```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "Admin User",
            "email": "admin@libmanage.com",
            "role": "admin"
        },
        "token": "2|def456..."
    }
}
```

### 3. Get Current User (Protected)

```
GET /api/auth/me
Authorization: Bearer {token}
```

### 4. Logout (Protected)

```
POST /api/auth/logout
Authorization: Bearer {token}
```

---

## 📚 API Endpoints

### Books (Public View, Restricted Create/Update/Delete)

#### Get All Books (Public)

```
GET /api/books
```

#### Get Book by ID (Public)

```
GET /api/books/{id}
```

#### Create Book (Admin/Petugas)

```
POST /api/books
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Book Title",
  "author": "Author Name",
  "category_id": 1,
  "stock": 5,
  "cover": "book-cover.jpg"
}
```

#### Update Book (Admin/Petugas)

```
PUT /api/books/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Updated Title",
  "author": "Author Name",
  "category_id": 1,
  "stock": 10,
  "cover": "new-cover.jpg"
}
```

#### Delete Book (Admin/Petugas)

```
DELETE /api/books/{id}
Authorization: Bearer {token}
```

---

### Categories (Admin/Petugas Only)

#### Get All Categories

```
GET /api/categories
Authorization: Bearer {token}
```

#### Create Category

```
POST /api/categories
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Fiction"
}
```

#### Update Category

```
PUT /api/categories/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Science Fiction"
}
```

#### Delete Category

```
DELETE /api/categories/{id}
Authorization: Bearer {token}
```

---

### Borrow Requests (Member Request → Librarian Approve)

#### Get All Borrow Requests

```
GET /api/borrow-requests
Authorization: Bearer {token}
```

#### Create Borrow Request (Member)

```
POST /api/borrow-requests
Authorization: Bearer {token}
Content-Type: application/json

{
  "user_id": 3,
  "book_id": 1,
  "request_date": "2025-11-21"
}
```

#### Approve Request (Petugas/Admin)

```
POST /api/borrow-requests/{id}/approve
Authorization: Bearer {token}
```

**Response:** Otomatis membuat record peminjaman dan mengurangi stok buku

#### Reject Request (Petugas/Admin)

```
POST /api/borrow-requests/{id}/reject
Authorization: Bearer {token}
```

---

### Borrowings (Book Lending)

#### Get All Borrowings

```
GET /api/borrowings
Authorization: Bearer {token}
```

#### Return Book (Petugas/Admin)

```
POST /api/borrowings/{id}/return
Authorization: Bearer {token}
```

**Automatic Processing:**

-   Set return_date ke hari ini
-   Cek jika terlambat (> 14 hari dari borrow_date)
-   Jika terlambat: buat Fine record, set status="terlambat"
-   Jika tepat waktu: set status="dikembalikan"
-   Increment book stock

---

### Fines (Denda)

#### Get All Fines

```
GET /api/fines
Authorization: Bearer {token}
```

#### Mark Fine as Paid (Petugas/Admin)

```
POST /api/fines/{id}/mark-as-paid
Authorization: Bearer {token}
```

#### Delete Fine (Admin)

```
DELETE /api/fines/{id}
Authorization: Bearer {token}
```

---

### Users (Admin Only)

#### Get All Users

```
GET /api/users
Authorization: Bearer {token}
```

#### Create User

```
POST /api/users
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "New User",
  "email": "newuser@example.com",
  "password": "password123",
  "role": "anggota"
}
```

#### Update User

```
PUT /api/users/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Updated Name",
  "email": "newemail@example.com",
  "role": "petugas"
}
```

#### Delete User

```
DELETE /api/users/{id}
Authorization: Bearer {token}
```

---

## 🧪 Testing

### Postman Collection

Import file `LibManage_Postman_Collection.json` ke Postman untuk testing lengkap dengan pre-built requests dan environment variables.

### Default Test Credentials

**Admin Account:**

-   Email: `admin@libmanage.com`
-   Password: `admin123`
-   Role: `admin`

**Petugas Account:**

-   Email: `petugas@libmanage.com`
-   Password: `petugas123`
-   Role: `petugas`

**Member Accounts:**

-   Email: `anggota1@libmanage.com` / `anggota2@libmanage.com` / `anggota3@libmanage.com`
-   Password: `anggota123`
-   Role: `anggota`

### Default Test Data

**Categories:**

1. Fiksi
2. Non-Fiksi
3. Referensi

**Books:**

1. Laskar Pelangi (5 stock)
2. Ayat-Ayat Cinta (4 stock)
3. Sapiens (3 stock)
4. Kamus Besar Bahasa Indonesia (2 stock)
5. Filosofi Teras (6 stock)

---

## 🔄 Workflow Example

1. **Member Login**

    ```
    POST /api/auth/login
    → Get token
    ```

2. **View Books**

    ```
    GET /api/books
    → Lihat daftar buku tersedia
    ```

3. **Request Borrow**

    ```
    POST /api/borrow-requests
    → Status: pending
    ```

4. **Librarian Approve**

    ```
    POST /api/borrow-requests/{id}/approve
    → Status: approved
    → Borrowing record dibuat (status: dipinjam)
    → Book stock berkurang
    ```

5. **Member Return Book**

    ```
    POST /api/borrowings/{id}/return
    ```

6. **Auto Fine Check**

    - Jika return_date > borrow_date + 14 hari
    - Fine record dibuat dengan amount: hari_terlambat × Rp 5.000
    - Status borrowing: terlambat

7. **Mark Fine as Paid**
    ```
    POST /api/fines/{id}/mark-as-paid
    → paid: true
    ```

---

## 📁 Project Structure

```
app/
├── Models/
│   ├── User.php
│   ├── Category.php
│   ├── Book.php
│   ├── BorrowRequest.php
│   ├── Borrowing.php
│   └── Fine.php
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── CategoryController.php
│   │   ├── BookController.php
│   │   ├── UserController.php
│   │   ├── BorrowRequestController.php
│   │   ├── BorrowingController.php
│   │   └── FineController.php
│   └── Middleware/
│       └── CheckRole.php
database/
├── migrations/
│   ├── *_create_users_table.php
│   ├── *_add_role_to_users_table.php
│   ├── *_create_categories_table.php
│   ├── *_create_books_table.php
│   ├── *_create_borrow_requests_table.php
│   ├── *_create_borrowings_table.php
│   └── *_create_fines_table.php
└── seeders/
    └── DatabaseSeeder.php
routes/
├── api.php (All API routes)
└── web.php
```

---

## 🛡 Role-Based Access Control

### Admin

-   ✅ Full access to all resources
-   ✅ Manage users
-   ✅ Manage categories
-   ✅ Manage books
-   ✅ Approve/reject borrow requests
-   ✅ Manage borrowings
-   ✅ Manage fines

### Petugas

-   ✅ View all resources
-   ✅ Manage books
-   ✅ Approve/reject borrow requests
-   ✅ Manage borrowings (return books)
-   ✅ Mark fines as paid
-   ❌ Manage users
-   ❌ Manage categories (view only)

### Anggota

-   ✅ View books
-   ✅ Create borrow requests
-   ✅ View own borrow requests
-   ✅ View borrowing history
-   ❌ Modify/delete own requests
-   ❌ Access other users' data

---

## ⚙️ Configuration

### Fine Rate

Default: Rp 5.000 per hari keterlambatan
Lokasi: `app/Http/Controllers/BorrowingController.php` - method `return()` line ~57

### Borrow Duration

Default: 14 hari
Lokasi: `app/Http/Controllers/BorrowingController.php` - method `return()` line ~55

---

## 🐛 Troubleshooting

### Server tidak jalan

```bash
# Port 8000 sudah dipakai
php artisan serve --port=8001
```

### Database connection error

-   Pastikan MySQL running
-   Cek konfigurasi di `.env`
-   Verify credentials

### Migration error

```bash
# Fresh migration
php artisan migrate:fresh --seed
```

### Permission issues di storage

```bash
# Linux/Mac
chmod -R 777 storage bootstrap/cache

# Windows (run as admin)
attrib -R storage\* /S
```

---

## 📝 License

Proyek ini dibuat untuk kebutuhan UKK SMK. Semua kode dapat digunakan dan dimodifikasi sesuai kebutuhan.

---

## 👨‍💻 Development Notes

-   Semua controller menggunakan Resource Controller pattern
-   Eloquent Relations sudah diimplementasikan
-   Error handling dengan response JSON standar
-   Validation built-in di setiap endpoint
-   Middleware untuk role-based access control
-   Database transactions untuk operasi kompleks

---

**Selamat menggunakan LibManage! 🎉**

Untuk pertanyaan atau bug report, silakan hubungi tim development.
