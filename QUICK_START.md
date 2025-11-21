# LibManage - Quick Start Guide

## 🚀 Memulai dalam 5 Menit

### 1. Setup & Run Server

```bash
cd "d:\Project Kelas 12\LibManage\Backend"
php artisan serve
```

Server akan berjalan di: **http://localhost:8000**

### 2. Test API di Browser/Postman

#### Login sebagai Admin

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@libmanage.com","password":"admin123"}'
```

Response:

```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {...},
    "token": "your_token_here"
  }
}
```

#### Gunakan token untuk protected routes

```bash
curl http://localhost:8000/api/books \
  -H "Authorization: Bearer your_token_here"
```

---

## 🔑 Test Credentials

| Role      | Email                  | Password   |
| --------- | ---------------------- | ---------- |
| Admin     | admin@libmanage.com    | admin123   |
| Petugas   | petugas@libmanage.com  | petugas123 |
| Anggota 1 | anggota1@libmanage.com | anggota123 |
| Anggota 2 | anggota2@libmanage.com | anggota123 |
| Anggota 3 | anggota3@libmanage.com | anggota123 |

---

## 📋 Quick API Examples

### 1. Get All Books (Public)

```
GET http://localhost:8000/api/books
```

### 2. Create Book (Admin/Petugas)

```
POST http://localhost:8000/api/books
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "title": "New Book",
  "author": "Author Name",
  "category_id": 1,
  "stock": 5,
  "cover": "cover.jpg"
}
```

### 3. Create Borrow Request (Anggota)

```
POST http://localhost:8000/api/borrow-requests
Authorization: Bearer {anggota_token}
Content-Type: application/json

{
  "user_id": 3,
  "book_id": 1,
  "request_date": "2025-11-21"
}
```

### 4. Approve Request (Petugas)

```
POST http://localhost:8000/api/borrow-requests/1/approve
Authorization: Bearer {petugas_token}
```

### 5. Return Book (Petugas)

```
POST http://localhost:8000/api/borrowings/1/return
Authorization: Bearer {petugas_token}
```

### 6. Mark Fine as Paid (Petugas)

```
POST http://localhost:8000/api/fines/1/mark-as-paid
Authorization: Bearer {petugas_token}
```

---

## 📊 Flow Peminjaman

```
1. Anggota login
   ↓
2. Lihat daftar buku: GET /api/books
   ↓
3. Buat request: POST /api/borrow-requests
   Status: pending
   ↓
4. Petugas approve: POST /borrow-requests/{id}/approve
   Status: approved
   Borrowing dibuat (dipinjam)
   Stok berkurang
   ↓
5. Anggota kembalikan: POST /api/borrowings/{id}/return
   ↓
6. Sistem cek keterlambatan:
   - Jika tepat waktu: status = dikembalikan, stok bertambah
   - Jika terlambat: status = terlambat, fine dibuat, stok bertambah
   ↓
7. Jika ada denda:
   Petugas tandai bayar: POST /api/fines/{id}/mark-as-paid
```

---

## 🔧 Restore Database

Jika data corrupt atau ingin reset:

```bash
# Option 1: Fresh migrate dengan seed
php artisan migrate:fresh --seed

# Option 2: Drop dan recreate
php artisan db:wipe
php artisan migrate
php artisan db:seed
```

---

## 📱 Testing dengan Postman

### Import Collection

1. Buka Postman
2. Import: `LibManage_Postman_Collection.json`
3. Setup environment variables di sidebar kanan
4. Run requests dengan authentication sudah built-in

### Manual Testing

1. Login dulu (auth/login) - simpan token
2. Set header: `Authorization: Bearer {token_dari_login}`
3. Test endpoint

---

## 🗂️ File Penting

-   `routes/api.php` - Semua API routes
-   `app/Http/Controllers/` - Business logic
-   `app/Models/` - Database models & relations
-   `database/migrations/` - Schema tables
-   `database/seeders/` - Default data
-   `DOCUMENTATION.md` - Dokumentasi lengkap
-   `API_TESTING.md` - Contoh API calls
-   `LibManage_Postman_Collection.json` - Postman collection

---

## 🎯 Common Tasks

### Tambah Kategori Baru

```bash
curl -X POST http://localhost:8000/api/categories \
  -H "Authorization: Bearer {admin_token}" \
  -H "Content-Type: application/json" \
  -d '{"name":"New Category"}'
```

### Lihat Semua Request Peminjaman

```bash
curl http://localhost:8000/api/borrow-requests \
  -H "Authorization: Bearer {petugas_token}"
```

### Lihat Detail Peminjaman

```bash
curl http://localhost:8000/api/borrowings/1 \
  -H "Authorization: Bearer {token}"
```

---

## ⚡ Performance Tips

1. Server development sudah cukup untuk testing
2. Untuk production: gunakan production-ready server (Nginx, Apache)
3. Cache queries jika ada performance issue
4. Optimize database indexes

---

## ✅ Checklist Testing

-   [ ] Login dengan 3 role berbeda (admin, petugas, anggota)
-   [ ] View books (public endpoint)
-   [ ] Create book (admin/petugas only)
-   [ ] Create category (admin/petugas only)
-   [ ] Request borrow sebagai anggota
-   [ ] Approve request sebagai petugas
-   [ ] Return book sebagai petugas
-   [ ] Check fine jika terlambat
-   [ ] Mark fine as paid

---

## 🆘 Bantuan

**Error 401 (Unauthorized)**

-   Pastikan token valid
-   Token mungkin expired

**Error 403 (Forbidden)**

-   Role tidak memiliki akses
-   Admin: access all
-   Petugas: tidak bisa manage users
-   Anggota: hanya bisa request

**Error 404 (Not Found)**

-   Resource tidak ada
-   Check ID yang digunakan

**Error 422 (Validation Error)**

-   Input tidak valid
-   Check required fields di documentation

---

## 📞 Support

Dokumentasi lengkap: `DOCUMENTATION.md`
API Testing examples: `API_TESTING.md`

---

**Happy coding! 🎉**
