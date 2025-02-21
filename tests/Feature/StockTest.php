<?php

use App\Models\Stock;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
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

// ✅ Test récupération de tous les stocks
test('an authenticated user can retrieve all stocks', function () {
    Stock::factory(5)->create();

    $response = $this->getJson('/api/stocks');

    $response->assertStatus(200)
        ->assertJsonCount(5);
});

// ✅ Test récupération d'un stock par magasin
test('an authenticated user can retrieve stocks for a specific store', function () {
    $store = Store::factory()->create();
    Stock::factory(3)->create(['ID_store' => $store->ID_store]);

    $response = $this->getJson("/api/stock/{$store->ID_store}");

    $response->assertStatus(200)
        ->assertJsonCount(3);
});

// ✅ Test récupération d'un stock spécifique par magasin et produit
test('an authenticated user can retrieve a specific stock for a store and product', function () {
    $store = Store::factory()->create();
    $product = Product::factory()->create();
    $stock = Stock::factory()->create([
        'ID_store' => $store->ID_store,
        'ID_product' => $product->ID_product
    ]);

    $response = $this->getJson("/api/stock/{$store->ID_store}/{$product->ID_product}");

    $response->assertStatus(200)
        ->assertJsonFragment([
            'ID_store' => $stock->ID_store,
            'ID_product' => $stock->ID_product
        ]);
});

// ✅ Test création d'un stock
test('an authenticated user can create a stock', function () {
    $store = Store::factory()->create();
    $product = Product::factory()->create();

    $response = $this->postJson('/api/stock', [
        'ID_store' => $store->ID_store,
        'ID_product' => $product->ID_product,
        'Quantity' => 50,
        'Nmb_boxes' => 5,
        'Nmb_on_shelves' => 10,
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['ID_store', 'ID_product', 'Quantity', 'Nmb_boxes', 'Nmb_on_shelves']);
});

// ✅ Test création de plusieurs stocks
test('an authenticated user can create multiple stocks', function () {
    $store = Store::factory()->create();
    $product1 = Product::factory()->create();
    $product2 = Product::factory()->create();

    $response = $this->postJson('/api/stocks', [
        'stocks' => [
            [
                'ID_store' => $store->ID_store,
                'ID_product' => $product1->ID_product,
                'Quantity' => 50,
                'Nmb_boxes' => 5,
                'Nmb_on_shelves' => 10,
            ],
            [
                'ID_store' => $store->ID_store,
                'ID_product' => $product2->ID_product,
                'Quantity' => 100,
                'Nmb_boxes' => 10,
                'Nmb_on_shelves' => 20,
            ]
        ]
    ]);

    $response->assertStatus(201)
        ->assertJsonCount(2);
});

// ✅ Test mise à jour d'un stock
test('an authenticated user can update a stock', function () {
    $store = Store::factory()->create();
    $product = Product::factory()->create();
    Stock::factory()->create([
        'ID_store' => $store->ID_store,
        'ID_product' => $product->ID_product,
        'Quantity' => 20,
        'Nmb_boxes' => 2,
        'Nmb_on_shelves' => 5,
    ]);

    $response = $this->putJson("/api/stock/{$store->ID_store}/{$product->ID_product}", [
        'Quantity' => 100
    ]);

    $response->assertStatus(200)
        ->assertJson(['Quantity' => 100]);
});

// ✅ Test mise à jour de plusieurs stocks
test('an authenticated user can update multiple stocks', function () {
    $store = Store::factory()->create();
    $product1 = Product::factory()->create();
    $product2 = Product::factory()->create();
    Stock::factory()->create([
        'ID_store' => $store->ID_store,
        'ID_product' => $product1->ID_product,
        'Quantity' => 20,
        'Nmb_boxes' => 2,
        'Nmb_on_shelves' => 5,
    ]);
    Stock::factory()->create([
        'ID_store' => $store->ID_store,
        'ID_product' => $product2->ID_product,
        'Quantity' => 50,
        'Nmb_boxes' => 5,
        'Nmb_on_shelves' => 10,
    ]);

    $response = $this->putJson("/api/stocks/{$store->ID_store}", [
        'stocks' => [
            [
                'ID_product' => $product1->ID_product,
                'Quantity' => 100
            ],
            [
                'ID_product' => $product2->ID_product,
                'Quantity' => 200
            ]
        ]
    ]);

    $response->assertStatus(200)
        ->assertJsonCount(2);
});

// ✅ Test suppression d'un stock
test('an authenticated user can delete a stock', function () {
    $store = Store::factory()->create();
    $product = Product::factory()->create();
    $stock = Stock::factory()->create([
        'ID_store' => $store->ID_store,
        'ID_product' => $product->ID_product
    ]);

    $response = $this->deleteJson('/api/stock', [
        'ID_store' => $stock->ID_store,
        'ID_product' => $stock->ID_product
    ]);

    $response->assertStatus(200)
        ->assertJson(['message' => 'Stock supprimé']);
});

// 🛠 Tests d'erreurs

// ❌ Test récupération de tout le stock inexistant
test('retrieving all non-existent stocks returns 404', function () {
    $response = $this->getJson('/api/stocks');

    $response->assertStatus(404)
        ->assertJson(['message' => 'Aucun stock trouvé']);
});

// ❌ Test format de réponse non supporté
test('unsupported response format for stocks returns 406', function () {
    Stock::factory()->create();
    $response = $this->get('/api/stocks', [
        'Accept' => 'text/plain'
    ]);

    $response->assertStatus(406)
        ->assertSeeText('Le format demandé n\'est pas disponible', false);
});

// ❌ Test récupération d'un stock par magasin inexistant
test('retrieving stocks for a non-existent store returns 404', function () {
    $response = $this->getJson('/api/stock/9999');

    $response->assertStatus(404)
        ->assertJson(['message' => 'Stock non trouvé']);
});

// ❌ Test format de réponse non supporté sur un stock par magasin
test('unsupported response format for stocks by store returns 406', function () {
    $store = Store::factory()->create();
    Stock::factory()->create(['ID_store' => $store->ID_store]);

    $response = $this->get("/api/stock/{$store->ID_store}", [
        'Accept' => 'text/plain'
    ]);

    $response->assertStatus(406)
        ->assertSeeText('Le format demandé n\'est pas disponible', false);
});

// ❌ Test récupération d'un stock inexistant
test('retrieving a non-existent stock returns 404', function () {
    $response = $this->getJson('/api/stock/9999/9999');

    $response->assertStatus(404)
        ->assertJson(['message' => 'Stock non trouvé']);
});

// ❌ Test format de réponse non supporté sur un stock spécifique
test('unsupported response format for a single stock returns 406', function () {
    $store = Store::factory()->create();
    $product = Product::factory()->create();
    $stock = Stock::factory()->create([
        'ID_store' => $store->ID_store,
        'ID_product' => $product->ID_product
    ]);

    $response = $this->get("/api/stock/{$store->ID_store}/{$product->ID_product}", [
        'Accept' => 'text/plain'
    ]);

    $response->assertStatus(406)
        ->assertSeeText('Le format demandé n\'est pas disponible', false);
});

// ❌ Test création d'un stock avec des données manquantes
test('creating a stock with missing data returns validation error', function () {
    $response = $this->postJson('/api/stock', []);

    $response->assertStatus(500)
        ->assertJson(['message' => 'Erreur lors de la création du stock']);
});

// ❌ Test création de plusieurs stocks avec des données manquantes
test('creating multiple stocks with missing data returns validation error', function () {
    $response = $this->postJson('/api/stocks', []);

    $response->assertStatus(500)
        ->assertJson(['message' => 'Erreur lors de la création des stocks']);
});

// ❌ Test mise à jour d'un stock inexistant
test('updating a non-existent stock returns 404', function () {
    $response = $this->putJson('/api/stock/9999/9999', [
        'Quantity' => 100
    ]);

    $response->assertStatus(404)
        ->assertJson(['message' => 'Stock non trouvé']);
});

// ❌ Test mise à jour de plusieurs stocks inexistants
test('updating multiple non-existent stocks returns 404', function () {
    $response = $this->putJson('/api/stocks/9999', [
        'stocks' => [
            [
                'ID_product' => 9999,
                'Quantity' => 100
            ],
            [
                'ID_product' => 9998,
                'Quantity' => 200
            ]
        ]
    ]);

    $response->assertStatus(404)
        ->assertJson(['message' => 'Stock non trouvé']);
});



