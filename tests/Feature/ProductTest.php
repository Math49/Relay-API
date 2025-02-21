<?php

use App\Models\Product;
use App\Models\Category;
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

// ✅ Test récupération de tous les produits
test('an authenticated user can retrieve all products', function () {
    Product::factory(5)->create();

    $response = $this->getJson('/api/products');

    $response->assertStatus(200)
        ->assertJsonCount(5);
});

// ✅ Test récupération d'un produit spécifique
test('an authenticated user can retrieve a specific product', function () {
    $product = Product::factory()->create();

    $response = $this->getJson("/api/product/{$product->ID_product}");

    $response->assertStatus(200)
        ->assertJson([
            'ID_product' => $product->ID_product,
            'Label' => $product->Label
        ]);
});

// ✅ Test création d'un produit
test('an authenticated user can create a product', function () {
    $category = Category::factory()->create();

    $response = $this->postJson('/api/product', [
        'Label' => 'Produit Test',
        'Box_quantity' => 10,
        'Image' => 'https://via.placeholder.com/150',
        'Packing' => true,
        'Barcode' => '1234567890123',
        'ID_category' => $category->ID_category,
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['ID_product', 'Label']);
});

// ✅ Test mise à jour d'un produit
test('an authenticated user can update a product', function () {
    $product = Product::factory()->create();

    $response = $this->putJson("/api/product/{$product->ID_product}", [
        'Label' => 'Produit Mis à Jour',
    ]);

    $response->assertStatus(200)
        ->assertJson(['Label' => 'Produit Mis à Jour']);
});

// ✅ Test suppression d'un produit
test('an authenticated user can delete a product', function () {
    $product = Product::factory()->create();

    $response = $this->deleteJson('/api/product', [
        'ID_product' => $product->ID_product,
    ]);

    $response->assertStatus(200)
        ->assertJson(['message' => 'Produit supprimé']);
});

// 🛠 Tests d'erreurs

// ❌ Test récupération d'un produit inexistant
test('retrieving a non-existent product returns 404', function () {
    $response = $this->getJson('/api/product/9999');

    $response->assertStatus(404)
        ->assertJson(['message' => 'Produit non trouvé']);
});

// ❌ Test création d'un produit avec des données manquantes
test('creating a product with missing data returns validation error', function () {
    $response = $this->postJson('/api/product', []);

    $response->assertStatus(500)
        ->assertJson(['message' => 'Erreur lors de la création du produit']);
});

// ❌ Test mise à jour d'un produit inexistant
test('updating a non-existent product returns 404', function () {
    $response = $this->putJson('/api/product/9999', [
        'Label' => 'Produit Inexistant',
    ]);

    $response->assertStatus(404)
        ->assertJson(['message' => 'Produit non trouvé']);
});

// ❌ Test suppression d'un produit inexistant
test('deleting a non-existent product returns 404', function () {
    $response = $this->deleteJson('/api/product', [
        'ID_product' => 9999,
    ]);

    $response->assertStatus(404)
        ->assertJson(['message' => 'Produit non trouvé']);
});

// ❌ Test format de réponse non supporté
test('unsupported response format for products returns 406', function () {
    Product::factory()->create();
    $response = $this->get('/api/products', [
        'Accept' => 'text/plain'
    ]);

    $response->assertStatus(406)
        ->assertSeeText('Le format demandé n\'est pas disponible', false);
});

// ❌ Test format de réponse non supporté sur un produit spécifique
test('unsupported response format for a single product returns 406', function () {
    $product = Product::factory()->create();
    $response = $this->get("/api/product/{$product->ID_product}", [
        'Accept' => 'text/plain'
    ]);

    $response->assertStatus(406)
        ->assertSeeText('Le format demandé n\'est pas disponible', false);
});
