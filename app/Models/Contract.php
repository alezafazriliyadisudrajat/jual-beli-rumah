<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['contract_number', 'file_path', 'signed_at'])]
class Contract extends Model
{
    protected function casts(): array
    {
        return [
            'signed_at' => 'datetime',
        ];
    }
}
