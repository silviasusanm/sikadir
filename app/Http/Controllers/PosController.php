<?php

namespace App\Http\Controllers;

use App\Models\Product; // Sesuaikan dengan Model Produk yang kamu gunakan
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index()
    {
        // Mengambil semua data produk untuk dikirim ke view pos.index
        $products = Product::latest()->get();

        return view('pos.index', compact('products'));
    }
}