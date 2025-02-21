<?php

use App\Models\Message;
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

// ✅ Test récupération de tous les messages
test('an authenticated user can retrieve all messages', function () {
    Message::factory(5)->create();

    $response = $this->getJson('/api/messages');

    $response->assertStatus(200)
        ->assertJsonCount(5);
});

// ✅ Test récupération des messages d'un magasin
test('an authenticated user can retrieve messages from a specific store', function () {
    $store = Store::factory()->create();
    Message::factory(3)->create(['ID_store' => $store->ID_store]);

    $response = $this->getJson("/api/messages/{$store->ID_store}");

    $response->assertStatus(200)
        ->assertJsonCount(3);
});

// ✅ Test récupération d'un message spécifique
test('an authenticated user can retrieve a specific message', function () {
    $store = Store::factory()->create();
    $message = Message::factory()->create(['ID_store' => $store->ID_store]);

    $response = $this->getJson("/api/message/{$store->ID_store}/{$message->ID_message}");

    $response->assertStatus(200)
        ->assertJson([
            'ID_message' => $message->ID_message,
            'Message' => $message->Message
        ]);
});

// ✅ Test création d'un message
test('an authenticated user can create a message', function () {
    $store = Store::factory()->create();

    $response = $this->postJson('/api/message', [
        'Message' => 'Nouveau message',
        'Creation_date' => now()->toDateString(),
        'Deletion_date' => now()->addDays(30)->toDateString(),
        'ID_store' => $store->ID_store,
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['ID_message', 'Message', 'Creation_date', 'Deletion_date', 'ID_store']);
});

// ✅ Test mise à jour d'un message
test('an authenticated user can update a message', function () {
    $store = Store::factory()->create();
    $message = Message::factory()->create(['ID_store' => $store->ID_store]);

    $response = $this->putJson("/api/message/{$message->ID_message}", [
        'Message' => 'Message mis à jour',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'ID_message' => $message->ID_message,
            'Message' => 'Message mis à jour'
        ]);
});

// ✅ Test suppression d'un message
test('an authenticated user can delete a message', function () {
    $message = Message::factory()->create();

    $response = $this->deleteJson("/api/message", ['ID_message' => $message->ID_message]);


    $response->assertStatus(200)
        ->assertJson(['message' => 'Message supprimé']);
});

// 🛠 **Tests d'erreurs** 🔴

// ❌ Test récupération de tous les messages quand il n'y en a aucun
test('retrieving messages when none exist returns 404', function () {
    $response = $this->getJson('/api/messages');

    $response->assertStatus(404)
        ->assertJson(['message' => 'Aucun message trouvé']);
});

// ❌ Test récupération de tous les messages avec un format non disponible
test('retrieving messages with an unsupported format returns 406', function () {

    Message::factory()->create();
    $response = $this->get('/api/messages', [
        'Accept' => 'text/html'
    ]);

    $response->assertStatus(406)
        ->assertSeeText("Le format demandé n'est pas disponible", false);
});

// ❌ Test récupération des messages d'un magasin inexistant
test('retrieving messages from a non-existent store returns 404', function () {
    $response = $this->getJson('/api/messages/9999');

    $response->assertStatus(404)
        ->assertJson(['message' => 'Message non trouvé']);
});

// ❌ Test récupération des messages d'un magasin avec un format non disponible
test('retrieving messages from a store with an unsupported format returns 406', function () {
    $store = Store::factory()->create();
    Message::factory()->create(['ID_store' => $store->ID_store]);

    $response = $this->get("/api/messages/{$store->ID_store}", [
        'Accept' => 'text/html'
    ]);

    $response->assertStatus(406)
        ->assertSeeText("Le format demandé n'est pas disponible", false);
});

// ❌ Test récupération d'un message inexistant
test('retrieving a non-existent message returns 404', function () {
    $response = $this->getJson('/api/message/9999/9999');

    $response->assertStatus(404)
        ->assertJson(['message' => 'Message non trouvé']);
});

// ❌ Test récupération d'un message avec un format non disponible
test('retrieving a message with an unsupported format returns 406', function () {
    $store = Store::factory()->create();
    $message = Message::factory()->create(['ID_store' => $store->ID_store]);

    $response = $this->get("/api/message/{$store->ID_store}/{$message->ID_message}", [
        'Accept' => 'text/html'
    ]);

    $response->assertStatus(406)
        ->assertSeeText("Le format demandé n'est pas disponible", false);
});

// ❌ Test création d'un message avec des données manquantes
test('creating a message with missing data returns validation error', function () {
    $response = $this->postJson('/api/message', []);

    $response->assertStatus(500)
        ->assertJson(["message"=> "Erreur lors de la création du message"]);
});

// ❌ Test mise à jour d'un message inexistant
test('updating a non-existent message returns 404', function () {
    $response = $this->putJson('/api/message/9999', [
        'Message' => 'Mise à jour inexistante'
    ]);

    $response->assertStatus(404)
        ->assertJson(['message' => 'Message non trouvé']);
});
