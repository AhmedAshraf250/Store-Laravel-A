<?php

namespace App\Models;

use App\Concerns\HasPointLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    use HasFactory, HasPointLocation;

    protected $fillable = [
        'order_id',
        'current_location',
        'status',
    ];

    protected $hidden = ['current_location'];
    protected $appends = ['location'];

    // [Relationships] //
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // [SCOPES] //
    public function scopeNearby($query, float $lat, float $lng, int $meters = 1000)
    {
        return $query->whereRaw(
            'ST_Distance_Sphere(
            current_location,
            POINT(?, ?)
        ) <= ?',
            [$lng, $lat, $meters]
        );
    }
}
