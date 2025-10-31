<?php

namespace App\Observers;

use App\Models\Package;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class PackageObserver
{
    /**
     * Handle the Package "created" event.
     */
    public function created(Package $package): void
    {
        $this->logActivity($package, 'created', 'Package created');
    }

    /**
     * Handle the Package "updated" event.
     */
    public function updated(Package $package): void
    {
        $changes = $package->getChanges();
        
        // Log specifically if status changed
        if (isset($changes['status'])) {
            $this->logActivity(
                $package, 
                'status_changed', 
                "Package status changed to {$changes['status']}",
                ['old_status' => $package->getOriginal('status'), 'new_status' => $changes['status']]
            );
        } else {
            $this->logActivity($package, 'updated', 'Package updated', $changes);
        }
    }

    /**
     * Handle the Package "deleted" event.
     */
    public function deleted(Package $package): void
    {
        $this->logActivity($package, 'deleted', 'Package deleted');
    }

    /**
     * Log activity to activity_logs table
     */
    private function logActivity(Package $package, string $logName, string $description, array $properties = []): void
    {
        try {
            ActivityLog::create([
                'log_name' => $logName,
                'description' => $description,
                'subject_id' => $package->id,
                'subject_type' => get_class($package),
                'causer_id' => Auth::id(),
                'causer_type' => Auth::check() ? get_class(Auth::user()) : null,
                'properties' => $properties,
            ]);
        } catch (\Exception $e) {
            // Silently fail if activity logging fails - don't break the main operation
            \Log::warning("Failed to log activity for package {$package->id}: " . $e->getMessage());
        }
    }
}
