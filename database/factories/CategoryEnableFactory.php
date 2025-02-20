<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Store;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CategoryEnable>
 */
class CategoryEnableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ID_store' => Store::factory(),
            'ID_category' => Category::factory(),
            'Category_position' => $this->faker->numberBetween(1, 5),
        ];
    }
}
