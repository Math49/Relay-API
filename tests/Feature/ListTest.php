<?php

use App\Models\ListModel;
use App\Models\Store;
use App\Models\Product;
use App\Models\ProductList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
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

// ✅ Test récupération de toutes les listes
test('an authenticated user can retrieve all lists', function () {
    $lists = ListModel::factory(5)->create();
    $products = Product::factory(3)->create();

    foreach ($lists as $list) {
        foreach ($products as $product) {
            $list->productLists()->create([ // Correction ici
                'ID_product' => $product->ID_product,
                'Quantity' => 5
            ]);
        }
    }

    $response = $this->getJson('/api/lists');
    
    $response->assertStatus(200)
        ->assertJsonCount(5);
});


// ✅ Test récupération des listes par magasin
test('an authenticated user can retrieve lists by store', function () {
    $store = Store::factory()->create();
    ListModel::factory(3)->create(['ID_store' => $store->ID_store]);

    $response = $this->getJson("/api/list/{$store->ID_store}");

    $response->assertStatus(200)
        ->assertJsonCount(3);
});

// ✅ Test récupération d'une liste spécifique avec ses produits
test('an authenticated user can retrieve a specific list with products', function () {
    $store = Store::factory()->create();
    $list = ListModel::factory()->create([
        'ID_store' => $store->ID_store
    ]);
    $products = Product::factory(3)->create();
    
    foreach ($products as $product) {
        $list->productLists()->create([
            'ID_product' => $product->ID_product,
            'Quantity' => 5
        ]);
    }

    $response = $this->getJson("/api/list/{$store->ID_store}/{$list->ID_list}");

    $response->assertJsonStructure([
        'ID_list',
        'ID_store',
        'Creation_date',
        'product_lists' => [
            [
                'ID_product',
                'ID_list',
                'Quantity',
                'product' => [
                    'ID_product',
                    'Label',
                    'Box_quantity',
                    'Image',
                    'Packing',
                    'Barcode',
                    'ID_category',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]
    ]);
});

// ✅ Test création d'une liste
test('an authenticated user can create a list', function () {
    $store = Store::factory()->create();
    $product = Product::factory()->create();

    $response = $this->postJson('/api/list', [
        'ID_store' => $store->ID_store,
        'Creation_date' => now()->toDateString(),
        'products' => [
            ['ID_product' => $product->ID_product, 'Quantity' => 10]
        ]
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['ID_list', 'ID_store', 'Creation_date']);
});

// ✅ Test mise à jour d'une liste
test('an authenticated user can update a list', function () {
    $list = ListModel::factory()->create();
    $newProduct = Product::factory()->create();


    

    $response = $this->putJson("/api/list", [
        'ID_list' => $list->ID_list,
        'products' => [
            ['ID_product' => $newProduct->ID_product, 'Quantity' => 15]
        ]
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['ID_list', 'ID_store']);
});

// ✅ Test suppression d'une liste
test('an authenticated user can delete a list', function () {
    $list = ListModel::factory()->create();
    
    $response = $this->deleteJson('/api/list', [
        'ID_list' => $list->ID_list,
        'productLists' => []
    ]);

    $response->assertStatus(200)
        ->assertJson(['message' => 'Liste supprimée']);
});

// 🛠 Tests d'erreurs

// ❌ Test récupération de toutes les listes inexistantes
test('retrieving non-existent lists returns 404', function () {
    $response = $this->getJson('/api/lists');

    $response->assertStatus(404)
        ->assertJson(['message' => 'Aucune liste trouvée']);
});

// ❌ Test format de réponse non supporté
test('unsupported response format for lists returns 406', function () {
    ListModel::factory()->create();
    $response = $this->get('/api/lists', [
        'Accept' => 'text/plain'
    ]);

    $response->assertStatus(406)
        ->assertSeeText('Le format demandé n\'est pas disponible', false);
});

// ❌ Test récupération des listes pour un magasin inexistant
test('retrieving lists for a non-existent store returns 404', function () {
    $response = $this->getJson('/api/list/9999');

    $response->assertStatus(404)
        ->assertJson(['message' => 'Liste non trouvée']);
});

// ❌ Test format de réponse non supporté
test('unsupported response format for store lists returns 406', function () {
    $store = Store::factory()->create();
    ListModel::factory()->create(['ID_store' => $store->ID_store]);
    $response = $this->get("/api/list/{$store->ID_store}", [
        'Accept' => 'text/plain'
    ]);

    $response->assertStatus(406)
        ->assertSeeText('Le format demandé n\'est pas disponible', false);
});

// ❌ Test récupération d'une liste inexistante
test('retrieving a non-existent list returns 404', function () {
    $store = Store::factory()->create();
    $response = $this->getJson("/api/list/{$store->ID_store}/9999");

    $response->assertStatus(404)
        ->assertJson(['message' => 'Liste non trouvée']);
});

// ❌ Test format de réponse non supporté
test('unsupported response format for a list returns 406', function () {
    $store = Store::factory()->create();
    $list = ListModel::factory()->create(['ID_store' => $store->ID_store]);
    $response = $this->get("/api/list/{$store->ID_store}/{$list->ID_list}", [
        'Accept' => 'text/plain'
    ]);

    $response->assertStatus(406)
        ->assertSeeText('Le format demandé n\'est pas disponible', false);
});

// ❌ Test mise à jour d'une liste inexistante
test('updating a non-existent list returns 404', function () {
    $response = $this->putJson('/api/list', [
        'ID_list' => 9999,
        'Creation_date' => now()->toDateString(),
        'products' => []
    ]);

    $response->assertStatus(404)
        ->assertJson(['message' => 'Liste non trouvée']);
});

// ❌ Test suppression d'une liste inexistante
test('deleting a non-existent list returns 404', function () {
    $response = $this->deleteJson('/api/list', [
        'ID_list' => 9999,
        'productLists' => []
    ]);

    $response->assertStatus(404)
        ->assertJson(['message' => 'Liste non trouvée']);
});
