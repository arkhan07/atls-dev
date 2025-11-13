<?php

namespace App\Http\Controllers;

use App\Models\ATLsRegistration;
use App\Models\Package;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    /**
     * Show registration form for a package
     */
    public function create($packageId)
    {
        $package = Package::with('region')->findOrFail($packageId);
        
        // Check if package is active
        if ($package->status !== 'active') {
            Toastr::error('Paket ini sedang tidak aktif.', 'Error');
            return redirect()->back();
        }
        
        // Check if quota is full
        if ($package->remaining_quota <= 0) {
            Toastr::error('Maaf, kuota paket ini sudah penuh.', 'Error');
            return redirect()->back();
        }
        
        $user = Auth::user();
        
        return view('frontend.register-package', compact('package', 'user'));
    }

    /**
     * Store registration
     */
    public function store(Request $request, $packageId)
    {
        $package = Package::findOrFail($packageId);
        
        // Validate
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'id_number' => 'nullable|string|max:50',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'profession' => 'nullable|string|max:100',
            'institution' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:100',
            'license_number' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'is_certified' => 'nullable|boolean',
            'previous_certification_date' => 'nullable|date',
        ]);
        
        // Check quota again
        if ($package->remaining_quota <= 0) {
            Toastr::error('Maaf, kuota paket ini sudah penuh.', 'Error');
            return redirect()->back()->withInput();
        }
        
        try {
            DB::beginTransaction();
            
            // Create registration
            $registration = ATLsRegistration::create([
                'user_id' => Auth::id(),
                'package_id' => $packageId,
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'id_number' => $validated['id_number'] ?? null,
                'birth_date' => $validated['birth_date'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'profession' => $validated['profession'] ?? null,
                'institution' => $validated['institution'] ?? null,
                'specialization' => $validated['specialization'] ?? null,
                'license_number' => $validated['license_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'province' => $validated['province'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'is_certified' => $request->has('is_certified'),
                'previous_certification_date' => $validated['previous_certification_date'] ?? null,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'amount_paid' => $package->price,
                'registered_at' => now(),
            ]);
            
            DB::commit();
            
            Toastr::success('Pendaftaran berhasil! Silakan lakukan pembayaran untuk mengkonfirmasi pendaftaran Anda.', 'Sukses');
            
            // Redirect to user's registration list or detail
            if (Auth::check()) {
                return redirect()->route('user.registrations.index');
            }
            
            return redirect()->route('home');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Terjadi kesalahan: ' . $e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show user's registrations
     */
    public function index()
    {
        $page_data['active'] = 'registrations';
        $page_data['registrations'] = ATLsRegistration::with(['package.region'])
            ->where('user_id', Auth::id())
            ->orderBy('registered_at', 'desc')
            ->paginate(10);
        
        return view('user.registrations.index', $page_data);
    }

    /**
     * Show registration detail
     */
    public function show($id)
    {
        $page_data['active'] = 'registrations';
        $page_data['registration'] = ATLsRegistration::with(['package.region', 'user'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        return view('user.registrations.show', $page_data);
    }
}
