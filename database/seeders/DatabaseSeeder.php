<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Store;
use App\Models\Category;
use App\Models\CategoryEnable;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Message;
use App\Models\ListModel;
use App\Models\ProductList;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 🔹 Création des magasins avec des utilisateurs
        $stores = Store::factory(5)
            ->has(User::factory()->admin()->count(1), 'users') // 1 admin par magasin
            ->has(User::factory()->count(2), 'users') // 2 utilisateurs normaux par magasin
            ->create();

        // 🔹 Création des catégories
        $categories = Category::factory(5)->create();

        // 🔹 Activation des catégories pour chaque magasin
        $categories->each(function ($category) use ($stores) {
            $stores->each(function ($store) use ($category) {
                CategoryEnable::factory()->create([
                    'ID_store' => $store->ID_store,
                    'ID_category' => $category->ID_category,
                ]);
            });
        });

        // 🔹 Création des produits et association à une catégorie
        $products = Product::factory(20)->create();

        // 🔹 Création des stocks dans chaque magasin
        $stores->each(function ($store) use ($products) {
            $products->each(function ($product) use ($store) {
                Stock::factory()->create([
                    'ID_store' => $store->ID_store,
                    'ID_product' => $product->ID_product,
                ]);
            });
        });

        // 🔹 Création de messages pour chaque magasin
        $stores->each(function ($store) {
            Message::factory(2)->create([
                'ID_store' => $store->ID_store,
            ]);
        });

        // 🔹 Création de listes d'achat pour chaque magasin
        $stores->each(function ($store) use ($products) {
            $list = ListModel::factory()->create([
                'ID_store' => $store->ID_store,
            ]);

            // 🔹 Ajout de produits aux listes
            $products->random(5)->each(function ($product) use ($list) {
                ProductList::factory()->create([
                    'ID_list' => $list->ID_list,
                    'ID_product' => $product->ID_product,
                ]);
            });
        });
    }
}
