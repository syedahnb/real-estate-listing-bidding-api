<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreListingRequest;
use App\Http\Resources\ListingResource;
use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{

    public function index(Request $request)
    {
        $query = Listing::where('status', 'active');

        // Apply Filters
        if ($request->has('location')) {
            $query->where('location', $request->input('location'));
        }

        if ($request->has('min_price') && $request->has('max_price')) {
            $minPrice = (float) $request->input('min_price');
            $maxPrice = (float) $request->input('max_price');

            // Ensure min_price is less than or equal to max_price
            if ($minPrice <= $maxPrice) {
                $query->whereBetween('base_price', [$minPrice, $maxPrice]);
            }
        } else {
            // If only min_price is provided, match listings with base_price equal to min_price
            if ($request->has('min_price')) {
                $query->where('base_price', '<=',  $request->input('min_price'));
            }
            // If only max_price is provided, apply the less than or equal condition
            if ($request->has('max_price')) {
                $query->where('base_price', '>=',  $request->input('max_price'));
            }
        }

        // Apply Sorting
        if ($request->has('sort_by')) {
            $query->orderBy($request->input('sort_by'), $request->input('sort_order', 'asc'));
        }

        // Apply Pagination
        $listings = $query->paginate($request->input('per_page', 10));

        // Return Paginated Resource Collection
        return ListingResource::collection($listings);
    }

    public function create(StoreListingRequest $request)
    {

        $listing = Listing::create([
            'title' => $request->title,
            'description' => $request->description,
            'base_price' => $request->base_price,
            'location' => $request->location,
            'status' => $request->status,
            'expiry_date' => $request->expiry_date,
        ]);
        return response()->json($listing, 201);
    }

}
