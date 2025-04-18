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
            'Image' => 'data:image/png;base64,' . base64_encode($this->faker->image(null, 640, 480, null, true)),
            'Packing' => $this->faker->boolean(), // ✅ Génère true ou false
            'Barcode' => $this->faker->numerify('#############'), // Génère un code-barres 13 chiffres
            'ID_category' => Category::factory(),
        ];
    }

    public function withCategory($category): Factory
    {
        return $this->state(fn (array $attributes) => [
            'ID_category' => $category,
        ]);
    }
}
