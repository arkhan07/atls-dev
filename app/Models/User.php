<?php
// =====================================================
// 1. UPDATED USER MODEL - app/Models/User.php
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',        // Kolom phone sudah ada di database Anda
        'password',
        'image',
        'role',
        'type',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // =====================================================
    // EXISTING WHATSAPP OTP METHODS
    // =====================================================

    /**
     * Scope untuk mencari user berdasarkan nomor telepon
     */
    public function scopeByPhoneNumber($query, $phoneNumber)
    {
        return $query->where('phone', $phoneNumber);
    }

    /**
     * Dapatkan verifikasi OTP untuk user ini
     */
    public function otpVerifications()
    {
        return $this->hasMany(\App\Models\OtpVerification::class, 'phone_number', 'phone');
    }

    /**
     * Cari user berdasarkan phone atau email untuk login
     */
    public static function findByPhoneOrEmail($identifier)
    {
        return static::where('email', $identifier)
            ->orWhere('phone', $identifier)
            ->first();
    }

    /**
     * Check if user has verified phone number
     */
    public function hasVerifiedPhone()
    {
        return !empty($this->phone);
    }

    /**
     * Format phone number for display
     */
    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone) {
            return null;
        }
        
        // Format: +62 812-3456-7890
        $phone = $this->phone;
        if (substr($phone, 0, 2) === '62') {
            return '+62 ' . substr($phone, 2, 3) . '-' . substr($phone, 5, 4) . '-' . substr($phone, 9);
        }
        
        return $phone;
    }

    /**
     * Check if user can receive OTP
     */
    public function canReceiveOTP()
    {
        return !empty($this->phone) && $this->status == 1;
    }

    // =====================================================
    // NEW: AGENT PRICING RELATIONSHIPS & METHODS
    // =====================================================

    /**
     * Get all packages owned by this user (agent)
     */
    public function packages()
    {
        return $this->hasMany(\App\Models\Package::class);
    }

    /**
     * Get all registrations made by this user (customer)
     */
    public function registrations()
    {
        return $this->hasMany(\App\Models\Registration::class);
    }

    /**
     * Get all registrations for packages owned by this agent
     */
    public function agentRegistrations()
    {
        return \App\Models\Registration::whereHas('package', function($query) {
            $query->where('user_id', $this->id);
        });
    }

    /**
     * Get active packages for this agent
     */
    public function activePackages()
    {
        return $this->packages()->where('status', 'active');
    }

    /**
     * Get total revenue for this agent
     */
    public function getTotalRevenueAttribute()
    {
        return $this->agentRegistrations()
                   ->where('payment_status', 'paid')
                   ->sum('amount');
    }

    /**
     * Get total registrations count for this agent
     */
    public function getTotalRegistrationsAttribute()
    {
        return $this->agentRegistrations()->count();
    }

    // =====================================================
    // USER TYPE & ROLE METHODS
    // =====================================================

    /**
     * Check if user is agent
     */
    public function isAgent()
    {
        return $this->type === 'agent' || $this->role === 'agent';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->type === 'admin' || $this->role === 'admin';
    }

    /**
     * Check if user is customer
     */
    public function isCustomer()
    {
        return $this->type === 'customer' || $this->role === 'customer';
    }

    /**
     * Check if user can create packages
     */
    public function canCreatePackages()
    {
        return $this->isAgent() && $this->status == 1;
    }

    /**
     * Check if user can register for packages
     */
    public function canRegisterPackages()
    {
        return $this->status == 1;
    }

    // =====================================================
    // EXISTING RELATIONSHIPS (jika ada)
    // =====================================================

    /**
     * Get user's wishlist items
     */
    public function wishlists()
    {
        return $this->hasMany(\App\Models\Wishlist::class);
    }

    /**
     * Get user's appointments
     */
    public function appointments()
    {
        return $this->hasMany(\App\Models\Appointment::class);
    }

    /**
     * Get user's reviews
     */
    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class);
    }

    /**
     * Get user's blog posts (if agent/admin)
     */
    public function blogs()
    {
        return $this->hasMany(\App\Models\Blog::class, 'author_id');
    }

    // =====================================================
    // SCOPES
    // =====================================================

    /**
     * Scope untuk filter user berdasarkan type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope untuk filter user aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope untuk filter agent
     */
    public function scopeAgents($query)
    {
        return $query->where('type', 'agent');
    }

    /**
     * Scope untuk filter customer
     */
    public function scopeCustomers($query)
    {
        return $query->where('type', 'customer');
    }
}

