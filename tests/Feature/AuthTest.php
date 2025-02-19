<?php

use App\Models\User;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

beforeEach(function () {
    DB::beginTransaction();
});

afterEach(function () {
    DB::rollBack();
});

// ✅ Test de l'inscription réussie
test('a user can register', function () {
    $store = Store::factory()->create();

    $response = $this->postJson('/api/register', [
        'Name' => 'John Doe',
        'Password' => 'password',
        'Is_admin' => false,
        'ID_store' => $store->ID_store,
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['message', 'user', 'token']);
});

// ❌ Test de l'inscription échouée (store inexistant)
test('registration fails when required fields are missing', function () {
    $response = $this->postJson('/api/register', [
        'Name' => 'John Doe',
        'Password' => 'password',
    ]);

    $response->assertStatus(500)
        ->assertJson(['message' => 'Erreur lors de la création de l\'utilisateur']);
});

// ✅ Test de la connexion réussie
test('a user can login', function () {
    $store = Store::factory()->create();
    $user = User::factory()->create([
        'ID_store' => $store->ID_store,
        'Password' => Hash::make('password'), // Hasher le mot de passe
    ]);

    $response = $this->postJson('/api/login', [
        'Name' => $user->Name,
        'Password' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['message', 'user', 'token']);
});

// ❌ Test de connexion échouée (mot de passe incorrect)
test('login fails with incorrect password', function () {
    $store = Store::factory()->create();
    $user = User::factory()->create([
        'ID_store' => $store->ID_store,
        'Password' => Hash::make('password'),
    ]);

    $response = $this->postJson('/api/login', [
        'Name' => $user->Name,
        'Password' => 'wrongpassword',
    ]);

    $response->assertStatus(500)
        ->assertJson(['message' => 'Erreur lors de la connexion']);
});

// ✅ Test de déconnexion réussie
test('an authenticated user can logout', function () {
    $store = Store::factory()->create();
    $user = User::factory()->create([
        'ID_store' => $store->ID_store,
        'Password' => Hash::make('password'),
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/logout');

    $response->assertStatus(200)
        ->assertJson(['message' => 'Déconnexion réussie']);
});

// ❌ Test de déconnexion quand il n'y a pas de token
test('logout fails when there is no token', function () {
    
    $response = $this->postJson('/api/logout');

    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});
