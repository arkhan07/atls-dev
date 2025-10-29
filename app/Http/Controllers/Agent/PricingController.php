<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PricingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('IsAgent'); // Menggunakan middleware yang sudah ada
    }

    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Package::where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $packages = $query->get();

        // Hitung statistik untuk dashboard
        $stats = [
            'total_packages' => Package::where('user_id', Auth::id())->count(),
            'active_packages' => Package::where('user_id', Auth::id())->where('status', 'active')->count(),
            'total_registrations' => Registration::whereHas('package', function($q) {
                $q->where('user_id', Auth::id());
            })->count(),
            'total_revenue' => Registration::whereHas('package', function($q) {
                $q->where('user_id', Auth::id());
            })->sum('amount')
        ];

        return view('user.agent.pricing.index', compact('packages', 'status', 'stats'));
    }

    public function create()
    {
        return view('user.agent.pricing.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date_range' => 'required|string|max:100',
            'days' => 'required|string|max:100',
            'time' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'maps_link' => 'nullable|url',
            'quota' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'bank_account' => 'required|string|max:50',
            'bank_name' => 'required|string|max:50',
            'account_name' => 'required|string|max:100',
            'contact_name' => 'required|string|max:100',
            'contact_phone' => 'required|string|max:20',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $data = $request->all();
            $data['user_id'] = Auth::id();

            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = $this->handleImageUpload($request->file('image'));
            }

            Package::create($data);

            DB::commit();

            return redirect()
                ->route('agent.pricing.index')
                ->with('success', 'Paket pricing berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $package = Package::where('id', $id)
                         ->where('user_id', Auth::id())
                         ->firstOrFail();

        $registrations = Registration::where('package_id', $id)
                                   ->with('user')
                                   ->orderBy('created_at', 'desc')
                                   ->get();

        return view('user.agent.pricing.show', compact('package', 'registrations'));
    }

    public function edit($id)
    {
        $package = Package::where('id', $id)
                         ->where('user_id', Auth::id())
                         ->firstOrFail();

        return view('user.agent.pricing.edit', compact('package'));
    }

    public function update(Request $request, $id)
    {
        $package = Package::where('id', $id)
                         ->where('user_id', Auth::id())
                         ->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'date_range' => 'required|string|max:100',
            'days' => 'required|string|max:100',
            'time' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'maps_link' => 'nullable|url',
            'quota' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'bank_account' => 'required|string|max:50',
            'bank_name' => 'required|string|max:50',
            'account_name' => 'required|string|max:100',
            'contact_name' => 'required|string|max:100',
            'contact_phone' => 'required|string|max:20',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $data = $request->all();

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($package->image) {
                    $this->deleteImage($package->image);
                }
                $data['image'] = $this->handleImageUpload($request->file('image'));
            }

            $package->update($data);

            DB::commit();

            return redirect()
                ->route('agent.pricing.index')
                ->with('success', 'Paket pricing berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $package = Package::where('id', $id)
                         ->where('user_id', Auth::id())
                         ->firstOrFail();

        try {
            DB::beginTransaction();

            // Check if has registrations
            $registrationCount = Registration::where('package_id', $id)->count();
            
            if ($registrationCount > 0) {
                // Soft delete by deactivating
                $package->update(['status' => 'inactive']);
                $message = 'Paket telah dinonaktifkan karena memiliki pendaftar.';
            } else {
                // Hard delete
                if ($package->image) {
                    $this->deleteImage($package->image);
                }
                $package->delete();
                $message = 'Paket berhasil dihapus.';
            }

            DB::commit();

            return redirect()
                ->route('agent.pricing.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Request $request, $id)
    {
        $package = Package::where('id', $id)
                         ->where('user_id', Auth::id())
                         ->firstOrFail();

        try {
            $newStatus = $package->status === 'active' ? 'inactive' : 'active';
            $package->update(['status' => $newStatus]);

            $statusText = $newStatus === 'active' ? 'diaktifkan' : 'dinonaktifkan';

            return redirect()
                ->route('agent.pricing.index')
                ->with('success', "Paket berhasil {$statusText}!");

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function handleImageUpload($file)
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/packages'), $filename);
        return $filename;
    }

    private function deleteImage($filename)
    {
        $path = public_path('uploads/packages/' . $filename);
        if (file_exists($path)) {
            unlink($path);
        }
    }
}