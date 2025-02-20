<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactory extends Factory
{
    protected $model = Store::class;

    public function definition()
    {
        return [
            'Address' => $this->faker->streetAddress,
            'Phone' => $this->faker->numerify('0#########'), // Format français
            'Manager_name' => $this->faker->name,
            'Manager_phone' => $this->faker->numerify('0#########'), // Format français
        ];
    }
}
