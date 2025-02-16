<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

class UserTest extends TestCase
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
    public function an_authenticated_user_can_retrieve_all_users()
    {
        $store = Store::factory()->create();
        User::factory(5)->create(['ID_store' => $store->ID_store]);
        
        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
                 ->assertJsonCount(6); // 5 créés + 1 admin authentifié
    }

    #[Test]
    public function an_authenticated_user_can_retrieve_a_specific_user()
    {
        $store = Store::factory()->create();
        $user = User::factory()->create(['ID_store' => $store->ID_store]);

        $response = $this->getJson("/api/user/{$user->ID_user}");

        $response->assertStatus(200)
                 ->assertJson(['Name' => $user->Name]);
    }

    #[Test]
    public function an_authenticated_user_can_create_a_user()
    {
        $store = Store::factory()->create();
        $response = $this->postJson('/api/user', [
            'Name' => 'New User',
            'Password' => 'password123',
            'Is_admin' => false,
            'ID_store' => $store->ID_store,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['message', 'user']);
    }
    

    #[Test]
    public function an_authenticated_user_can_update_a_user()
    {
        $store = Store::factory()->create();

        $user = User::factory()->create(['ID_store' => $store->ID_store]);

        $response = $this->putJson("/api/user/{$user->ID_user}", [
            'Name' => 'Updated Name',
            'Password' => 'updatedpassword',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Utilisateur modifié avec succès']);
    }

    #[Test]
    public function an_authenticated_user_can_delete_a_user()
    {
        $store = Store::factory()->create();

        $user = User::factory()->create(['ID_store' => $store->ID_store]);

        $response = $this->deleteJson("/api/user", [
            'ID_user' => $user->ID_user,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Utilisateur supprimé avec succès']);
    }
}
