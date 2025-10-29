<?php

namespace App\Services;

use App\Models\Region;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class RegionService
{
    public function handleImageUpload(UploadedFile $file, string $type, ?string $oldImage = null): string
    {
        // Delete old image if exists
        if ($oldImage && Storage::disk('public')->exists($oldImage)) {
            Storage::disk('public')->delete($oldImage);
        }

        $directory = "regions/{$type}";
        $filename = uniqid("region_{$type}_") . '.' . $file->getClientOriginalExtension();
        
        return $file->storeAs($directory, $filename, 'public');
    }

    public function generateSlug(string $name, ?int $excludeId = null): string
    {
        $baseSlug = \Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (Region::where('slug', $slug)
                    ->when($excludeId, fn($query) => $query->where('id', '!=', $excludeId))
                    ->exists()) {
            $slug = $baseSlug . '-' . $counter++;
            if ($counter > 100) {
                $slug = $baseSlug . '-' . time();
                break;
            }
        }

        return $slug;
    }
}