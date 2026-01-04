<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public function show(Request $request, Order $order)
    {
        $order->load('delivery');
        return view('front.orders.show', compact('order',));
    }
}
