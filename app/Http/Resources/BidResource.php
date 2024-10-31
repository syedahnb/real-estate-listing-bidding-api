<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BidResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'listing' => [
                'title' => $this->listing->title,
                'highest_bid' => $this->listing->highest_bid,
            ],
            'bid_amount' => $this->bid_amount,
            'status' => $this->listing->highest_bid === $this->bid_amount ? 'winning' : 'outbid',
            'timestamp' => $this->created_at,
        ];
    }
}
