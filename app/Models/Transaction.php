<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['property_id', 'buyer_id', 'agreed_price', 'booking_fee', 'status', 'contract_id'])]
class Transaction extends Model
{
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }
}
