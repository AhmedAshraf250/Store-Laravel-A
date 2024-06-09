<?php

namespace App\Models;

use App\Models\Scopes\ProductScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'category_id',
        'store_id',
        'price',
        'compare_price',
        'status',
    ];




    // GLOBAL SCOPE
    // هذه الميثود لارافيل بتستخدمها لكى تعمل تهيئه او إنشيليزاشن للمودل هذا يعنى بووت مع انطلاقه وتشغيله
    // زى ما فى على مستوى الابليكاشن "الآب سيرفيسس بروفيدرز" عشان نعمل بوت إستراب للأبليكشان, كمان لو بعمل بوت إستراب للمودل نفسه زى لو فيه عمليات هضيفها على المودل بشكل اساسى وتلقائى
    // لذلك يوجد به استاتيك ميثود بنعرفها وبنضيف بها الاكواد اللى عاوزينها تتنفذ مع كل مره يتم إستخدام هذا المودل
    public static function booted()
    {
        // static::addGlobalScope('store', function (Builder $builder) {
        //     $user = Auth::user();
        //     if ($user->store_id) {
        //         $builder->where('store_id', '=', $user->store_id);
        //     }
        // });
        // // بمرر له كلاس وابجيكت منه او كلوشر فانكشن عادى


        // > php artisan make:scope ProductScope
        static::addGlobalScope('store', new ProductScope());

    }





    // foreach($products as $product) {
    //     $product->category->name // OR // $product->category()->first()->name
    //     $product->store->name // OR // $product->store()->first()->name
    //
    //                   $product->category() => (return object form the relationship 'belongsTo')
    //                   $product->category => (return object form the model)
    //                        so how that Work !!
    //  فى لارافيل ممكن ننادى على العلاقة اللى تم بنائها باسمها عادى ولكن, كابروبيرتى وليس ميثود
    // لو نادينا عليها كميثود بترجع اوبجيكت بالعلاقة وليس الناتج من هذه الميثود كالمودل مثلاً
    // ولذلك عند مناداة اسم الميثود دى كابروبيرتى ومفيش اصلا اسم بروبيرتى بالاسم دا, هنا اللارافيل تتدخل والماجيك ميثود اللى فى الكلاس تعمل
    // الماجيك ميثود تبدأ تتلقى اسم البروبيرتى دى اللى مش موجوده وتبدا تشوف طب هل فى اسم ميثود على نفس الاسم؟ لو موجود طيب تبدا مثلا تشوف هل هذه الميثود بترجع ريلاشن ؟ تبدا تنفذ هذا الريلاشن وترجع الناتج تبعها وفى النهاية بترجع المودل
    // }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,     // related Model
            'product_tag',  // Pivot table name
            'product_id',   // FK in Pivot table for the Current Model
            'tag_id',       // FK in Pivot table for the Related Model
            'id',           // PK Current Model
            'id'            // PK Related Model
        );
        // In Laravel IF Making Names with Default Just short this Code In => [return $this->belongsToMany(Tag::class);] ||| Laravel will do the rest auto
    }
}
