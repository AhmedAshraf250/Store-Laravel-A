<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;


class CartController extends Controller
{

    public function __construct(protected CartRepository $cart) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd($cart->total());
        // dd($this->cart->get());
        return view('front.cart', ['cart' => $this->cart]);
    }

    public function show($id) {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id', 'int'],
            'quantity' => ['nullable', 'int', 'min:1'],
        ]);

        $product = Product::findOrFail($request->post('product_id'));
        $this->cart->add($product, $request->post('quantity', 1));

        // if ($request->expectsJson()) {
        //     return response()->json(['message' => 'Product added to cart']);
        // };
        // return redirect()->route('cart.index')->with('success', 'Product added to cart');

        return $request->expectsJson()
            ? response()->json(['message' => 'Product added to cart'])
            : redirect()->route('cart.index')->with('success', 'Product added to cart');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'quantity' => ['required', 'int', 'min:1'],
        ]);

        $this->cart->update($id, $request->post('quantity'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->cart->delete($id);
        return ['massage' => 'Product removed from cart'];
    }
}
