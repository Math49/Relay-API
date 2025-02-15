<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Store;

class UserFactory extends Factory
{
    protected static ?string $password;

    protected $model = User::class;

    public function definition(): array
    {
        return [
            'Name' =>  $this->faker->name,
            'Password' => static::$password ??= Hash::make('password'),
            'Is_admin' => false,
            'ID_store' => null,
            'remember_token' => Str::random(10),
        ];
    }

    public function withStore(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'ID_store' => Store::factory()->create()->ID_store,
        ]);
    }

    public function admin(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'Is_admin' => true,
        ]);
    }
}
