<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition()
    {
        return [
            'Message' => $this->faker->sentence(),
            'Creation_date' => $this->faker->date(),
            'Deletion_date' => $this->faker->date(),
            'ID_store' => Store::factory(),
        ];
    }
}
