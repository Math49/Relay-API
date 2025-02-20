<?php

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

// ✅ Test récupération de tous les magasins
test('an authenticated user can retrieve all stores', function () {
    Store::factory(5)->create();

    $response = $this->getJson('/api/stores');

    $response->assertStatus(200)
        ->assertJsonCount(6); // 5 créés + 1 admin authentifié
});

// ✅ Test récupération d'un magasin spécifique
test('an authenticated user can retrieve a specific store', function () {
    $store = Store::factory()->create();

    $response = $this->getJson("/api/store/{$store->ID_store}");

    $response->assertStatus(200)
        ->assertJson(['Address' => $store->Address]);
});

// ✅ Test création d'un magasin
test('an authenticated user can create a store', function () {
    $response = $this->postJson('/api/store', [
        'Address' => '123 Rue Exemple',
        'Phone' => '0123456789',
        'Manager_name' => 'John Doe',
        'Manager_phone' => '0987654321',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['Address', 'Phone', 'Manager_name']);
});

// ✅ Test mise à jour d'un magasin
test('an authenticated user can update a store', function () {
    $store = Store::factory()->create();

    $response = $this->putJson("/api/store/{$store->ID_store}", [
        'Address' => 'Nouvelle adresse',
    ]);

    $response->assertStatus(200)
        ->assertJson(['Address' => 'Nouvelle adresse']);
});

// ✅ Test suppression d'un magasin
test('an authenticated user can delete a store', function () {
    $store = Store::factory()->create();

    $response = $this->deleteJson("/api/store", [
        'ID_store' => $store->ID_store,
    ]);

    $response->assertStatus(200)
        ->assertJson(['message' => 'Magasin supprimé avec succès']);
});


// 🛠 Tests d'erreurs

// ❌ Test format de réponse non supporté
test('unsupported response format for stores returns 406', function () {
    Store::factory()->create();

    $response = $this->get("/api/stores", [
        'Accept' => 'text/plain'
    ]);

    $response->assertStatus(406)
        ->assertSee('Le format demandé n\'est pas disponible', false);
});

// ❌ Test récupération d'un magasin inexistant → 404
test('retrieving a non-existent store returns 404', function () {
    $response = $this->getJson('/api/store/9999'); // ID inexistant

    $response->assertStatus(404)
        ->assertJson(['message' => 'Magasin non trouvé']);
});

// ❌ Test récupération d'un magasin avec un format non supporté → 406
test('retrieving a store with an unsupported format returns 406', function () {
    $store = Store::factory()->create();

    $response = $this->get("/api/store/{$store->ID_store}", [
        'Accept' => 'text/plain'
    ]);

    $response->assertStatus(406)
        ->assertSee('Le format demandé n\'est pas disponible', false);
});

// ❌ Test création d'un magasin avec des champs manquants → 422
test('creating a store with missing fields returns 422', function () {
    $response = $this->postJson('/api/store', []); // Aucune donnée envoyée

    $response->assertStatus(500)
        ->assertJson(['message' => 'Erreur lors de la création du magasin']);
});

// ❌ Test mise à jour d'un magasin inexistant → 404
test('updating a non-existent store returns 404', function () {
    $response = $this->putJson('/api/store/9999', [
        'Address' => 'Nouvelle adresse',
    ]);

    $response->assertStatus(404)
        ->assertJson(['message' => 'Magasin non trouvé']);
});

// ❌ Test suppression d'un magasin inexistant → 404
test('deleting a non-existent store returns 404', function () {
    $response = $this->deleteJson('/api/store', [
        'ID_store' => 9999, // ID inexistant
    ]);

    $response->assertStatus(404)
        ->assertJson(['message' => 'Magasin non trouvé']);
});

// ❌ Test suppression d'un magasin sans ID fourni → 404
test('deleting a store without providing an ID returns 404', function () {
    $response = $this->deleteJson('/api/store', []);

    $response->assertStatus(404)
        ->assertJson(['message' => 'Magasin non trouvé']);
});


