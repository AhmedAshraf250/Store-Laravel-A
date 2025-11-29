<?php

namespace App\Facades;

use App\Repositores\Cart\CartRepoistory;
use Illuminate\Support\Facades\Facade;

class Cart extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CartRepoistory::class;
    }
}
