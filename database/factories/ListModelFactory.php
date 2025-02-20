<?php

namespace Database\Factories;

use App\Models\ListModel;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListModelFactory extends Factory
{
    protected $model = ListModel::class;

    public function definition()
    {
        return [
            'ID_store' => Store::factory(),
            'Creation_date' => $this->faker->date(),
        ];
    }
}
