<?php

use App\Models\Category;
use App\Models\Product;
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

// ✅ Test récupération de toutes les catégories
test('an authenticated user can retrieve all categories', function () {
    Category::factory(5)->create();

    $response = $this->getJson('/api/categories');

    $response->assertStatus(200)
        ->assertJsonCount(5);
});

// ✅ Test récupération d'une catégorie spécifique avec ses produits
test('an authenticated user can retrieve a specific category with products', function () {
    $category = Category::factory()->create();
    Product::factory(3)->create(['ID_category' => $category->ID_category]);

    $response = $this->getJson("/api/category/{$category->ID_category}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'ID_category', 
            'Label',
            'products' => [['ID_product', 'Label']] // Vérifie la présence des produits liés
        ]);
});

// ✅ Test création d'une catégorie
test('an authenticated user can create a category', function () {
    $response = $this->postJson('/api/category', [
        'Label' => 'Nouvelle catégorie',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['ID_category', 'Label']);
});

// ✅ Test mise à jour d'une catégorie
test('an authenticated user can update a category', function () {
    $category = Category::factory()->create();

    $response = $this->putJson("/api/category/{$category->ID_category}", [
        'Label' => 'Catégorie mise à jour',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['ID_category', 'Label']);
});

// ✅ Test suppression d'une catégorie
test('an authenticated user can delete a category', function () {
    $category = Category::factory()->create();

    $response = $this->deleteJson('/api/category', [
        'ID_category' => $category->ID_category,
    ]);

    $response->assertStatus(200)
        ->assertJson(['message' => 'Catégorie supprimée avec succès']);

});


// 🛠 Tests d'erreurs

// ❌ Test format de réponse non supporté
test('unsupported response format for categories returns 406', function () {
    Category::factory()->create();
    $response = $this->get('/api/categories', [
        'Accept' => 'text/plain'
    ]);

    $response->assertStatus(406)
        ->assertSeeText('Le format demandé n\'est pas disponible', false);
});

// ❌ Test récupération d'une catégorie inexistante
test('retrieving a non-existent category returns 404', function () {
    $response = $this->getJson('/api/category/9999'); // ID inexistant

    $response->assertStatus(404)
        ->assertJson(['message' => 'Catégorie non trouvée']);
});

// ❌ Test format de réponse non supporté
test('unsupported response format for category returns 406', function () {
    $category = Category::factory()->create();
    $response = $this->get("/api/category/{$category->ID_category}", [
        'Accept' => 'text/plain'
    ]);

    $response->assertStatus(406)
        ->assertSeeText('Le format demandé n\'est pas disponible', false);
});

// ❌ Test création d'une catégorie avec des données manquantes
test('creating a category with missing data returns validation error', function () {
    $response = $this->postJson('/api/category', []);

    $response->assertStatus(500)
        ->assertJson(['message'=> 'Erreur lors de la création de la catégorie']);
});

// ❌ Test mise à jour d'une catégorie inexistante
test('updating a non-existent category returns 404', function () {
    $response = $this->putJson('/api/category/9999', [
        'Label' => 'Nouvelle mise à jour',
    ]);

    $response->assertStatus(404)
        ->assertJson(['message' => 'Catégorie non trouvée']);
});

// ❌ Test suppression d'une catégorie inexistante
test('deleting a non-existent category returns 404', function () {
    $response = $this->deleteJson('/api/category', [
        'ID_category' => 9999, // ID inexistant
    ]);

    $response->assertStatus(404)
        ->assertJson(['message' => 'Catégorie non trouvée']);
});
