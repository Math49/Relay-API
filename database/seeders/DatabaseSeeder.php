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

        $stores = Store::factory(5)->create();

        // Création de catégories
        $categories = Category::factory(10)->create();

        // Création d'un admin et d'utilisateurs normaux
        User::factory()->admin()->withStore()->create([
            'Name' => 'Admin User',
            'Password' => bcrypt('adminpassword'),
        ]);

        User::factory(10)->withStore()->create();

        // Associer les catégories aux magasins (CategoryEnable)
        foreach ($stores as $store) {
            foreach ($categories->random(rand(3, 7)) as $category) {
                CategoryEnable::factory()->create([
                    'ID_category' => $category->id,
                    'ID_store' => $store->ID_store,
                    'Category_position' => rand(1, 100),
                ]);
            }
        }
    }
}
