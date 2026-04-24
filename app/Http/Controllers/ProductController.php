<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // view semua produk
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    // view detail produk
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
}

