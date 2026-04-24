<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('orderDetails.product')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->quantity > $product->stok_buku) {
            return back()->with('error', 'Jumlah melebihi stok yang tersedia.');
        }

        // Ambil user yang sedang login, fallback ke user pertama untuk demo
        $user = Auth::user();
        if (!$user) {
            $user = \App\Models\User::first();
        }

        $grossAmount = $product->harga_buku * $request->quantity;

        $order = DB::transaction(function () use ($user, $product, $request, $grossAmount) {
            $order = Order::create([
                'user_id' => $user->id,
                'gross_amount' => $grossAmount,
                'status' => 'pending',
            ]);

            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->harga_buku,
            ]);

            Payment::create([
                'order_id' => $order->id,
                'amount' => $grossAmount,
                'status' => 'pending',
            ]);

            // Kurangi stok
            $product->decrement('stok_buku', $request->quantity);

            return $order;
        });

        return redirect()->route('orders.show', $order)->with('success', 'Order berhasil dibuat! Silakan lakukan pembayaran.');
    }
}
