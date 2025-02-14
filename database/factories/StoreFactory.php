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
            'Address' => $this->faker->address,
            'Phone' => $this->faker->phoneNumber,
            'Manager_name' => $this->faker->name,
            'Manager_phone' => $this->faker->phoneNumber
        ];
    }
}
