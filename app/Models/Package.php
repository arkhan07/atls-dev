<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'date_range',
        'days',
        'time',
        'location',
        'maps_link',
        'quota',
        'price',
        'bank_account',
        'bank_name',
        'account_name',
        'contact_name',
        'contact_phone',
        'description',
        'image',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('uploads/packages/' . $this->image) : asset('images/default-package.jpg');
    }

    public function getRegistrationCountAttribute()
    {
        return $this->registrations()->count();
    }

    public function getRemainingQuotaAttribute()
    {
        return max(0, $this->quota - $this->registration_count);
    }

    public function getRegistrationPercentageAttribute()
    {
        if ($this->quota <= 0) return 0;
        return ($this->registration_count / $this->quota) * 100;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByAgent($query, $agentId)
    {
        return $query->where('user_id', $agentId);
    }
}