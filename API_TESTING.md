# LibManage API Testing Guide

## Authentication

### Register User

```
POST http://localhost:8000/api/auth/register
Content-Type: application/json

{
  "name": "Test User",
  "email": "test@example.com",
  "password": "password123"
}
```

### Login

```
POST http://localhost:8000/api/auth/login
Content-Type: application/json

{
  "email": "admin@libmanage.com",
  "password": "admin123"
}
```

Response: Will get token to use in Authorization: Bearer {token}

### Logout

```
POST http://localhost:8000/api/auth/logout
Authorization: Bearer {token}
```

---

## Categories (Admin & Petugas Only)

### Get All Categories

```
GET http://localhost:8000/api/categories
Authorization: Bearer {token}
```

### Create Category (Admin & Petugas)

```
POST http://localhost:8000/api/categories
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Fiction"
}
```

### Get Single Category

```
GET http://localhost:8000/api/categories/1
Authorization: Bearer {token}
```

### Update Category (Admin & Petugas)

```
PUT http://localhost:8000/api/categories/1
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Fiction Updated"
}
```

### Delete Category (Admin & Petugas)

```
DELETE http://localhost:8000/api/categories/1
Authorization: Bearer {token}
```

---

## Books

### Get All Books (Public)

```
GET http://localhost:8000/api/books
```

### Get Single Book (Public)

```
GET http://localhost:8000/api/books/1
```

### Create Book (Admin & Petugas)

```
POST http://localhost:8000/api/books
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "The Hobbit",
  "author": "J.R.R. Tolkien",
  "category_id": 1,
  "stock": 5,
  "cover": "the-hobbit.jpg"
}
```

### Update Book (Admin & Petugas)

```
PUT http://localhost:8000/api/books/1
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "The Hobbit Updated",
  "author": "J.R.R. Tolkien",
  "category_id": 1,
  "stock": 3,
  "cover": "the-hobbit-updated.jpg"
}
```

### Delete Book (Admin & Petugas)

```
DELETE http://localhost:8000/api/books/1
Authorization: Bearer {token}
```

---

## Users (Admin Only)

### Get All Users (Admin)

```
GET http://localhost:8000/api/users
Authorization: Bearer {token}
```

### Create User (Admin)

```
POST http://localhost:8000/api/users
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "New User",
  "email": "newuser@example.com",
  "password": "password123",
  "role": "anggota"
}
```

### Get Single User (Admin)

```
GET http://localhost:8000/api/users/1
Authorization: Bearer {token}
```

### Update User (Admin)

```
PUT http://localhost:8000/api/users/1
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Updated User",
  "email": "updateduser@example.com",
  "role": "petugas"
}
```

### Delete User (Admin)

```
DELETE http://localhost:8000/api/users/1
Authorization: Bearer {token}
```

---

## Borrow Requests (Anggota Request, Petugas Approve/Reject)

### Get All Borrow Requests (Authenticated)

```
GET http://localhost:8000/api/borrow-requests
Authorization: Bearer {token}
```

### Create Borrow Request (Anggota)

```
POST http://localhost:8000/api/borrow-requests
Authorization: Bearer {token}
Content-Type: application/json

{
  "user_id": 3,
  "book_id": 1,
  "request_date": "2025-11-21"
}
```

### Get Single Borrow Request

```
GET http://localhost:8000/api/borrow-requests/1
Authorization: Bearer {token}
```

### Approve Borrow Request (Petugas/Admin)

```
POST http://localhost:8000/api/borrow-requests/1/approve
Authorization: Bearer {token}
```

### Reject Borrow Request (Petugas/Admin)

```
POST http://localhost:8000/api/borrow-requests/1/reject
Authorization: Bearer {token}
```

### Delete Borrow Request

```
DELETE http://localhost:8000/api/borrow-requests/1
Authorization: Bearer {token}
```

---

## Borrowings (Book Lending)

### Get All Borrowings

```
GET http://localhost:8000/api/borrowings
Authorization: Bearer {token}
```

### Create Borrowing (Petugas/Admin - Usually done via approve request)

```
POST http://localhost:8000/api/borrowings
Authorization: Bearer {token}
Content-Type: application/json

{
  "user_id": 3,
  "book_id": 1,
  "borrow_date": "2025-11-21"
}
```

### Get Single Borrowing

```
GET http://localhost:8000/api/borrowings/1
Authorization: Bearer {token}
```

### Return Book (Petugas/Admin)

```
POST http://localhost:8000/api/borrowings/1/return
Authorization: Bearer {token}
```

### Delete Borrowing (Admin)

```
DELETE http://localhost:8000/api/borrowings/1
Authorization: Bearer {token}
```

---

## Fines (Denda)

### Get All Fines

```
GET http://localhost:8000/api/fines
Authorization: Bearer {token}
```

### Get Single Fine

```
GET http://localhost:8000/api/fines/1
Authorization: Bearer {token}
```

### Mark Fine as Paid (Petugas/Admin)

```
POST http://localhost:8000/api/fines/1/mark-as-paid
Authorization: Bearer {token}
```

### Delete Fine (Admin)

```
DELETE http://localhost:8000/api/fines/1
Authorization: Bearer {token}
```

---

## Test Data (Available After Seeding)

### Users

-   Admin: admin@libmanage.com / admin123
-   Petugas: petugas@libmanage.com / petugas123
-   Anggota 1: anggota1@libmanage.com / anggota123
-   Anggota 2: anggota2@libmanage.com / anggota123
-   Anggota 3: anggota3@libmanage.com / anggota123

### Categories

1. Fiksi
2. Non-Fiksi
3. Referensi

### Books

1. Laskar Pelangi (5 stock)
2. Ayat-Ayat Cinta (4 stock)
3. Sapiens (3 stock)
4. Kamus Besar Bahasa Indonesia (2 stock)
5. Filosofi Teras (6 stock)

---

## Testing Flow

1. **Login** as admin/petugas/anggota to get token
2. **Create/View** categories and books
3. **Request** borrow as anggota
4. **Approve** request as petugas
5. **Return** book as petugas
6. **Check** fines if overdue (14 days = max borrow period)
7. **Mark fine as paid** as petugas

---

## Error Codes

-   401: Unauthorized (Missing or invalid token)
-   403: Forbidden (Insufficient permissions)
-   404: Not Found
-   422: Validation Error
-   500: Server Error
