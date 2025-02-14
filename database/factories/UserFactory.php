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
            'name' => fake()->name(),
            'password' => static::$password ??= Hash::make('password'),
            'is_admin' => false,
            'id_store' => Store::factory(),
        ];
    }

    public function admin(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'is_admin' => true,
        ]);
    }
}
