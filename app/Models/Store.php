<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Store extends Model
{
    use HasFactory, Notifiable;

    // const  CREATED_AT = 'created_on_';
    // const  UPDATED_AT = 'updated_on';

    protected $connection = 'mysql';    // [Default], connection name  || look config/database.php

    protected $table = 'stores';        // default
    protected $primaryKey = 'id';       // default
    protected $keyType = 'int';         // The "type" of the primary key
    public $incrementing = true;        // default
    public $timestamps = true;          // default

    public function products()
    {
        return $this->hasMany(Product::class, 'store_id', 'id');
    }
}
