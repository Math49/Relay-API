<?php

namespace Database\Factories;

use App\Models\Stock;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    protected $model = Stock::class;

    public function definition()
    {
        return [
            'ID_store' => Store::factory(),
            'ID_product' => Product::factory(),
            'Nmb_boxes' => $this->faker->numberBetween(1, 50),
            'Quantity' => $this->faker->numberBetween(10, 500),
            'Nmb_on_shelves' => $this->faker->numberBetween(1, 20),
            'Is_empty' => $this->faker->boolean(),
        ];
    }
}
