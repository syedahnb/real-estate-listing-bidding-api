<?php

namespace Database\Seeders;

use App\Models\Bid;
use App\Models\User;
use App\Models\Listing;
use Illuminate\Database\Seeder;

class BidsSeeder extends Seeder
{
    public function run()
    {
        $user1 = User::where('email', 'user1@example.com')->first();
        $user2 = User::where('email', 'user2@example.com')->first();
        $listing1 = Listing::where('title', 'Luxury Apartment in New York')->first();
        $listing2 = Listing::where('title', 'Beach House in California')->first();

        Bid::create([
            'user_id' => $user1->id,
            'listing_id' => $listing1->id,
            'bid_amount' => 210000,
            'created_at' => now()->subDays(2),
        ]);

        Bid::create([
            'user_id' => $user2->id,
            'listing_id' => $listing1->id,
            'bid_amount' => 220000,
            'created_at' => now()->subDay(),
        ]);

        Bid::create([
            'user_id' => $user1->id,
            'listing_id' => $listing2->id,
            'bid_amount' => 510000,
            'created_at' => now()->subDay(),
        ]);

        Bid::create([
            'user_id' => $user2->id,
            'listing_id' => $listing2->id,
            'bid_amount' => 530000,
            'created_at' => now(),
        ]);

        $this->command->info('Bids seeded successfully!');
    }
}
