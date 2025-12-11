<?php

namespace App\Models;

use App\Models\Scopes\ProductScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    // Columns considered sensitive; Laravel will hide them in JSON responses
    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    // Appended attributes are mainly used for API / JSON responses.
    // They ensure that accessor-generated properties appear in the output, when the model is converted to JSON.
    // they can still be accessed as normal properties internally.
    protected $appends = [
        // If an accessor is listed here but not defined in the model, an error will occur.
        // When the model is converted to JSON, each appended accessor will appear as a key
        // with its value coming from the accessor's return value.
        'image_url'
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
        static::creating(
            fn(Product $product) => $product->slug = Str::slug($product->name)
        );
    }



    // =================== [Relations] =================== //
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

    // =================== [Accessors] =================== //
    //      Difinition -> 'CamelCase' || invoke -> 'snake_case'  {{ $Model->image_url }}

    // public function getImageUrlAttribute()
    // {
    //     // return default image
    //     if (!$this->image) {
    //         return 'https://www.incathlab.com/images/products/default_product.png';
    //     }
    //     if (Str::startsWith($this->image, ['http://', 'https://'])) {
    //         return $this->image;
    //     }
    //     return asset('storage/' . $this->image);
    // }
    // [OR]:
    // protected function imageUrl(): Attribute
    // {
    //     return Attribute::make(
    //         get: function () {
    //             if (!$this->image) {
    //                 return 'https://www.incathlab.com/images/products/default_product.png';
    //             }
    //             if (Str::startsWith($this->image, ['http://', 'https://'])) {
    //                 return $this->image;
    //             }
    //             return asset('storage/' . $this->image);
    //         }
    //     );
    // }
    // [OR]:
    // protected function image(): Attribute
    // {
    //     return Attribute::make(
    //         get: function ($value) { // $value = $this->image || $value = the name of the method in that case it will be 'image'
    //             if (!$value) {
    //                 return 'https://www.incathlab.com/images/products/default_product.png';
    //             }
    //             if (Str::startsWith($value, ['http://', 'https://'])) {
    //                 return $value;
    //             }
    //             return asset('storage/' . $value);
    //         }
    //     );
    // }
    // [OR]:
    protected function imageUrl(): Attribute // $product->image_url || $product->imageUrl
    {
        return Attribute::make(
            get: fn() => $this->image
                ? (Str::startsWith($this->image, ['http://', 'https://'])
                    ? $this->image
                    : asset('storage/' . $this->image))
                : 'https://www.incathlab.com/images/products/default_product.png'
        );
    }

    public function getSalePercentAttribute()
    {
        if (!$this->compare_price) {
            return 0;
        }
        return round(100 - (100 * $this->price / $this->compare_price), 1);
    }

    // =================== [Scopes] =================== //
    public function scopeActive(Builder $builder)
    {
        $builder->where('status', '=', 'active');
    }

    public function scopeFilter(Builder $builder, array $filters)
    {
        // 
        $options = array_merge([
            'category_id' => null,
            'store_id' => null,
            'tag_id' => null,
            'status' => 'active',
        ], $filters);

        $builder->when($options['category_id'] ?? false, function ($builder, $category_id) {
            $builder->where('category_id', $category_id);
        });

        $builder->when($options['store_id'] ?? false, function ($builder, $store_id) {
            $builder->where('store_id', $store_id);
        });

        $builder->when($options['tag_id'] ?? false, function ($builder, $tag_id) {

            // $builder->whereHas('tags', function ($query) use ($tag_id) {
            //     $query->where('id', $tag_id);
            // });

            // $builder->selectRaw('id IN (SELECT product_id FROM product_tag WHERE tag_id = ?)', [$tag_id]);

            // $builder->whereRaw('EXISTS (SELECT 1 FROM product_tag WHERE product_id = products.id AND tag_id = ?)', [$tag_id]); // much faster
            $builder->whereExists(function ($query) use ($tag_id) {
                $query->select(1)
                    ->from('product_tag')
                    ->whereRaw('product_id = products.id')
                    ->where('tag_id', $tag_id);
            });
        });

        $builder->when($options['status'] ?? false, function ($builder, $status) {
            $builder->where('status', $status);
        });
    }
}
