<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

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
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            
            // Create directory if not exists
            $uploadPath = public_path('uploads/gallery/');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Resize and save image
            $img = Image::make($image);
            $img->resize(1200, 800, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($uploadPath . $imageName, 80); // 80% quality

            // Create thumbnail
            $thumbnailPath = public_path('uploads/gallery/thumbnails/');
            if (!file_exists($thumbnailPath)) {
                mkdir($thumbnailPath, 0755, true);
            }
            
            $thumbnail = Image::make($image);
            $thumbnail->resize(400, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $thumbnail->save($thumbnailPath . $imageName, 80);

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

            // Upload new image (same process as store)
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            
            $uploadPath = public_path('uploads/gallery/');
            $img = Image::make($image);
            $img->resize(1200, 800, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($uploadPath . $imageName, 80);

            // Create thumbnail
            $thumbnailPath = public_path('uploads/gallery/thumbnails/');
            $thumbnail = Image::make($image);
            $thumbnail->resize(400, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $thumbnail->save($thumbnailPath . $imageName, 80);

            $gallery->image = $imageName;
        }

        $gallery->save();

        return redirect()->route('admin.gallery.index')
                        ->with('success', get_phrase('Gallery item updated successfully'));
    }

    public function destroy(Gallery $gallery)
    {
        try {
            \DB::beginTransaction();
            
            // Delete image files
            $imagePath = public_path('uploads/gallery/' . $gallery->image);
            $thumbnailPath = public_path('uploads/gallery/thumbnails/' . $gallery->image);
            
            if (file_exists($imagePath)) {
                @unlink($imagePath);
            }
            if (file_exists($thumbnailPath)) {
                @unlink($thumbnailPath);
            }

            // Delete record using DB query to avoid any model events
            \DB::table('galleries')->where('id', $gallery->id)->delete();
            
            \DB::commit();

            return redirect()->route('admin.gallery.index')
                            ->with('success', get_phrase('Gallery item deleted successfully'));
                            
        } catch (\Exception $e) {
            \DB::rollBack();
            
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
        $categories = Gallery::getCategories();
        
        return view('admin.gallery.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'key' => 'required|string|max:100|unique:gallery_categories,key',
            'name' => 'required|string|max:255',
        ]);

        // Note: For now, categories are hardcoded in the model
        // If you need dynamic categories, you'll need to create a gallery_categories table
        // and modify the Gallery::getCategories() method
        
        return redirect()->back()
                        ->with('warning', get_phrase('Categories are currently managed in the code. Contact developer for custom categories.'));
    }

    public function updateCategory(Request $request, $id)
    {
        // Categories are currently hardcoded
        // This is a placeholder for future dynamic category management
        
        return redirect()->back()
                        ->with('warning', get_phrase('Categories are currently managed in the code. Contact developer for custom categories.'));
    }

    public function destroyCategory($id)
    {
        // Categories are currently hardcoded
        // This is a placeholder for future dynamic category management
        
        return redirect()->back()
                        ->with('warning', get_phrase('Categories are currently managed in the code. Contact developer for custom categories.'));
    }
}