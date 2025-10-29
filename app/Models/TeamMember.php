<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'category',
        'image',
        'social_links',
        'order_num',
        'is_active'
    ];

    protected $casts = [
        'social_links' => 'array',
        'is_active' => 'boolean',
    ];

    // Automatically determine category based on position
    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->category)) {
                $model->category = self::determineCategory($model->position);
            }
        });
        
        static::updating(function ($model) {
            $model->category = self::determineCategory($model->position);
        });
    }

    public static function determineCategory($position)
    {
        if ($position === 'Penasehat') {
            return 'Penasehat';
        } elseif (in_array($position, ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Bendahara'])) {
            return 'Pengurus Pusat';
        } elseif (strpos($position, 'Departemen') !== false || strpos($position, 'Biro') !== false || 
                  strpos($position, 'Pokja') !== false || strpos($position, 'Mutu') !== false) {
            return 'Unsur Staf';
        } elseif (strpos($position, 'Divisi') !== false) {
            return 'Unsur Pelaksana';
        }
        return '';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('category')
                    ->orderBy('position')
                    ->orderBy('order_num');
    }

    // Get available positions grouped by category
    public static function getPositionOptions()
    {
        return [
            'Penasehat' => [
                'Penasehat'
            ],
            'Pengurus Pusat Kontrasia' => [
                'Ketua',
                'Wakil Ketua',
                'Sekretaris',
                'Bendahara'
            ],
            'Unsur Staf Pimpinan' => [
                'Departemen Bidang Traumatologi dan ATI',
                'Departemen Diklat, R & D',
                'Departemen Logistik dan Inventarisasi',
                'Departemen Bencana',
                'Departemen Etika dan Disiplin',
                'Biro Hukum',
                'Pokja ATLS Edisi 10',
                'Mutu Pendidikan'
            ],
            'Unsur Pelaksana' => [
                'Divisi Jawa Timur',
                'Divisi Jawa Tengah dan DIY',
                'Divisi Jawa Barat dan Jakarta',
                'Divisi Bali, NTB, NTT',
                'Divisi Sumatera',
                'Divisi Kalimantan, Sulawesi',
                'Divisi Papua, Maluku'
            ]
        ];
    }

    // Get social platforms
    public static function getSocialPlatforms()
    {
        return ['facebook', 'twitter', 'instagram', 'linkedin'];
    }
}