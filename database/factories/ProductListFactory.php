<?php

namespace Database\Factories;

use App\Models\ProductList;
use App\Models\Product;
use App\Models\ListModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductListFactory extends Factory
{
    protected $model = ProductList::class;

    public function definition()
    {
        return [
            'ID_product' => Product::factory(),
            'ID_list' => ListModel::factory(),
            'Quantity' => $this->faker->numberBetween(1, 100),
        ];
    }
}
