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

        $stores = Store::factory(5)
            ->has(User::factory()->admin()->count(1), 'users')
            ->has(User::factory()->count(1), 'users')
            ->create();

        $categories = Category::factory(5)->create();

        $categories->each(function ($category) use ($stores) {
            $stores->each(function ($store) use ($category) {
                CategoryEnable::factory()->create([
                    'ID_store' => $store->ID_store,
                    'ID_category' => $category->ID_category,
                ]);
            });
        });
    }
}
