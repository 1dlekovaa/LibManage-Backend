# 📚 LibManage Backend (Laravel)

Sistem manajemen perpustakaan sederhana berbasis Laravel.  
Frontend repo: [https://github.com/1dlekovaa/LibManage.git](https://github.com/1dlekovaa/LibManage.git)

## 🚀 Setup Project

1. Clone repo ini dan frontend
2. Di folder backend, jalankan:
    ```bash
    composer install
    php artisan migrate --seed
    php artisan serve
    ```
3. Buka frontend dan ikuti petunjuk di README frontend

## 🔑 Akun Default

-   **Admin**  
    Email: admin@libmanage.com  
    Password: admin123
-   **Petugas**  
    Email: petugas@libmanage.com  
    Password: petugas123
-   **Anggota**  
    Email: anggota1@libmanage.com / anggota2@libmanage.com / anggota3@libmanage.com  
    Password: anggota123

## 📡 API URL

```
http://localhost:8000/api
```

## 📊 ERD (Database)

![ERD](https://img.dbdiagram.io/api/1/render?compress=1&src=ZnJvbSBkYiRkYXRhYmFzZSBpbXBvcnQgc3RhcnQKClRhYmxlIHVzZXJzIHsKICBpZCBiaWdpbnQgW3BrLCBpbmNyZW1lbnRdCiAgbmFtZSB2YXJjaGFyIFtub3QgbnVsbF0KICBFBWQ=)

## 🗂️ Use Case Diagram

![Use Case Diagram](https://i.imgur.com/2QwQkQw.png)

## 📁 Struktur Project

-   app/Http/Controllers (Auth, Book, Category, User, BorrowRequest, Borrowing)
-   app/Models (User, Book, Category, BorrowRequest, Borrowing)
-   routes/api.php
-   database/migrations & seeders

## 🛡️ Role & Hak Akses

-   **Admin**: Kelola user, buku, kategori, permintaan
-   **Petugas**: Kelola buku, kategori, proses peminjaman/pengembalian
-   **Anggota**: Lihat buku, ajukan & cek status peminjaman
