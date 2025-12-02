<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderItem extends Pivot
{
    use HasFactory;

    // Since this model extends Pivot, Laravel assumes the table name is singular.
    // But our pivot table is actually named in plural form, so we must explicitly define the table name to ensure Laravel uses the correct pivot table.

    // this table is a pivot between orders and products | so defualt name is order_product
    protected $table = 'order_items';
    public $incrementing = true;
    public $timestamps = false;

    // Relations

    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault([
            'name' => $this->product_name
        ]);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
