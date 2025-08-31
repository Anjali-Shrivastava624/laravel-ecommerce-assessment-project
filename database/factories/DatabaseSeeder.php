<?php

// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
        ]);

        User::factory()->customer()->create([
            'name' => 'Customer User',
            'email' => 'customer@test.com',
        ]);

        Product::factory(50)->create();
    }
}
