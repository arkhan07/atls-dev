<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ATLsRegistration extends Model
{
    use HasFactory;

    protected $table = 'atls_registrations';

    protected $fillable = [
        'user_id',
        'package_id',
        'full_name',
        'email',
        'phone',
        'id_number',
        'birth_date',
        'gender',
        'profession',
        'institution',
        'specialization',
        'license_number',
        'address',
        'city',
        'province',
        'notes',
        'is_certified',
        'previous_certification_date',
        'status',
        'payment_status',
        'amount_paid',
        'registered_at',
        'confirmed_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'previous_certification_date' => 'date',
        'is_certified' => 'boolean',
        'amount_paid' => 'decimal:2',
        'registered_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Terkonfirmasi',
            'cancelled' => 'Dibatalkan',
            'completed' => 'Selesai',
            default => $this->status
        };
    }

    public function getPaymentStatusLabelAttribute()
    {
        return match($this->payment_status) {
            'unpaid' => 'Belum Dibayar',
            'pending' => 'Menunggu Verifikasi',
            'paid' => 'Lunas',
            'refunded' => 'Dikembalikan',
            default => $this->payment_status
        };
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByPackage($query, $packageId)
    {
        return $query->where('package_id', $packageId);
    }
}
