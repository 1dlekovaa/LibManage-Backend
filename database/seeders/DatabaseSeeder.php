<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Users
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@libmanage.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Petugas Perpustakaan',
            'email' => 'petugas@libmanage.com',
            'password' => Hash::make('petugas123'),
            'role' => 'petugas',
        ]);

        User::create([
            'name' => 'Anggota 1',
            'email' => 'anggota1@libmanage.com',
            'password' => Hash::make('anggota123'),
            'role' => 'anggota',
        ]);

        User::create([
            'name' => 'Anggota 2',
            'email' => 'anggota2@libmanage.com',
            'password' => Hash::make('anggota123'),
            'role' => 'anggota',
        ]);

        User::create([
            'name' => 'Anggota 3',
            'email' => 'anggota3@libmanage.com',
            'password' => Hash::make('anggota123'),
            'role' => 'anggota',
        ]);

        // Create Categories
        $category1 = Category::create(['name' => 'Fiksi']);
        $category2 = Category::create(['name' => 'Non-Fiksi']);
        $category3 = Category::create(['name' => 'Referensi']);

        // Create Books
        Book::create([
            'title' => 'Laskar Pelangi',
            'author' => 'Andrea Hirata',
            'category_id' => $category1->id,
            'stock' => 5,
            'cover' => null,
            'sinopsis' => 'Sebuah novel yang menceritakan kisah perjuangan sekelompok anak-anak berbakat dari keluarga miskin di sebuah sekolah dasar di Pulau Belitung yang bermimpi untuk meraih pendidikan terbaik.',
        ]);

        Book::create([
            'title' => 'Ayat-Ayat Cinta',
            'author' => 'Habiburrahman El Shirazy',
            'category_id' => $category1->id,
            'stock' => 4,
            'cover' => null,
            'sinopsis' => 'Novel tentang kisah cinta seorang mahasiswa Indonesia bernama Fahri yang menimba ilmu di Kairo, Mesir, dengan latar belakang nilai-nilai agama Islam.',
        ]);

        Book::create([
            'title' => 'Sapiens',
            'author' => 'Yuval Noah Harari',
            'category_id' => $category2->id,
            'stock' => 3,
            'cover' => null,
            'sinopsis' => 'Sebuah buku non-fiksi yang menjelajahi sejarah umat manusia dari munculnya Homo sapiens hingga era modern, menjelaskan bagaimana manusia mendominasi dunia.',
        ]);

        Book::create([
            'title' => 'Kamus Besar Bahasa Indonesia',
            'author' => 'Pusat Bahasa',
            'category_id' => $category3->id,
            'stock' => 2,
            'cover' => null,
            'sinopsis' => 'Referensi lengkap bahasa Indonesia yang berisi ribuan kata, frasa, dan penjelasan mendalam tentang penggunaan dan makna dalam bahasa Indonesia standar.',
        ]);

        Book::create([
            'title' => 'Filosofi Teras',
            'author' => 'Henry Manampiring',
            'category_id' => $category2->id,
            'stock' => 6,
            'cover' => null,
            'sinopsis' => 'Buku filosofi yang menggabungkan ajaran Stoicisme kuno dengan kehidupan modern, memberikan panduan praktis untuk menghadapi tantangan dan meraih ketenangan hidup.',
        ]);
    }
}
