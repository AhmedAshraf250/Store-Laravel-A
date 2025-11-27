<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // in Case if I want to stop global scope of this model => withoutGlobalScope('scopeName') LIKE: 
        // $products = Product::withoutGlobalScope('store')->paginate();

        $products = Product::with(['category', 'store'])->paginate(); // Eager loading => 3 sql queries to preload data, for avoid sql queries in loops
        // with() method used to eager load relationships.

        return view('dashboard.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $tags = implode(',', $product->tags()->pluck('name')->toArray());
        return view('dashboard.products.edit', compact('product', 'tags'));
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
        //  TODO: Validate

        $product->update($request->except('tags'));

        //  -- -- -- -- -- Legacy Code -- -- -- -- --
        // $tags = json_decode($request->post('tags'));
        // $tag_ids = [];

        // $saved_tags = Tag::all(); // return collection object

        // foreach ($tags as $item) {
        //     $slug = Str::slug($item->value);
        //     // where() here not query statement from database, but search in collection object, which we get from above, and this is the right way
        //     $tag = $saved_tags->where('slug', $slug)->first();
        //     if (!$tag) {
        //         $tag = Tag::create([
        //             'name' => $item->value,
        //             'slug' => $slug,
        //         ]);
        //     }
        //     $tag_ids[] = $tag->id;
        // }
        // $product->tags()->sync($tag_ids);


        $tagNames = json_decode($request->post('tags')); // return array
        $tagNames = array_filter(array_map(fn($item) => $item->value, $tagNames));
        $slugs = array_map([Str::class, 'slug'], $tagNames); // make array of slugs of tags from request
        $existingTags = Tag::whereIn('slug', $slugs)->pluck('id', 'slug')->toArray(); // saved tags from database // return like [slug => id]
        $tagIdsToSync = [];
        foreach ($tagNames as $name) {
            $slug = Str::slug($name);
            if (isset($existingTags[$slug])) {
                $tagIdsToSync[] = $existingTags[$slug];
            } else {
                $tag = Tag::create([
                    'name' => $name,
                    'slug' => $slug,
                ]);
                $tagIdsToSync[] = $tag->id;
            }
        }

        // ميثود "سينك" خاصة فقط بعلاقات مانى تو مانى,هتروح تفحص الجدول الوسيط على مستوى هذا البرودكت, هل الآيديز المبعوته فى الاراى موجوده فى الجدول الوسيط, بمعنى هل برودكت 1 وتاج 1 موجودين.. إذا موجودين خلاص مش هتعمل حاجه .. إذا مش موجودين هتضيفهم
        // طب اذا عندى مثلا فى الجدول الوسيط برودكت 1 مع تاج 2 بس انا فى الاراى اللى بعتها مفيش فيها تاج 2 هنا السينك هتحذفه من الجدول
        // السينك اللى انا ببعته بتخزنه , يعنى اللى مش موجود فى الاراى اللى بعتهالها وفى نفس الوقت موجود قديما مثلا فى الجدول فستقوم بحذفه وتحديث الجدول على حسب الاراى المعطاه لها وبطبيعه الحال اللى مش موجود فى الجدول وموجود فى الاراى هتضيفه
        $product->tags()->sync($tagIdsToSync);
        // [SUMMARY]:
        // sync() keeps the pivot table exactly matching the IDs you provide.
        // Anything in the pivot table that is NOT in the given array will be removed.
        // Anything in the array that is NOT already in the pivot table will be added.

        return redirect()->route('dashboard.products.index')->with('success', 'Product updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
