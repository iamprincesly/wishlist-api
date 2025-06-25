<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Sylvanus Etim',
            'email' => 'iamprincesly@gmail.com',
            'password' => 'password'
        ]);

        // User::factory()->count(10)->create();

        // Product::factory()->count(100)->create();

        Wishlist::factory()->count(100)->create();
    }
}
