<?php

use App\Models\Category;
use App\Models\CategoryEnable;
use App\Models\Store;
use App\Models\User;
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

// ✅ Test récupération de toutes les catégories activées
test('an authenticated user can retrieve all enabled categories', function () {
    CategoryEnable::factory(5)->create();

    $response = $this->getJson('/api/categoryEnables');

    $response->assertStatus(200)
        ->assertJsonCount(5);
});

// ✅ Test récupération des catégories activées pour un magasin spécifique
test('an authenticated user can retrieve enabled categories for a specific store', function () {
    $store = Store::factory()->create();
    CategoryEnable::factory(3)->create(['ID_store' => $store->ID_store]);

    $response = $this->getJson("/api/categoryEnable/{$store->ID_store}");

    $response->assertStatus(200)
        ->assertJsonCount(3);
});

// ✅ Test création d'une catégorie activée
test('an authenticated user can enable a category for a store', function () {
    $store = Store::factory()->create();
    $category = Category::factory()->create();

    $response = $this->postJson("/api/categoryEnable/{$store->ID_store}", [
        'ID_category' => $category->ID_category,
        'Category_position' => 1,
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['ID_category', 'ID_store', 'Category_position']);
});

// ✅ Test mise à jour d'une catégorie activée
test('an authenticated user can update an enabled category for a store', function () {
    $categoryEnable = CategoryEnable::factory()->create();

    $response = $this->putJson("/api/categoryEnable/{$categoryEnable->ID_store}/{$categoryEnable->ID_category}", [
        'Category_position' => 2,
    ]);

    $response->assertStatus(200)
        ->assertJson(['Category_position' => 2]);
});

// ✅ Test suppression d'une catégorie activée
test('an authenticated user can delete an enabled category', function () {
    $categoryEnable = CategoryEnable::factory()->create();

    $response = $this->deleteJson('/api/categoryEnable', [
        'ID_category' => $categoryEnable->ID_category,
        'ID_store' => $categoryEnable->ID_store,
    ]);

    $response->assertStatus(200)
        ->assertJson(['message' => 'Catégorie supprimée avec succès']);
});

// 🛠 Tests d'erreurs

// ❌ Test format de réponse non supporté
test('unsupported response format for category enables returns 406', function () {
    CategoryEnable::factory()->create();
    $response = $this->get('/api/categoryEnables', [
        'Accept' => 'text/plain'
    ]);

    $response->assertStatus(406)
        ->assertSeeText("Le format demandé n'est pas disponible", false);
});

// ❌ Test récupération d'une catégorie activée pour un magasin inexistant
test('retrieving enabled categories for a non-existent store returns 404', function () {
    $response = $this->getJson('/api/categoryEnable/9999'); // ID inexistant

    $response->assertStatus(404)
        ->assertJson(['message' => 'Catégorie non trouvée']);
});

// ❌ Test format de réponse non supporté
test('unsupported response format for category enable returns 406', function () {
    $store = Store::factory()->create();
    CategoryEnable::factory()->create([
        'ID_store'=> $store->ID_store
        ]);
    $response = $this->get("/api/categoryEnable/{$store->ID_store}", [
        'Accept' => 'text/plain'
    ]);

    $response->assertStatus(406)
        ->assertSeeText("Le format demandé n'est pas disponible", false);
});

// ❌ Test mise à jour d'une catégorie activée inexistante
test('updating a non-existent enabled category returns 404', function () {
    $response = $this->putJson('/api/categoryEnable/9999/9999', [
        'Category_position' => 2,
    ]);

    $response->assertStatus(404)
        ->assertJson(['message' => 'Catégorie non trouvée']);
});

// ❌ Test suppression d'une catégorie activée inexistante
test('deleting a non-existent enabled category returns 404', function () {
    $response = $this->deleteJson('/api/categoryEnable', [
        'ID_category' => 9999, // ID inexistant
        'ID_store' => 9999, // ID inexistant
    ]);

    $response->assertStatus(404)
        ->assertJson(['message' => 'Catégorie non trouvée']);
});
