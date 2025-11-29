<?php

namespace App\Repositores\Contracts;

use App\Models\Product;
use Illuminate\Support\Collection;

interface CartRepoistory
{
    // Repository design pattern

    // So all the Repos which represent the Cart Interface should have the same methods whether it's resource from the database or other source like session


    public function get(): Collection;

    public function add(Product $product);

    public function update(Product $product, int $quantity);

    public function delete(Product $product);

    public function empty();

    public function total();
}
