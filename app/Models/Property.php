<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'user_id', 'category_id', 'location_id', 'title', 'description', 'listing_type',
    'price', 'land_area', 'building_area', 'bedrooms', 'bathrooms',
    'certificate_type', 'latitude', 'longitude', 'status', 'is_promoted',
    'condition', 'facing', 'floors_count', 'floor_location', 'interior_type',
    'maid_bedrooms', 'garages_count', 'carports_count', 'telephone_lines',
    'electricity', 'has_pam_water', 'has_ground_water', 'road_access'
])]
class Property extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function features()
    {
        return $this->hasMany(PropertyFeature::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function getPrimaryImageAttribute()
    {
        $primary = $this->images()->where('is_primary', true)->first();
        return $primary ? $primary->image_path : ($this->images()->first() ? $this->images()->first()->image_path : 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=800&q=80');
    }

    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }
}
