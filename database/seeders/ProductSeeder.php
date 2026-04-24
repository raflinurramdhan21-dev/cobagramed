<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'judul_buku' => 'Laskar Pelangi',
                'penulis_buku' => 'Andrea Hirata',
                'penerbit_buku' => 'Bentang Pustaka',
                'harga_buku' => 85000,
                'stok_buku' => 10,
                'deskripsi_buku' => 'Novel tentang perjuangan anak-anak di Belitung dalam mengejar pendidikan.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'judul_buku' => 'Bumi',
                'penulis_buku' => 'Tere Liye',
                'penerbit_buku' => 'Gramedia Pustaka Utama',
                'harga_buku' => 95000,
                'stok_buku' => 15,
                'deskripsi_buku' => 'Novel fantasi tentang petualangan Raib di dunia paralel.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'judul_buku' => 'Atomic Habits',
                'penulis_buku' => 'James Clear',
                'penerbit_buku' => 'Penguin Random House',
                'harga_buku' => 120000,
                'stok_buku' => 20,
                'deskripsi_buku' => 'Buku pengembangan diri tentang membangun kebiasaan kecil yang berdampak besar.',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
