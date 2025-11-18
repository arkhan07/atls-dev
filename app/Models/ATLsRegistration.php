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
        'title',
        'full_name',
        'nickname',
        'email',
        'phone',
        'place_of_birth',
        'birth_date',
        'age',
        'gender',
        'religion',
        'id_number',
        'nik',
        'plataran_sehat_name',
        'shirt_size',
        'profession',
        'institution',
        'specialization',
        'license_number',
        'certificate_file',
        'address',
        'city',
        'province',
        'shipping_address',
        'shipping_city',
        'shipping_province',
        'shipping_postal_code',
        'notes',
        'is_certified',
        'previous_certification_date',
        'agreed_to_terms',
        'status',
        'payment_status',
        'amount_paid',
        'payment_proof',
        'payment_proof_uploaded_at',
        'registered_at',
        'confirmed_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'previous_certification_date' => 'date',
        'is_certified' => 'boolean',
        'agreed_to_terms' => 'boolean',
        'amount_paid' => 'decimal:2',
        'payment_proof_uploaded_at' => 'datetime',
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

    // Accessor for certificate file URL
    public function getCertificateFileUrlAttribute()
    {
        if ($this->certificate_file) {
            // Return uploads URL (files stored in public/uploads/)
            return asset('uploads/' . $this->certificate_file);
        }
        return null;
    }

    // Calculate age from birth_date
    public function getCalculatedAgeAttribute()
    {
        if ($this->birth_date) {
            return $this->birth_date->age;
        }
        return $this->age;
    }

    // Accessor for payment proof URL
    public function getPaymentProofUrlAttribute()
    {
        if ($this->payment_proof) {
            // Return uploads URL (images stored in public/uploads/)
            return asset('uploads/' . $this->payment_proof);
        }
        return null;
    }
}
