<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{

    protected $fillable = ['title', 'description', 'base_price', "location", "expiry_date", "highest_bid", "highest_bidder_id"];



}
