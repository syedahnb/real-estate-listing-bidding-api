<?php

namespace Database\Seeders;

use App\Models\Listing;
use Illuminate\Database\Seeder;

class ListingsSeeder extends Seeder
{
    public function run()
    {
        Listing::create([
            'title' => 'Luxury Apartment in New York',
            'description' => 'A high-end apartment in the heart of NYC.',
            'base_price' => 200000,
            'location' => 'New York',
            'highest_bid' => null,
            'highest_bidder_id' => null,
            'status' => 'active',
            'expiry_date' => now()->addDays(10),
        ]);

        Listing::create([
            'title' => 'Beach House in California',
            'description' => 'A beautiful beach house with a sea view.',
            'base_price' => 500000,
            'location' => 'California',
            'highest_bid' => null,
            'highest_bidder_id' => null,
            'status' => 'active',
            'expiry_date' => now()->addDays(5),
        ]);

        Listing::create([
            'title' => 'Mountain Cabin in Colorado',
            'description' => 'A cozy cabin in the mountains.',
            'base_price' => 300000,
            'location' => 'Colorado',
            'highest_bid' => null,
            'highest_bidder_id' => null,
            'status' => 'sold', // Sold status for testing
            'expiry_date' => now()->subDays(1), // Expired listing
        ]);

        $this->command->info('Listings seeded successfully!');
    }
}
