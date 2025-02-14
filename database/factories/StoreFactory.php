<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Store;

class StoreFactory extends Factory
{

    protected $model = Store::class;

    public function definition(): array
    {
        return [
            'Address' => "{$this->faker->city}",
            'Phone' => "0{$this->faker->numberBetween(100000000, 999999999)}",
            'Manager_name' => $this->faker->name,
            'Manager_phone' => "0{$this->faker->numberBetween(100000000, 999999999)}",
        ];
    }
}
