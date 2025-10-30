<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\RegionPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RegionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Region::with('page');

            // Apply filters
            if ($request->filled('status')) {
                if ($request->status === 'active') {
                    $query->where('is_active', true);
                } elseif ($request->status === 'inactive') {
                    $query->where('is_active', false);
                }
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('slug', 'LIKE', "%{$search}%")
                      ->orWhere('whatsapp', 'LIKE', "%{$search}%");
                });
            }

            // Apply sorting
            $sort = $request->get('sort', 'order_num');
            if ($sort === 'name') {
                $query->orderBy('name');
            } elseif ($sort === 'created_at') {
                $query->orderBy('created_at', 'desc');
            } else {
                $query->orderBy('order_num')->orderBy('name');
            }

            $regions = $query->paginate(10);

            return view('admin.regions.index', compact('regions'));

        } catch (\Exception $e) {
            return back()->with('error', 'Error loading regions: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            return view('admin.regions.create');
        } catch (\Exception $e) {
            return redirect()->route('admin.regions.index')
                           ->with('error', 'Error loading create form: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            // Basic validation
            $request->validate([
                'name' => 'required|string|max:255',
                'whatsapp' => 'required|string|regex:/^628[0-9]{8,13}$/',
                'order_num' => 'required|integer|min:0',
                'icon_image' => 'required|image|mimes:jpeg,png,gif|max:2048',
                'banner_image' => 'required|image|mimes:jpeg,png,gif|max:2048',
                'meta_title' => 'nullable|string|max:60',
                'meta_description' => 'nullable|string|max:160',
                'content' => 'nullable|string'
            ]);

            DB::beginTransaction();

            // Generate unique slug
            $region = new Region();
            $slug = $region->generateUniqueSlug($request->name);

            // Handle image uploads
            $iconPath = null;
            $bannerPath = null;

            if ($request->hasFile('icon_image')) {
                $iconPath = $request->file('icon_image')->store('regions/icons', 'public');
            }

            if ($request->hasFile('banner_image')) {
                $bannerPath = $request->file('banner_image')->store('regions/banners', 'public');
            }

            // Create region
            $region = Region::create([
                'name' => $request->name,
                'slug' => $slug,
                'meta_title' => $request->meta_title ?: $request->name,
                'meta_description' => $request->meta_description,
                'icon_image' => $iconPath,
                'banner_image' => $bannerPath,
                'whatsapp' => $request->whatsapp,
                'order_num' => $request->order_num,
                'is_active' => $request->boolean('is_active', true)
            ]);

            // Create default page content
            $defaultContent = $request->content ?: "
                <div class='region-content'>
                    <h1>Wilayah {$region->name}</h1>
                    <p>Selamat datang di wilayah {$region->name}. Silakan hubungi kami untuk informasi lebih lanjut.</p>
                </div>
            ";

            RegionPage::create([
                'region_id' => $region->id,
                'slug' => $slug,
                'title' => $region->name,
                'content' => $defaultContent
            ]);

            DB::commit();

            return redirect()->route('admin.regions.index')
                           ->with('success', "Region '{$region->name}' berhasil ditambahkan! URL: /wilayah-detail/{$slug}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating region: ' . $e->getMessage());
        }
    }

    public function show(Region $region)
    {
        try {
            $region->load('page');
            return view('admin.regions.show', compact('region'));
        } catch (\Exception $e) {
            return redirect()->route('admin.regions.index')
                           ->with('error', 'Error loading region: ' . $e->getMessage());
        }
    }

    public function edit(Region $region)
    {
        try {
            $region->load('page');
            return view('admin.regions.edit', compact('region'));
        } catch (\Exception $e) {
            return redirect()->route('admin.regions.index')
                           ->with('error', 'Error loading region for edit: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Region $region)
    {
        try {
            // Basic validation
            $request->validate([
                'name' => 'required|string|max:255',
                'whatsapp' => 'required|string|regex:/^628[0-9]{8,13}$/',
                'order_num' => 'required|integer|min:0',
                'icon_image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
                'banner_image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
                'meta_title' => 'nullable|string|max:60',
                'meta_description' => 'nullable|string|max:160',
                'content' => 'nullable|string'
            ]);

            DB::beginTransaction();

            // Handle image uploads
            if ($request->hasFile('icon_image')) {
                // Delete old image
                if ($region->icon_image && Storage::disk('public')->exists($region->icon_image)) {
                    Storage::disk('public')->delete($region->icon_image);
                }
                $region->icon_image = $request->file('icon_image')->store('regions/icons', 'public');
            }

            if ($request->hasFile('banner_image')) {
                // Delete old image
                if ($region->banner_image && Storage::disk('public')->exists($region->banner_image)) {
                    Storage::disk('public')->delete($region->banner_image);
                }
                $region->banner_image = $request->file('banner_image')->store('regions/banners', 'public');
            }

            // Update region
            $region->update([
                'name' => $request->name,
                'meta_title' => $request->meta_title ?: $request->name,
                'meta_description' => $request->meta_description,
                'whatsapp' => $request->whatsapp,
                'order_num' => $request->order_num,
                'is_active' => $request->boolean('is_active', true)
            ]);

            // Update page content if provided
            if ($region->page && $request->filled('content')) {
                $region->page->update([
                    'content' => $request->content
                ]);
            }

            DB::commit();

            return redirect()->route('admin.regions.index')
                           ->with('success', "Region '{$region->name}' berhasil diperbarui!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating region: ' . $e->getMessage());
        }
    }

    public function destroy(Region $region)
    {
        try {
            DB::beginTransaction();

            // Delete images
            if ($region->icon_image && Storage::disk('public')->exists($region->icon_image)) {
                Storage::disk('public')->delete($region->icon_image);
            }
            if ($region->banner_image && Storage::disk('public')->exists($region->banner_image)) {
                Storage::disk('public')->delete($region->banner_image);
            }

            // Delete region (page will be deleted automatically due to cascade)
            $region->delete();

            DB::commit();

            return redirect()->route('admin.regions.index')
                           ->with('success', "Region '{$region->name}' berhasil dihapus!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting region: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Request $request, Region $region)
    {
        try {
            $region->update([
                'is_active' => !$region->is_active
            ]);

            $status = $region->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return back()->with('success', "Region '{$region->name}' berhasil {$status}!");

        } catch (\Exception $e) {
            return back()->with('error', 'Error updating status: ' . $e->getMessage());
        }
    }

    public function publicShow($slug)
    {
        try {
            $region = Region::with(['page', 'activePackages.user'])
                ->where('slug', $slug)
                ->where('is_active', true)
                ->firstOrFail();
            
            // Get active packages for this region
            $packages = $region->activePackages()
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
            
            return view('frontend.region-detail', compact('region', 'packages'));
            
        } catch (\Exception $e) {
            abort(404, 'Region not found');
        }
    }

    // Additional methods for bulk actions
    public function bulkDelete(Request $request)
    {
        try {
            $ids = json_decode($request->ids, true);
            if (!is_array($ids) || empty($ids)) {
                return back()->with('error', 'No regions selected');
            }

            DB::beginTransaction();

            $regions = Region::whereIn('id', $ids)->get();
            
            foreach ($regions as $region) {
                // Delete images
                if ($region->icon_image && Storage::disk('public')->exists($region->icon_image)) {
                    Storage::disk('public')->delete($region->icon_image);
                }
                if ($region->banner_image && Storage::disk('public')->exists($region->banner_image)) {
                    Storage::disk('public')->delete($region->banner_image);
                }
                $region->delete();
            }

            DB::commit();

            return back()->with('success', count($regions) . ' regions berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting regions: ' . $e->getMessage());
        }
    }

    public function bulkActivate(Request $request)
    {
        try {
            $ids = json_decode($request->ids, true);
            if (!is_array($ids) || empty($ids)) {
                return back()->with('error', 'No regions selected');
            }

            $count = Region::whereIn('id', $ids)->update(['is_active' => true]);
            
            return back()->with('success', "{$count} regions berhasil diaktifkan!");

        } catch (\Exception $e) {
            return back()->with('error', 'Error activating regions: ' . $e->getMessage());
        }
    }

    public function bulkDeactivate(Request $request)
    {
        try {
            $ids = json_decode($request->ids, true);
            if (!is_array($ids) || empty($ids)) {
                return back()->with('error', 'No regions selected');
            }

            $count = Region::whereIn('id', $ids)->update(['is_active' => false]);
            
            return back()->with('success', "{$count} regions berhasil dinonaktifkan!");

        } catch (\Exception $e) {
            return back()->with('error', 'Error deactivating regions: ' . $e->getMessage());
        }
    }

    public function export()
    {
        // Implementation for export functionality
        return response()->json(['message' => 'Export functionality will be implemented']);
    }

    public function import(Request $request)
    {
        // Implementation for import functionality
        return back()->with('success', 'Import functionality will be implemented');
    }

    public function statistics()
    {
        try {
            $stats = [
                'total_regions' => Region::count(),
                'active_regions' => Region::where('is_active', true)->count(),
                'inactive_regions' => Region::where('is_active', false)->count(),
                'with_pages' => Region::has('page')->count()
            ];

            return response()->json($stats);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}