<?php

use App\Models\User;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
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

// ✅ Test récupération de tous les utilisateurs
test('an authenticated user can retrieve all users', function () {
    $store = Store::factory()->create();
    User::factory(5)->create(['ID_store' => $store->ID_store]);

    $response = $this->getJson('/api/users');

    $response->assertStatus(200)
        ->assertJsonCount(6); // 5 créés + 1 admin authentifié
});

// ✅ Test récupération d'un utilisateur spécifique
test('an authenticated user can retrieve a specific user', function () {
    $store = Store::factory()->create();
    $user = User::factory()->create(['ID_store' => $store->ID_store]);

    $response = $this->getJson("/api/user/{$user->ID_user}");

    $response->assertStatus(200)
        ->assertJson(['Name' => $user->Name]);
});

// ✅ Test création d'un utilisateur
test('an authenticated user can create a user', function () {
    $store = Store::factory()->create();
    $response = $this->postJson('/api/user', [
        'Name' => 'New User',
        'Password' => 'password123',
        'Is_admin' => false,
        'ID_store' => $store->ID_store,
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['message', 'user']);
});

// ✅ Test mise à jour d'un utilisateur
test('an authenticated user can update a user', function () {
    $store = Store::factory()->create();
    $user = User::factory()->create(['ID_store' => $store->ID_store]);

    $response = $this->putJson("/api/user/{$user->ID_user}", [
        'Name' => 'Updated Name',
        'Password' => 'updatedpassword',
    ]);

    $response->assertStatus(200)
        ->assertJson(['message' => 'Utilisateur modifié avec succès']);
});

// ✅ Test suppression d'un utilisateur
test('an authenticated user can delete a user', function () {
    $store = Store::factory()->create();
    $user = User::factory()->create(['ID_store' => $store->ID_store]);

    $response = $this->deleteJson("/api/user", [
        'ID_user' => $user->ID_user,
    ]);

    $response->assertStatus(200)
        ->assertJson(['message' => 'Utilisateur supprimé avec succès']);
});


// 🛠 Tests d'erreurs

// ❌ Test format de réponse non supporté
test('unsupported response format for users returns 406', function () {
    $response = $this->getJson('/api/users', ['Accept' => 'application/xml']);

    $response->assertStatus(406)
        ->assertSeeText("Le format demandé n'est pas disponible", false);
});

// ❌ Test récupération d'un utilisateur inexistant
test('retrieving a non-existent user returns 404', function () {
    $response = $this->getJson('/api/user/9999'); // ID inexistant

    $response->assertStatus(404)
        ->assertJson(['message' => 'Utilisateur non trouvé']);
});

// ❌ Test format de réponse non supporté
test('unsupported response format for user returns 406', function () {
    $store = Store::factory()->create();
    $user = User::factory()->create(['ID_store' => $store->ID_store]);

    $response = $this->getJson("/api/user/{$user->ID_user}", ['Accept' => 'application/xml']);

    $response->assertStatus(406)
        ->assertSeeText("Le format demandé n'est pas disponible", false);
});

// ❌ Test mise à jour d'un utilisateur inexistant
test('updating a non-existent user returns 404', function () {
    $response = $this->putJson('/api/user/9999', [
        'Name' => 'Updated Name',
    ]);

    $response->assertStatus(404)
        ->assertJson(['message' => 'Utilisateur non trouvé']);
});

// ❌ Test suppression d'un utilisateur inexistant
test('deleting a non-existent user returns 404', function () {
    $response = $this->deleteJson('/api/user', [
        'ID_user' => 9999, // ID inexistant
    ]);

    $response->assertStatus(404)
        ->assertJson(['message' => 'Utilisateur non trouvé']);
});
