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

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }
    
    #[Test]
    public function a_user_can_register()
    {
        
        $store = Store::factory()->create();

        $response = $this->postJson('/api/register', [
            'Name' => 'John Doe',
            'Password' => 'password',
            'Is_admin' => false,
            'ID_store' => $store->ID_store,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['message', 'user', 'token']);
    }

    #[Test]
    public function a_user_can_login()
    {
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
    }

    #[Test]
    public function an_authenticated_user_can_logout()
    {
        $store = Store::factory()->create();
        $user = User::factory()->create([
            'ID_store' => $store->ID_store,
        ]);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Déconnexion réussie']);
    }
}
