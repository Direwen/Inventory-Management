<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\InventoryCollaborator;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user1 = User::factory()->create([
            'name' => 'Bot1',
            'email' => 'bot1@gmail.com',
            'password' => "asdasd123",
            'email_verified_at' => now()
        ]);

        $user2 = User::factory()->create([
            'email' => 'bot2@gmail.com',
            'password' => "asdasd123",
            'email_verified_at' => now()
        ]);
        $user3 = User::factory()->create([
            'email' => 'bot3@gmail.com',
            'password' => "asdasd123",
            'email_verified_at' => now()
        ]);
        $user4 = User::factory()->create([
            'email' => 'bot4@gmail.com',
            'password' => "asdasd123",
            'email_verified_at' => now()
        ]);
        $user5 = User::factory()->create([
            'email' => 'bot5@gmail.com',
            'password' => "asdasd123",
            'email_verified_at' => now()
        ]);
        $user6 = User::factory()->create([
            'email' => 'bot6@gmail.com',
            'password' => "asdasd123",
            'email_verified_at' => now()
        ]);

        $inventory = Inventory::createQuietly([
            "name" => "fake inventory",
            "description" => "womp womp"
        ]);

        $inventory->collaborators()->createQuietly([
            "user_id" => $user1->id,
            "role" => "admin"
        ]);

        $inventory->collaborators()->createQuietly([
            "user_id" => $user2->id,
            "role" => "manager"
        ]);
        $inventory->collaborators()->createQuietly([
            "user_id" => $user3->id,
            "role" => "employee"
        ]);
        $inventory->collaborators()->createQuietly([
            "user_id" => $user4->id,
            "role" => "employee"
        ]);
        $inventory->collaborators()->createQuietly([
            "user_id" => $user5->id,
            "role" => "employee"
        ]);

        $products = [];

        for ($i = 1; $i <= 20; $i++) {
            $products[] = [
                "name" => "Product $i",
                "sku" => "SKU" . str_pad($i, 3, '0', STR_PAD_LEFT),
                "initial_qty" => rand(10, 50) // Random initial quantity between 10 and 50
            ];
        }

        foreach ($products as $details) {
            DB::transaction(function () use ($details, $inventory) {
                $product = Product::create([
                    'name' => $details["name"],
                    'sku' => $details["sku"],
                    'inventory_id' => $inventory->id
                ]);

                Stock::create([
                    'product_id' => $product->id,
                    'current_stock' => $details["initial_qty"] ?? 0
                ]);
            });
        }



    }
}
