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
});
afterEach(function () {
    DB::rollBack();
});


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

test('a user can login', function () {
    $store = Store::factory()->create();
    $user = User::factory()->create([
        'ID_store' => $store->ID_store,
    ]);

    $response = $this->postJson('/api/login', [
        'Name' => $user->Name,
        'Password' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['message', 'user', 'token']);
});

test('an authenticated user can logout', function () {
    $store = Store::factory()->create();
    $user = User::factory()->create([
        'ID_store' => $store->ID_store,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/logout');

    $response->assertStatus(200)
        ->assertJson(['message' => 'Déconnexion réussie']);
});
