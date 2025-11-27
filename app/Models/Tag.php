<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug'];
    public $timestamps = false;

    public function products()
    {

        // return $this->belongsToMany(
        //     Product::class,     // related Model
        //     'product_tag',  // Pivot table name
        //     'tag_id',       // FK in Pivot table for the Current Model
        //     'product_id',   // FK in Pivot table for the Related Model
        //     'id',           // PK Current Model
        //     'id'            // PK Related Model
        // );

        // Because I defined the default naming for the tables, so there is no need to define the rest of the columns and relationship details.
        return $this->belongsToMany(Product::class);
    }
}
