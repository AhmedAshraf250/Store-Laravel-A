<?php

namespace App\Repositories\Cart;

use App\Models\Product;
use Illuminate\Support\Collection;

interface CartRepository
{
    // Repository design pattern

    // So all the Repos which represent the Cart Interface should have the same methods whether it's resource from the database or other source like session


    public function get(): Collection;

    public function add(Product $product, int $quantity = 1);

    public function update($id, int $quantity);

    public function delete($id);

    public function empty();

    public function total(): float;
}
