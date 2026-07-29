<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['property_id', 'name'])]
class PropertyFeature extends Model
{
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
