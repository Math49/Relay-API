<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'Label' => $this->faker->word,
            'Box_quantity' => $this->faker->numberBetween(1, 100),
            'Image' => $this->faker->imageUrl(),
            'Packing' => $this->faker->boolean(), // ✅ Génère true ou false
            'Barcode' => $this->faker->numerify('#############'), // Génère un code-barres 13 chiffres
            'ID_category' => Category::factory(),
        ];
    }
}
