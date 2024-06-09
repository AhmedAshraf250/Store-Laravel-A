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
        // فى حالة انى لو اردت ايقاف الجلوبال اسكوب الذى تم إنشائه لهذا المودل فى بعض الحالات
        // $products = Product::withoutGlobalScope('store')->paginate();
        $products = Product::with(['category', 'store'])->paginate(); // Eager loading => 3 sql queries to preload data, for avoid sql queries in loops
        // الويز ميثود دى باستخدمها عشان اطلب من اللارافيل انها تعمل تحميل مسبق للريلاشنز التابعه لهذا المودل الذى استعلم عنه وذلك لتقليل جمل الاستعلام فى الفيو مثلاً

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
    public function show($id)
    {

    }

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
        $product->update($request->except('tags'));

        // dd($request->post('tags'));
        $tags = json_decode($request->post('tags'));
        $tag_ids = [];

        $saved_tags = Tag::all(); // return collection object // وهى عبارة عن تو ممكن نقول انها اراى فيها مجموعة من البيانات

        foreach ($tags as $item) {
            $slug = Str::slug($item->value);
            //الجملة اللى تحت دى مش جملة استعلام من الداتابيس, بل بحث داخل الكوليكشن اوبجيكت اللى رجعناه فوق , وهكذا لن يتم فى كل لفه عمل كويرى واستعلام داخل الداتا بيس وهذا هو الصحيح والمنطقى
            $tag = $saved_tags->where('slug', $slug)->first();
            if (!$tag) {
                $tag = Tag::create([
                    'name' => $item->value,
                    'slug' => $slug,
                ]);
            }
            $tag_ids[] = $tag->id;
        }
        // ميثود "سينك" خاصة فقط بعلاقات مانى تو مانى,هتروح تفحص الجدول الوسيط على مستوى هذا البرودكت, هل الآيديز المبعوته فى الاراى موجوده فى الجدول الوسيط, بمعنى هل برودكت 1 وتاج 1 موجودين.. إذا موجودين خلاص مش هتعمل حاجه .. إذا مش موجودين هتضيفهم
        // طب اذا عندى مثلا فى الجدول الوسيط برودكت 1 مع تاج 2 بس انا فى الاراى اللى بعتها مفيش فيها تاج 2 هنا السينك هتحذفه من الجدول
        // السينك اللى انا ببعته بتخزنه , يعنى اللى مش موجود فى الاراى اللى بعتهالها وفى نفس الوقت موجود قديما مثلا فى الجدول فستقوم بحذفه وتحديث الجدول على حسب الاراى المعطاه لها وبطبيعه الحال اللى مش موجود فى الجدول وموجود فى الاراى هتضيفه
        $product->tags()->sync($tag_ids);

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
