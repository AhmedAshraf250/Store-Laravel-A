<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'user_id',
        'payment_method',
        'status',
        'payment_status'
    ];

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    // Relations

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    // ====***====
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Customer'
        ]);
    }
    // ====***====
    public function products()
    {
        return $this->belongsToMany(
            Product::class,  // The related model is Product::class because this relation returns products.
            'order_items',   // pivot table
            'order_id',      // FK in Pivot table for the Current Model
            'product_id',    // FK in Pivot table for the Related Model
            'id',            // PK Current Model
            'id'             // PK Related Model
        )
            ->using(OrderItem::class) // to use 'using' method (OrderItem::class) must exten from "Pivot"
            ->withPivot(['product_name', 'price', 'quantity', 'options']);
        // Without withPivot(), Laravel only returns the foreign keys.
        // withPivot() tells Laravel to also include these extra columns from the pivot table when loading the relationship.
        // in this relation we have 2 `quantity` Columns, one in `order_items` Table and one in `Products` Table

        /**
         * Order ↔ Product Many-to-Many Relationship via Custom Pivot 'order_items' Table and 'OrderItem' Model
         * 
         * Why ->using(OrderItem::class)?
         *     → Use custom pivot model ["OrderItem"] instead of default Illuminate\Database\Eloquent\Relations\Pivot
         *      [RESULT]:
                    #relations: array:1 [▼
                        "pivot" => App\ModelOrderItem {#1095 ▼  // instead of "pivot" => Illuminate\Database\Eloquent\Relations\Pivot
                            +attributes: array:5 [▼
                            "id" => 1 
                            "order_id" => 1
                            "product_id" => 1
                            "quantity" => 1
                            "created_at" => null
                            "updated_at" => null
                            ]
                            ...
                        }
                    ]
         * 
         *     → Enables access to pivot-specific methods and attributes
         * - Custom pivot model allows:
         *     → Accessors/mutators (e.g., formatted price)
         *     → Events (created, updating)
         *     → Relationships on pivot (e.g., $pivot->variation)
         * - Default pivot model: 
         *     → No access to pivot-specific methods 
         *
         * Why withPivot()?
         * - Brings order-specific data into the relationship
         * - Eager-load extra columns from order_items table
         * - Essential for:
         *     → Displaying order details
         *     → Calculating totals
         *     → Processing returns/refunds
         * 
         * Result:
         * $order->products → Collection of Product models
         * Each pivot contains: product_name, price, quantity, options
            foreach ($order->products as $product) {
                echo $product->name;
                echo $product->pivot->quantity;        // from order_items
                echo $product->pivot->price;           // price at time of purchase
                echo $product->pivot->options;         // JSON or serialized extras
                echo $product->pivot->product_name;    // snapshot name
            }
         *  
         */
    }
    // ====***====
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    // ====***====
    public function addresses()
    {
        return $this->hasMany(OrderAdress::class);
    }
    // ====***====
    public function billingAdresse()
    {
        return $this->hasOne(OrderAdress::class)->where('type', 'billing'); // returns Model
        // return $this->addresses()->where('type', '=', 'billing');        // returns Collection
    }
    public function shippingAdresse()
    {
        return $this->hasOne(OrderAdress::class)->where('type', 'shipping');
    }
    // ====***====
    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }
    // ====***====

    protected static function booted()
    {
        static::creating(function (Order $order) {
            // $order->number = 'ORD-' . now()->format('YmdHis') . '-' . $order->id; // ORD-20220131091010-1
            $order->number = static::getNextOrderNumber();
        });
    }

    public static function getNextOrderNumber()
    {
        $year = Carbon::now()->year;

        // SELECT MAX(number) FROM orders
        $number = Order::whereYear('created_at', $year)->max('number');
        if ($number) {
            return $number + 1;
        }
        return $year . '0000001';
    }

    public function total()
    {
        $this->loadMissing('items.product');
        return $this->items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }
}
