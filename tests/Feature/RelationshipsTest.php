<?php

use App\Models\User;
use App\Models\Store;
use App\Models\Stock;
use App\Models\Product;
use App\Models\ProductList;
use App\Models\Category;
use App\Models\CategoryEnable;
use App\Models\Message;
use App\Models\ListModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    DB::beginTransaction();
    $user = User::factory()->withStore()->create();
    Sanctum::actingAs($user);
});

afterEach(function () {
    DB::rollBack();
});

// ✅ Test relation User → Store (Un utilisateur appartient à un magasin)
test('a user belongs to a store', function () {
    $store = Store::factory()->create();
    $user = User::factory()->create(['ID_store' => $store->ID_store]);

    expect($user->store)->toBeInstanceOf(Store::class);
    expect($user->store->ID_store)->toBe($store->ID_store);
});

// ✅ Test relation Store → Stocks (Un magasin possède plusieurs stocks)
test('a store has many stocks', function () {
    $store = Store::factory()->create();
    $stocks = Stock::factory(3)->create(['ID_store' => $store->ID_store]);

    expect($store->stocks)->toHaveCount(3);
    expect($store->stocks->first())->toBeInstanceOf(Stock::class);
});

// ✅ Test relation Store → Messages (Un magasin possède plusieurs messages)
test('a store has many messages', function () {
    $store = Store::factory()->create();
    Message::factory(2)->create(['ID_store' => $store->ID_store]);

    expect($store->messages)->toHaveCount(2);
    expect($store->messages->first())->toBeInstanceOf(Message::class);
});

// ✅ Test relation Store → Lists (Un magasin possède plusieurs listes)
test('a store has many lists', function () {
    $store = Store::factory()->create();
    ListModel::factory(2)->create(['ID_store' => $store->ID_store]);

    expect($store->lists)->toHaveCount(2);
    expect($store->lists->first())->toBeInstanceOf(ListModel::class);
});

// ✅ Test relation Store → Users (Un magasin a plusieurs utilisateurs)
test('a store has many users', function () {
    $store = Store::factory()->create();
    User::factory(3)->create(['ID_store' => $store->ID_store]);

    expect($store->users)->toHaveCount(3);
    expect($store->users->first())->toBeInstanceOf(User::class);
});

// ✅ Test relation Stock → Store (Un stock appartient à un magasin)
test('a stock belongs to a store', function () {
    $store = Store::factory()->create();
    $stock = Stock::factory()->create(['ID_store' => $store->ID_store]);

    expect($stock->store)->toBeInstanceOf(Store::class);
    expect($stock->store->ID_store)->toBe($store->ID_store);
});

// ✅ Test relation Stock → Product (Un stock appartient à un produit)
test('a stock belongs to a product', function () {
    $product = Product::factory()->create();
    $stock = Stock::factory()->create(['ID_product' => $product->ID_product]);

    expect($stock->product)->toBeInstanceOf(Product::class);
    expect($stock->product->ID_product)->toBe($product->ID_product);
});

// ✅ Test relation ProductList → Product (Une liaison appartient à un produit)
test('a product_list entry belongs to a product', function () {
    $product = Product::factory()->create();
    $productList = ProductList::factory()->create(['ID_product' => $product->ID_product]);

    expect($productList->product)->toBeInstanceOf(Product::class);
    expect($productList->product->ID_product)->toBe($product->ID_product);
});

// ✅ Test relation ProductList → ListModel (Une liaison appartient à une liste)
test('a product_list entry belongs to a list', function () {
    $list = ListModel::factory()->create();
    $productList = ProductList::factory()->create(['ID_list' => $list->ID_list]);

    expect($productList->list)->toBeInstanceOf(ListModel::class);
    expect($productList->list->ID_list)->toBe($list->ID_list);
});

// ✅ Test relation Product → Category (Un produit appartient à une catégorie)
test('a product belongs to a category', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->create(['ID_category' => $category->ID_category]);

    expect($product->category)->toBeInstanceOf(Category::class);
    expect($product->category->ID_category)->toBe($category->ID_category);
});

// ✅ Test relation Product → Stocks (Un produit peut être en stock dans plusieurs magasins)
test('a product has many stocks', function () {
    $product = Product::factory()->create();
    Stock::factory(3)->create(['ID_product' => $product->ID_product]);

    expect($product->stocks)->toHaveCount(3);
    expect($product->stocks->first())->toBeInstanceOf(Stock::class);
});

// ✅ Test relation Product → Lists (Un produit peut appartenir à plusieurs listes)
test('a product belongs to many lists', function () {
    $product = Product::factory()->create();
    $lists = ListModel::factory(2)->create();

    foreach ($lists as $list) {
        ProductList::factory()->create([
            'ID_product' => $product->ID_product,
            'ID_list' => $list->ID_list,
        ]);
    }

    $this->assertCount(2, $product->lists);
});

// ✅ Test relation Message → Store (Un message appartient à un magasin)
test('a message belongs to a store', function () {
    $store = Store::factory()->create();
    $message = Message::factory()->create(['ID_store' => $store->ID_store]);

    expect($message->store)->toBeInstanceOf(Store::class);
    expect($message->store->ID_store)->toBe($store->ID_store);
});

// ✅ Test relation CategoryEnable → Store (Une activation de catégorie appartient à un magasin)
test('a category_enable belongs to a store', function () {
    $store = Store::factory()->create();
    $categoryEnable = CategoryEnable::factory()->create(['ID_store' => $store->ID_store]);

    expect($categoryEnable->store)->toBeInstanceOf(Store::class);
    expect($categoryEnable->store->ID_store)->toBe($store->ID_store);
});

// ✅ Test relation CategoryEnable → Category (Une activation de catégorie appartient à une catégorie)
test('a category_enable belongs to a category', function () {
    $category = Category::factory()->create();
    $categoryEnable = CategoryEnable::factory()->create(['ID_category' => $category->ID_category]);

    expect($categoryEnable->category)->toBeInstanceOf(Category::class);
    expect($categoryEnable->category->ID_category)->toBe($category->ID_category);
});

// ✅ Test relation Category → Products (Une catégorie a plusieurs produits)
test('a category has many products', function () {
    $category = Category::factory()->create();
    Product::factory(4)->create(['ID_category' => $category->ID_category]);

    expect($category->products)->toHaveCount(4);
    expect($category->products->first())->toBeInstanceOf(Product::class);
});

// ✅ Test relation Category → CategoryEnables (Une catégorie a plusieurs activations)
test('a category has many category enables', function () {
    $category = Category::factory()->create();
    CategoryEnable::factory(3)->create(['ID_category' => $category->ID_category]);

    expect($category->categoryEnables)->toHaveCount(3);
    expect($category->categoryEnables->first())->toBeInstanceOf(CategoryEnable::class);
});

// ✅ Test relation List → Store (Une liste appartient à un magasin)
test('a list belong to a store', function () {
    $list = ListModel::factory()->create();

    expect($list->store)->toBeInstanceOf(Store::class);
});

// ✅ Test relation List → ProductList (Une liste possède plusieurs liaisons)
test('a list has many product_list entries', function () {
    $list = ListModel::factory()->create();
    ProductList::factory(3)->create(['ID_list' => $list->ID_list]);

    expect($list->productLists)->toHaveCount(3);
    expect($list->productLists->first())->toBeInstanceOf(ProductList::class);
});
