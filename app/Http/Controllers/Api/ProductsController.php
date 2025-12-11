<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ProductsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('index', 'show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /**
         * ┌─────────────────────────────────────────────────────────────┐
         * │              Difference between with() and load()           │
         * └─────────────────────────────────────────────────────────────┘
         *
         * 1. with()  → Eager Loading (Use in Query Builder - BEFORE fetching)
         *     - Best performance, prevents N+1 from the start
         *     - Use it 99% of the time when you know you need the relation
         * 
         * * 2. load()  → Lazy Eager Loading (Use on already fetched Model/Collection) like:  $user->load('profile');
         *     - Use ONLY when the model(s) are already retrieved
         *     - Common cases:
         *       • Data came from cache/session
         *       • Conditional loading based on user role, request param, etc.ss
         */

        // with(['relationship:column,column']); || 'id' column must exist when retriveing specific columns from some relationship even if we dont use it
        // return Product::filter($request->query())
        //     ->with(['category:id,name', 'store:id,name', 'tags:id,name'])->paginate();

        $products = Product::filter($request->query())
            ->with(['category:id,name', 'store:id,name', 'tags:id,name'])->paginate();

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Before performing any action, we must verify that the authenticated user (via API token) has the required permission.
        // The token stores its abilities in an array like: ["product.create", "product.update", "product.delete"]
        // If the token ability contains ["*"], it means it has full access to all abilities.
        //
        // The $user here refers to the currently authenticated user who sent the token with the request. 
        // If this token does NOT have permission for 'product.create',
        // the request is rejected.
        // Using tokenCan(), we check whether the current user's token is allowed to perform the requested action.
        $user = $request->user();
        if (!$user->tokenCan('product.create')) {
            return Response::json(['message' => 'Not Allowed'], 403); // $user->tokenCan(string $ability);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:500',
            'status' => 'in:active,draft,archived',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|gt:price', // gt --> Greater Than
        ]);
        $product = Product::create($request->all());
        return $product;
        // Laravel returns 201 (Created) by default when using the `store` action.
        // For custom actions and that action make create then the status code must be set manually. ==>> return Response::json($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        // Api Resource
        return new ProductResource($product);

        // return $product->load(['category:id,name', 'store:id,name', 'tags:id,name']);
        // load()  → Lazy Eager Loading (Use on already fetched Model/Collection)
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $user = $request->user();
        if (!$user->tokenCan('product.update')) {
            return Response::json(['message' => 'Not Allowed'], 403); // $user->tokenCan(string $ability);
        }

        $validated = $request->validate([
            // 'sometimes' means the field is required only if present in the request; otherwise it’s ignored.
            'name' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
            'description' => 'nullable|string|max:500',
            'status' => 'in:active,draft,archived',
            'price' => 'sometimes|required|numeric|min:0',
            'compare_price' => 'nullable|numeric|gt:price', // gt --> Greater Than
        ]);
        $product->update($validated);
        // $product->update($request->validated()); // return only the validated data
        return Response::json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $user = Auth::guard('sanctum')->user();

        if (!$user->tokenCan('product.delete')) { // if the token ability contains ["*"] ==(with)=>> tokenCan('anything') =return=>> true always
            return Response::json(['message' => 'Not Allowed'], 403); // $user->tokenCan(string $ability);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();
        return response()->noContent();
    }
}
