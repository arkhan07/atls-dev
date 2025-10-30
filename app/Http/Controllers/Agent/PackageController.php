<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Region;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;

class PackageController extends Controller
{
    /**
     * Display a listing of packages for the logged-in agent
     */
    public function index(Request $request)
    {
        $page_data['active'] = 'packages';
        $userId = Auth::id();
        
        // Get filter status from request
        $filterStatus = $request->get('status', 'all');
        
        // Build query
        $query = Package::where('user_id', $userId)
            ->with(['region', 'registrations']);
        
        // Apply status filter
        if ($filterStatus !== 'all') {
            $query->where('status', $filterStatus);
        }
        
        // Get packages with pagination
        $page_data['packages'] = $query->orderBy('created_at', 'desc')->paginate(10);
        $page_data['filterStatus'] = $filterStatus;
        
        return view('user.agent.packages.index', $page_data);
    }

    /**
     * Show the form for creating a new package
     */
    public function create()
    {
        $page_data['active'] = 'packages';
        $page_data['regions'] = Region::active()->ordered()->get();
        
        return view('user.agent.packages.create', $page_data);
    }

    /**
     * Store a newly created package
     */
    public function store(Request $request)
    {
        try {
            // Validation
            $request->validate([
                'region_id' => 'required|exists:regions,id',
                'title' => 'required|string|max:255',
                'date_range' => 'required|string|max:255',
                'days' => 'required|string|max:255',
                'time' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'maps_link' => 'nullable|url',
                'quota' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'bank_account' => 'required|string|max:255',
                'bank_name' => 'required|string|max:255',
                'account_name' => 'required|string|max:255',
                'contact_name' => 'required|string|max:255',
                'contact_phone' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'status' => 'required|in:active,inactive',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            
            // Prepare data
            $data = $request->except('image');
            $data['user_id'] = Auth::id();
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/packages'), $imageName);
                $data['image'] = $imageName;
            }
            
            // Create package
            Package::create($data);
            
            Toastr::success('Paket ATLS berhasil ditambahkan!', 'Sukses');
            return redirect()->route('agent.packages.index');
            
        } catch (\Exception $e) {
            Toastr::error('Error: ' . $e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified package
     */
    public function edit($id)
    {
        $page_data['active'] = 'packages';
        $page_data['package'] = Package::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        $page_data['regions'] = Region::active()->ordered()->get();
        
        return view('user.agent.packages.edit', $page_data);
    }

    /**
     * Update the specified package
     */
    public function update(Request $request, $id)
    {
        try {
            // Find package and verify ownership
            $package = Package::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            
            // Validation
            $request->validate([
                'region_id' => 'required|exists:regions,id',
                'title' => 'required|string|max:255',
                'date_range' => 'required|string|max:255',
                'days' => 'required|string|max:255',
                'time' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'maps_link' => 'nullable|url',
                'quota' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'bank_account' => 'required|string|max:255',
                'bank_name' => 'required|string|max:255',
                'account_name' => 'required|string|max:255',
                'contact_name' => 'required|string|max:255',
                'contact_phone' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'status' => 'required|in:active,inactive',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            
            // Prepare data
            $data = $request->except('image');
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($package->image) {
                    $oldImagePath = public_path('uploads/packages/' . $package->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/packages'), $imageName);
                $data['image'] = $imageName;
            }
            
            // Update package
            $package->update($data);
            
            Toastr::success('Paket ATLS berhasil diupdate!', 'Sukses');
            return redirect()->route('agent.packages.index');
            
        } catch (\Exception $e) {
            Toastr::error('Error: ' . $e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Toggle package status (active/inactive)
     */
    public function toggleStatus($id)
    {
        try {
            $package = Package::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            
            $newStatus = $package->status === 'active' ? 'inactive' : 'active';
            $package->update(['status' => $newStatus]);
            
            $statusText = $newStatus === 'active' ? 'diaktifkan' : 'dinonaktifkan';
            Toastr::success("Paket berhasil {$statusText}!", 'Sukses');
            
            return redirect()->back();
            
        } catch (\Exception $e) {
            Toastr::error('Error: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified package
     */
    public function destroy($id)
    {
        try {
            $package = Package::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            
            // Check if package has registrations
            $registrationCount = Registration::where('package_id', $id)->count();
            
            if ($registrationCount > 0) {
                // Don't delete, just deactivate
                $package->update(['status' => 'inactive']);
                Toastr::warning('Paket memiliki pendaftar, status diubah menjadi inactive.', 'Perhatian');
            } else {
                // Delete image if exists
                if ($package->image) {
                    $imagePath = public_path('uploads/packages/' . $package->image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                
                // Delete package
                $package->delete();
                Toastr::success('Paket berhasil dihapus!', 'Sukses');
            }
            
            return redirect()->route('agent.packages.index');
            
        } catch (\Exception $e) {
            Toastr::error('Error: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    /**
     * View registrants for a package
     */
    public function registrations($id)
    {
        $page_data['active'] = 'packages';
        $page_data['package'] = Package::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['registrations.user'])
            ->firstOrFail();
        
        return view('user.agent.packages.registrations', $page_data);
    }
}
