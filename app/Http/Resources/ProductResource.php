<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    /**
     * Each model can have its own API Resource to customize
     * the JSON response returned by the API.
     *
     * The main purpose of a Resource is to control the output structure:
     * - Exclude unwanted fields
     * - Add or reshape data as needed
     *
     * When using a Resource, the model’s $hidden and $appends
     * properties are NOT automatically applied.
     *
     * Usage example:
     * public function show(Product $product)
     * {
     *     return new ProductResource($product);
     * }
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'price' => [
                'normal' => $this->price,
                'compare' => $this->compare_price
            ],
            'relations' => [
                'category' => [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                ],
                'store' => [
                    'id' => $this->store->id,
                    'name' => $this->store->name,
                ],
            ],
            'image' => $this->image_url
        ];
    }
}
