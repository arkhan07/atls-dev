<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'log_name',
        'description',
        'subject_id',
        'subject_type',
        'causer_id',
        'causer_type',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the subject of the activity log.
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Get the causer (user who performed the action) of the activity log.
     */
    public function causer()
    {
        return $this->morphTo();
    }

    /**
     * Scope to filter by log name
     */
    public function scopeForLogName($query, $logName)
    {
        return $query->where('log_name', $logName);
    }

    /**
     * Scope to filter by causer
     */
    public function scopeCausedBy($query, $causer)
    {
        return $query->where('causer_type', get_class($causer))
                     ->where('causer_id', $causer->id);
    }
}
