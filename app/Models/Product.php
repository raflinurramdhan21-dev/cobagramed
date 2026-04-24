<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul_buku',
        'penulis_buku',
        'penerbit_buku',
        'harga_buku',
        'stok_buku',
        'deskripsi_buku',
    ];
}