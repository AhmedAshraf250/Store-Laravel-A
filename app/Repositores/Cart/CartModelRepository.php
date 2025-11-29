<?php

namespace App\Repositores\Cart;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;


class CartModelRepository implements CartRepoistory
{
    protected $items;

    public function __construct()
    {
        $this->items = collect([]);
    }

    public function get(): Collection
    {
        if (!$this->items->count()) {
            return Cart::with('product')->get();
        }
        // only one query statement in the request
        return $this->items;
    }

    public function add(Product $product, int $quantity = 1)
    {
        $item = Cart::where('product_id', $product->id)->first();
        if (!$item) {
            $cart = Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
            $this->get()->push($cart); // add to collection to make it sync 
            return $cart;
        }
        return $item->increment('quantity', $quantity);
    }

    public function update($id, int $quantity)
    {
        return Cart::where('id', $id)
            ->update([
                'quantity' => $quantity,
            ]);
    }

    public function delete($id)
    {
        return Cart::where('id', $id)->delete();
    }

    public function empty()
    {
        return Cart::query()->destroy();
    }


    public function total(): float
    {
        // return (float) Cart::join('products', 'carts.product_id', '=', 'products.id')
        //     ->selectRaw('SUM(products.price * carts.quantity) as total')
        //     ->value('total');

        return (float) $this->get()->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        /**
         * `get()` returns a Collection of items, not a plain array.
         * Collections provide many helpful methods that simplify the code and avoid writing multiple foreach loops.
         * The `sum()` method iterates internally over each item and passes it to the closure, where we define the logic needed (price * quantity).
         * This keeps the code clean, expressive, and reduces the number of queries.
         * Here I'm running only one database query for this request. when we called {{ $cart->total() }} and {{ $cart->get() }} in the view
         */
    }
}
