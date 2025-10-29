<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'user_id',
        'name',
        'email',
        'phone',
        'amount',
        'payment_status',
        'payment_method',
        'payment_proof',
        'registration_date',
        'notes'
    ];

    protected $casts = [
        'registration_date' => 'date',
        'amount' => 'decimal:2'
    ];

    // Relationships
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-warning',
            'paid' => 'bg-success',
            'cancelled' => 'bg-danger'
        ];

        return $badges[$this->payment_status] ?? 'bg-secondary';
    }
}