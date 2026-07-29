<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['property_id', 'tenant_id', 'start_date', 'end_date', 'duration_type', 'total_price', 'deposit', 'status', 'contract_id'])]
class Booking extends Model
{
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
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
