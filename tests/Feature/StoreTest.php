<?php

namespace Tests\Feature;

use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;

class StoreTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');

        $user = User::factory()->withStore()->create();
        Sanctum::actingAs($user);
    }
    #[Test]
    public function an_authenticated_user_can_retrieve_all_stores()
    {
        Store::factory(5)->create();

        $response = $this->getJson('/api/stores');

        $response->assertStatus(200)
                 ->assertJsonCount(6);
    }

    #[Test]
    public function an_authenticated_user_can_retrieve_a_specific_store()
    {
        $store = Store::factory()->create();

        $response = $this->getJson("/api/store/{$store->ID_store}");

        $response->assertStatus(200)
                 ->assertJson(['Address' => $store->Address]);
    }

    #[Test]
    public function an_authenticated_user_can_create_a_store()
    {
        $response = $this->postJson('/api/store', [
            'Address' => '123 Rue Exemple',
            'Phone' => '0123456789',
            'Manager_name' => 'John Doe',
            'Manager_phone' => '0987654321',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['Address', 'Phone', 'Manager_name']);
    }

    #[Test]
    public function an_authenticated_user_can_update_a_store()
    {
        $store = Store::factory()->create();

        $response = $this->putJson("/api/store/{$store->ID_store}", [
            'Address' => 'Nouvelle adresse',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['Address' => 'Nouvelle adresse']);
    }

    #[Test]
    public function an_authenticated_user_can_delete_a_store()
    {
        $store = Store::factory()->create();

        $response = $this->deleteJson("/api/store", [
            'ID_store' => $store->ID_store,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Magasin supprimé avec succès']);
    }
}
