<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::query();

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $galleries = $query->orderBy('sort_order', 'asc')
                          ->orderBy('created_at', 'desc')
                          ->paginate(20);

        $categories = Gallery::getCategories();

        return view('admin.gallery.index', compact('galleries', 'categories'));
    }

    public function create()
    {
        $categories = Gallery::getCategories();
        return view('admin.gallery.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'category' => 'nullable|string',
            'is_featured' => 'boolean',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'integer|min:0'
        ]);

        $gallery = new Gallery();
        $gallery->title = $request->title;
        $gallery->description = $request->description;
        $gallery->category = $request->category;
        $gallery->is_featured = $request->boolean('is_featured');
        $gallery->status = $request->status;
        $gallery->sort_order = $request->sort_order ?? 0;
        $gallery->created_by = auth()->id();

        // Handle image upload
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $imageFile->getClientOriginalExtension();
            
            // Create directory if not exists
            $uploadPath = public_path('uploads/gallery/');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Initialize Image Manager with GD driver
            $manager = new ImageManager(new Driver());
            
            // Resize and save image
            $img = $manager->read($imageFile->getRealPath());
            $img->scale(width: 1200);
            $img->save($uploadPath . $imageName, quality: 80);

            // Create thumbnail
            $thumbnailPath = public_path('uploads/gallery/thumbnails/');
            if (!file_exists($thumbnailPath)) {
                mkdir($thumbnailPath, 0755, true);
            }
            
            $thumbnail = $manager->read($imageFile->getRealPath());
            $thumbnail->cover(400, 300);
            $thumbnail->save($thumbnailPath . $imageName, quality: 80);

            $gallery->image = $imageName;
        }

        $gallery->save();

        return redirect()->route('admin.gallery.index')
                        ->with('success', get_phrase('Gallery item created successfully'));
    }

    public function show(Gallery $gallery)
    {
        return view('admin.gallery.show', compact('gallery'));
    }

    public function edit(Gallery $gallery)
    {
        $categories = Gallery::getCategories();
        return view('admin.gallery.edit', compact('gallery', 'categories'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'category' => 'nullable|string',
            'is_featured' => 'boolean',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'integer|min:0'
        ]);

        $gallery->title = $request->title;
        $gallery->description = $request->description;
        $gallery->category = $request->category;
        $gallery->is_featured = $request->boolean('is_featured');
        $gallery->status = $request->status;
        $gallery->sort_order = $request->sort_order ?? 0;

        // Handle image upload if new image provided
        if ($request->hasFile('image')) {
            // Delete old image
            $oldImagePath = public_path('uploads/gallery/' . $gallery->image);
            $oldThumbnailPath = public_path('uploads/gallery/thumbnails/' . $gallery->image);
            
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
            if (file_exists($oldThumbnailPath)) {
                unlink($oldThumbnailPath);
            }

            // Upload new image
            $imageFile = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $imageFile->getClientOriginalExtension();
            
            // Initialize Image Manager
            $manager = new ImageManager(new Driver());
            
            $uploadPath = public_path('uploads/gallery/');
            $img = $manager->read($imageFile->getRealPath());
            $img->scale(width: 1200);
            $img->save($uploadPath . $imageName, quality: 80);

            // Create thumbnail
            $thumbnailPath = public_path('uploads/gallery/thumbnails/');
            $thumbnail = $manager->read($imageFile->getRealPath());
            $thumbnail->cover(400, 300);
            $thumbnail->save($thumbnailPath . $imageName, quality: 80);

            $gallery->image = $imageName;
        }

        $gallery->save();

        return redirect()->route('admin.gallery.index')
                        ->with('success', get_phrase('Gallery item updated successfully'));
    }

    public function destroy(Gallery $gallery)
    {
        try {
            // Delete image files first
            $imagePath = public_path('uploads/gallery/' . $gallery->image);
            $thumbnailPath = public_path('uploads/gallery/thumbnails/' . $gallery->image);
            
            if (file_exists($imagePath) && is_file($imagePath)) {
                @unlink($imagePath);
            }
            if (file_exists($thumbnailPath) && is_file($thumbnailPath)) {
                @unlink($thumbnailPath);
            }

            // Delete the gallery record
            $gallery->delete();

            return redirect()->route('admin.gallery.index')
                            ->with('success', get_phrase('Gallery item deleted successfully'));
                            
        } catch (\Exception $e) {
            \Log::error('Gallery delete error: ' . $e->getMessage());
            
            return redirect()->route('admin.gallery.index')
                            ->with('error', 'Error deleting gallery: ' . $e->getMessage());
        }
    }

    public function updateStatus($id, $status)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->status = $status;
        $gallery->save();

        return redirect()->back()
                        ->with('success', get_phrase('Gallery status updated successfully'));
    }

    public function toggleFeatured(Gallery $gallery)
    {
        $gallery->is_featured = !$gallery->is_featured;
        $gallery->save();

        return response()->json([
            'success' => true,
            'is_featured' => $gallery->is_featured,
            'message' => get_phrase('Featured status updated successfully')
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        
        if (!empty($ids)) {
            $galleries = Gallery::whereIn('id', $ids)->get();
            
            foreach ($galleries as $gallery) {
                // Delete image files
                $imagePath = public_path('uploads/gallery/' . $gallery->image);
                $thumbnailPath = public_path('uploads/gallery/thumbnails/' . $gallery->image);
                
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                if (file_exists($thumbnailPath)) {
                    unlink($thumbnailPath);
                }
                
                $gallery->delete();
            }
        }

        return response()->json([
            'success' => true,
            'message' => get_phrase('Selected items deleted successfully')
        ]);
    }

    public function updateSortOrder(Request $request)
    {
        $orders = $request->orders;
        
        foreach ($orders as $order) {
            Gallery::where('id', $order['id'])->update(['sort_order' => $order['position']]);
        }

        return response()->json([
            'success' => true,
            'message' => get_phrase('Sort order updated successfully')
        ]);
    }

    public function bulkStatusUpdate(Request $request)
    {
        $ids = $request->ids;
        $status = $request->status;
        
        if (!empty($ids) && in_array($status, ['active', 'inactive'])) {
            Gallery::whereIn('id', $ids)->update(['status' => $status]);
            
            return response()->json([
                'success' => true,
                'message' => get_phrase('Status updated successfully')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => get_phrase('Invalid request')
        ], 400);
    }

    // =====================================================
    // GALLERY CATEGORY MANAGEMENT METHODS
    // =====================================================
    
    public function manageCategories()
    {
        try {
            $categories = \App\Models\GalleryCategory::orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->paginate(20);

            return view('admin.gallery.categories', compact('categories'));
        } catch (\Exception $e) {
            // If table doesn't exist, show error message
            return redirect()->route('admin.gallery.index')
                ->with('error', 'Gallery Categories table belum dibuat. Silakan jalankan migration terlebih dahulu: php artisan migrate');
        }
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:gallery_categories,slug',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $category = new \App\Models\GalleryCategory();
        $category->name = $request->name;
        $category->slug = $request->slug ?: \Str::slug($request->name);
        $category->description = $request->description;
        $category->icon = $request->icon;
        $category->sort_order = $request->sort_order ?? 0;
        $category->status = $request->status;
        $category->save();
        
        return redirect()->back()
                        ->with('success', get_phrase('Category created successfully'));
    }

    public function updateCategory(Request $request, $id)
    {
        $category = \App\Models\GalleryCategory::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:gallery_categories,slug,' . $id,
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $category->name = $request->name;
        if ($request->filled('slug')) {
            $category->slug = $request->slug;
        }
        $category->description = $request->description;
        $category->icon = $request->icon;
        $category->sort_order = $request->sort_order ?? 0;
        $category->status = $request->status;
        $category->save();
        
        return redirect()->back()
                        ->with('success', get_phrase('Category updated successfully'));
    }

    public function destroyCategory($id)
    {
        try {
            $category = \App\Models\GalleryCategory::findOrFail($id);
            
            // Check if category is being used
            $galleryCount = Gallery::where('category', $category->slug)->count();
            
            if ($galleryCount > 0) {
                return redirect()->back()
                    ->with('error', get_phrase('Cannot delete category. It is being used by ' . $galleryCount . ' gallery items.'));
            }
            
            $category->delete();
            
            return redirect()->back()
                            ->with('success', get_phrase('Category deleted successfully'));
                            
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error deleting category: ' . $e->getMessage());
        }
    }
}