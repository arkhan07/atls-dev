<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'category',
        'is_featured',
        'status',
        'sort_order',
        'meta_data',
        'created_by'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'meta_data' => 'array'
    ];

    // Auto generate slug
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Accessor untuk full image URL
    public function getImageUrlAttribute()
    {
        return asset('uploads/gallery/' . $this->image);
    }

    // Categories static method
    public static function getCategories()
    {
        return [
            'hotel' => 'Hotel',
            'restaurant' => 'Restaurant', 
            'beauty' => 'Beauty',
            'car' => 'Car',
            'real-estate' => 'Real Estate',
            'doctor' => 'Doctor',
            'event' => 'Event',
            'other' => 'Other'
        ];
    }
}