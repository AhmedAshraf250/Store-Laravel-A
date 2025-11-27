<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {

        $products = Product::with('category')->active()
            ->latest()
            ->limit(8)
            ->get();
        return view('front.home', compact('products'));
        // return view('layouts.front', compact('products', 'title', 'slot'));
    }
}
