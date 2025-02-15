<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\Category;
use App\Models\CategoryEnable;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Store::factory(10)
            ->has(User::factory()->admin(), 'users')
            ->has(User::factory(), 'users')
            ->create();

        Category::factory(10)->create();

        CategoryEnable::factory(10)->create([
        ]);

    }
}
