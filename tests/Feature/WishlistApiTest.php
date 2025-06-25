<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create([
        'password' => bcrypt('password123')
    ]);
});

it('allows user registration', function () {
    $response = $this->postJson(route('api.auth.register'), [
        'name' => 'Test User',
        'email' => 'test@gmail.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'name', 'email'],
                    'authentication' => ['token', 'type', 'last_used_at', 'expires_at']
                ],
            ]);
});

it('allows user login', function () {
    $response = $this->postJson(route('api.auth.login'), [
        'email' => $this->user->email,
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'name', 'email'],
                    'authentication' => ['token', 'type', 'last_used_at', 'expires_at']
                ],
            ]);
});

it('prevents login with invalid credentials', function () {
    $response = $this->postJson(route('api.auth.login'), [
        'email' => $this->user->email,
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401)
            ->assertJson([
                'status' => 'failed',
                'message' => 'The provided credentials are incorrect.',
            ]);
});

it('allows authenticated user to fetch all products', function () {
    Product::factory()->count(5)->create();

    $response = $this->actingAs($this->user, 'sanctum')
                     ->getJson(route('api.products.index'));

    $response->assertOk();
            //  ->assertJsonCount(5);
});

it('allows user to add a product to wishlist', function () {
    $product = Product::factory()->create();

    $response = $this->actingAs($this->user, 'sanctum')
                     ->postJson(route('api.products.wishlist.add', $product));

    $response->assertStatus(201)
             ->assertJson(['message' => 'Product added to wishlist']);

    $this->assertDatabaseHas('wishlists', [
        'user_id' => $this->user->id,
        'product_id' => $product->id,
    ]);
});

it('allows user to remove a product from wishlist', function () {
    $product = Product::factory()->create();
    Wishlist::factory()->create([
        'user_id' => $this->user->id,
        'product_id' => $product->id,
    ]);

    $response = $this->actingAs($this->user, 'sanctum')
                     ->deleteJson(route('api.products.wishlist.remove', $product));

    $response->assertStatus(200)
             ->assertJson(['message' => 'Product removed from wishlist']);

    $this->assertDatabaseMissing('wishlists', [
        'user_id' => $this->user->id,
        'product_id' => $product->id,
    ]);
});

it('allows user to fetch their wishlist', function () {
    $products = Product::factory()->count(3)->create();

    foreach ($products as $product) {
        Wishlist::factory()->create([
            'user_id' => $this->user->id,
            'product_id' => $product->id,
        ]);
    }

    $response = $this->actingAs($this->user, 'sanctum')
                     ->getJson(route('api.products.wishlist.index'));

    $response->assertOk();
            //  ->assertJsonCount(3);
});

it('prevents user from seeing wishlist items that belong to others', function () {
    $otherUser = User::factory()->create();
    $productForOtherUser = Product::factory()->create();

    Wishlist::factory()->create([
        'user_id' => $otherUser->id,
        'product_id' => $productForOtherUser->id,
    ]);

    $response = $this->actingAs($this->user, 'sanctum')
                     ->getJson(route('api.products.wishlist.index'));

    $response->assertOk()
             ->assertJsonMissing(['id' => $productForOtherUser->id]);
});
