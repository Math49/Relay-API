<?php

use App\Models\User;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    Artisan::call('migrate:fresh');
    $user = User::factory()->withStore()->create();
    Sanctum::actingAs($user);
});

test('an authenticated user can retrieve all users', function () {
    $store = Store::factory()->create();
    User::factory(5)->create(['ID_store' => $store->ID_store]);

    $response = $this->getJson('/api/users');

    $response->assertStatus(200)
        ->assertJsonCount(6); // 5 créés + 1 admin authentifié
});

test('an authenticated user can retrieve a specific user', function () {
    $store = Store::factory()->create();
    $user = User::factory()->create(['ID_store' => $store->ID_store]);

    $response = $this->getJson("/api/user/{$user->ID_user}");

    $response->assertStatus(200)
        ->assertJson(['Name' => $user->Name]);
});

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

test('an authenticated user can delete a user', function () {
    $store = Store::factory()->create();
    $user = User::factory()->create(['ID_store' => $store->ID_store]);

    $response = $this->deleteJson("/api/user", [
        'ID_user' => $user->ID_user,
    ]);

    $response->assertStatus(200)
        ->assertJson(['message' => 'Utilisateur supprimé avec succès']);
});

