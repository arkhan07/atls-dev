<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegionPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
        'slug',
        'title', 
        'content'
    ];

    protected $casts = [
        'region_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}