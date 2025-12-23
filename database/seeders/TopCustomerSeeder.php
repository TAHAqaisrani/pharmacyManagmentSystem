<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TopCustomerSeeder extends Seeder
{
    public function run()
    {
        // 1. Ensure Main User exists and has high spend
        $mainUser = User::where('email', 'hello@gmail.com')->first();
        if (!$mainUser) {
            $mainUser = User::create([
                'name' => 'Muhammad taha Khan',
                'email' => 'hello@gmail.com',
                'password' => Hash::make('password'), // Resetting/Setting password to known value if created new
            ]);
        }

        // Create a large completed order for main user
        Order::create([
            'user_id' => $mainUser->id,
            'order_no' => 'ORD-' . Str::random(10),
            'total_amount' => 5000.00,
            'status' => 'completed',
            'payment_status' => 'paid',
            'shipping_address' => '123 Main St'
        ]);

        // 2. Ensure Competitor User exists with lower spend
        $competitor = User::firstOrCreate(
            ['email' => 'competitor@test.com'],
            ['name' => 'Competitor User', 'password' => Hash::make('password')]
        );

        // Create a smaller completed order for competitor
        Order::create([
            'user_id' => $competitor->id,
            'order_no' => 'ORD-' . Str::random(10),
            'total_amount' => 1000.00,
            'status' => 'completed',
            'payment_status' => 'paid',
            'shipping_address' => '456 Side St'
        ]);

        $this->command->info("Data seeded!");
        $this->command->info("Winner: hello@gmail.com (Total: 5000)");
        $this->command->info("Loser: competitor@test.com (Total: 1000)");
    }
}
