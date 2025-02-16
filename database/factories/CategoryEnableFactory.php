<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
            'ID_category' => null,
            'ID_store' => null,
            'Category_position' => $this->faker->numberBetween(1, 5),
        ];
    }
}
