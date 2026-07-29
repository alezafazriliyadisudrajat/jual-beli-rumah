<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['payable_type', 'payable_id', 'amount', 'method', 'gateway_reference', 'status', 'paid_at'])]
class Payment extends Model
{
    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
        ];
    }

    public function payable()
    {
        return $this->morphTo();
    }
}
