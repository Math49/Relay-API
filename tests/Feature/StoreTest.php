<?php

use App\Models\Store;
use App\Models\User;
use App\Models\Stock;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Artisan;
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


test('an authenticated user can retrieve all stores', function () {
    Store::factory(5)->create();

    $response = $this->getJson('/api/stores');

    $response->assertStatus(200)
        ->assertJsonCount(6);
});

test('an authenticated user can retrieve a specific store', function () {
    $store = Store::factory()->create();
    $response = $this->getJson("/api/store/{$store->ID_store}");

    $response->assertStatus(200)
        ->assertJson(['Address' => $store->Address]);
});

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

test('an authenticated user can update a store', function () {
    $store = Store::factory()->create();

    $response = $this->putJson("/api/store/{$store->ID_store}", [
        'Address' => 'Nouvelle adresse',
    ]);

    $response->assertStatus(200)
        ->assertJson(['Address' => 'Nouvelle adresse']);
});

test('an authenticated user can delete a store', function () {
    $store = Store::factory()->create();

    $response = $this->deleteJson("/api/store", [
        'ID_store' => $store->ID_store,
    ]);

    $response->assertStatus(200)
        ->assertJson(['message' => 'Magasin supprimé avec succès']);
});
