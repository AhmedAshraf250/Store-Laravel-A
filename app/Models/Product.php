<?php

namespace App\Models;

use App\Models\Scopes\ProductScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


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

    /**
     * the booted() method is used to define global scopes or automatic behaviors
     * that should run every time the model is used. This means that on any query
     * or create/update/delete operation, Laravel will trigger the logic placed here.
     * 
     */
    public static function booted()
    {
        // static::addGlobalScope('store', function (Builder $builder) {
        //     $user = Auth::user();
        //     if ($user->store_id) {
        //         $builder->where('store_id', '=', $user->store_id);
        //     }
        // });

        // pass a closure or class that implements Scope interface

        // > php artisan make:scope ProductScope // app\Models\Scopes\ProductScope.php
        static::addGlobalScope('store', new ProductScope());
    }



    // [Relations]:

    // foreach($products as $product) {
    //     $product->category->name     // OR //    $product->category()->first()->name
    //     $product->store->name        // OR //    $product->store()->first()->name
    // }
    /**
     * $product->category() => return object form the relationship 'belongsTo'
     * $product->category => return object form the model
     *   *   *   so how that Work !!
     *   In Laravel, you can call a defined relationship using its name as a property, not as a method.
     *   If we call it as a method, it returns the relationship object itself, not the result from this method
     *   Therefore, when calling this method's name as a property and there is no actual property with that name,
     *      1.Laravel steps in and uses the "magic method" in the class.
     *      2. The magic method receives this property name that doesn't exist and checks if there is a method with the same name.
     *      3. If such a method exists, Laravel checks whether this method returns a relationship.
     *      4. Then it executes this relationship and returns its result, which is ultimately the related model.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id')->withDefault(
            [
                'name' => '-',
            ]
        );
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

    public function scopeActive(Builder $builder)
    {
        $builder->where('status', '=', 'active');
    }

    // [Accessors]:
    //      Difinition -> 'CamelCase' || invoke -> 'snake_case'  {{ $Model->image_url }}
    public function getImageUrlAttribute()
    {
        // return default image
        if (!$this->image) {
            return 'https://www.incathlab.com/images/products/default_product.png';
        }
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
    }

    public function getSalePercentAttribute()
    {
        if (!$this->compare_price) {
            return 0;
        }
        return round(100 - (100 * $this->price / $this->compare_price), 1);
    }
}
