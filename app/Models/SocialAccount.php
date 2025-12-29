<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SocialAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'provider_user_id',
        'token',
        'refresh_token',
        'expires_at',
    ];

    protected $casts = [
        'token' => 'crypted',
        'expires_at' => 'datetime',
    ];

    protected function token(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Crypt::decrypt($value),
            set: fn($value) => Crypt::encrypt($value),
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Anonymous'
        ]);
    }
}
