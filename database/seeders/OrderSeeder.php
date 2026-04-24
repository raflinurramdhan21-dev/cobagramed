<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user Udin
        $user = User::where('email', 'udinpetot@gmail.com')->first();

        // Jika user tidak ditemukan, hentikan seeder
        if (!$user) {
            $this->command->error('User with email udinpetot@gmail.com not found. Please run UserSeeder first.');
            return;
        }

        // Ambil 1 produk secara acak
        $products = Product::inRandomOrder()->take(1)->get();

        // Jika produk tidak cukup, hentikan seeder
        if ($products->count() < 1) {
            $this->command->error('Not enough products to create an order. Please run ProductSeeder first.');
            return;
        }

        // Gunakan transaction untuk memastikan konsistensi data
        DB::transaction(function () use ($user, $products) {
            // 1. Buat Order terlebih dahulu dengan gross_amount sementara
            $order = Order::create([
                'user_id' => $user->id,
                'gross_amount' => 0, // Nilai sementara
                'status' => 'pending',
            ]);

            $totalGrossAmount = 0;

            // 2. Buat OrderDetail untuk setiap produk
            foreach ($products as $product) {
                $quantity = rand(1, 2); // Jumlah acak antara 1 dan 2
                $subtotal = $product->harga_buku * $quantity;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->harga_buku, // Simpan harga saat itu
                ]);

                $totalGrossAmount += $subtotal;
            }

            // 3. Update Order dengan gross_amount yang sudah dihitung
            $order->update(['gross_amount' => $totalGrossAmount]);
        });

        $this->command->info('Order seeder has been run successfully!');
    }
}
