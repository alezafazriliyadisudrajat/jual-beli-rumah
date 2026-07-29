<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['property_id', 'image_path', 'is_primary'])]
class PropertyImage extends Model
{
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
