<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Listing;
use App\Models\User;
use App\Notifications\OutbidNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BidController extends Controller
{

    protected $listing;
    protected $bid;

    protected $user;

    public function __construct(Listing $listing, Bid $bid, User $user)
    {
        $this->listing = $listing;
        $this->bid = $bid;
        $this->user = $user;
    }

    public function placeBid(Request $request, $listingId)
    {
        $listing = Listing::findOrFail($listingId);

        if ($listing->status !== 'active' || $listing->expiry_date <= now()) {
            return response()->json(['message' => 'Listing is not active or has expired'], 403);
        }

        $request->validate([
            'bid_amount' => 'required|numeric|min:0',
        ]);

        $currentHighestBid = $listing->highest_bid ?? $listing->base_price;

        if ($request->bid_amount <= $currentHighestBid) {
            return response()->json(['message' => 'Bid amount must be greater than the current highest bid'], 422);
        }

        // Check if the user already placed a bid with the same amount on this listing
        $existingBid = Bid::where('user_id', auth()->id())
            ->where('listing_id', $listing->id)
            ->where('bid_amount', $request->bid_amount)
            ->first();

        if ($existingBid) {
            return response()->json(['message' => 'You have already placed a bid with this amount on this listing'], 409);
        }
        DB::transaction(function () use ($listing, $request) {
            // Store the bid
            $bid = Bid::create([
                'user_id' => auth()->id(),
                'listing_id' => $listing->id,
                'bid_amount' => $request->bid_amount,
            ]);

            $previousHighestBidderId = $listing->highest_bidder_id;
            // Update the listing with the new highest bid
            $listing->update([
                'highest_bid' => $request->bid_amount,
                'highest_bidder_id' => auth()->id(),
            ]);
            // Notify previous highest bidder (if any)

            if ($previousHighestBidderId && $previousHighestBidderId !== auth()->id()) {
                $previousHighestBidder = User::find($listing->highest_bidder_id);
                $previousHighestBidder->notify(new OutbidNotification($listing, $bid));
            }
        });


        return response()->json(['message' => 'Bid placed successfully'], 201);
    }


    public function getBids($listingId, Request $request)
    {
        $listing = Listing::findOrFail($listingId);

        $query = $listing->bids()->orderBy('bid_amount', 'desc');

        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->input('start_date'))));
        }

        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->input('end_date'))));
        }


        $bids = $query->with('user:id,name')->get()->map(function ($bid) {
            return [
                'amount' => $bid->bid_amount,
                'bidder' => $bid->user->name,
                'timestamp' => $bid->created_at,
            ];
        });

        return response()->json($bids, 200);
    }


}
