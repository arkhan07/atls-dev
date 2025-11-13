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
        // Check authentication
        if (!Auth::check()) {
            Toastr::error('Silakan login terlebih dahulu untuk mendaftar.', 'Error');
            return redirect()->route('login');
        }

        // Only customers can register for packages
        if (Auth::user()->type !== 'customer') {
            Toastr::error('Hanya customer yang dapat mendaftar untuk paket pelatihan. Agent tidak dapat mendaftar.', 'Error');
            return redirect()->back();
        }

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
        // Only customers can register
        if (Auth::user()->type !== 'customer') {
            Toastr::error('Hanya customer yang dapat mendaftar.', 'Error');
            return redirect()->back();
        }

        $package = Package::findOrFail($packageId);
        
        // Validate
        $validated = $request->validate([
            'title' => 'nullable|string|max:50',
            'full_name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'place_of_birth' => 'nullable|string|max:100',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'religion' => 'nullable|string|max:50',
            'id_number' => 'nullable|string|max:50',
            'nik' => 'nullable|string|max:16',
            'plataran_sehat_name' => 'nullable|string|max:255',
            'shirt_size' => 'nullable|in:XS,S,M,L,XL,XXL,XXXL',
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
            'address' => 'required|string',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'shipping_address' => 'nullable|string',
            'shipping_city' => 'nullable|string|max:100',
            'shipping_province' => 'nullable|string|max:100',
            'shipping_postal_code' => 'nullable|string|max:10',
            'notes' => 'nullable|string',
            'agreed_to_terms' => 'required|accepted',
        ]);
        
        // Check quota again
        if ($package->remaining_quota <= 0) {
            Toastr::error('Maaf, kuota paket ini sudah penuh.', 'Error');
            return redirect()->back()->withInput();
        }
        
        try {
            DB::beginTransaction();
            
            // Handle certificate file upload
            $certificateFilePath = null;
            if ($request->hasFile('certificate_file')) {
                $certificateFilePath = $request->file('certificate_file')->store('registrations/certificates', 'public');
            }

            // Calculate age from birth date
            $age = null;
            if ($validated['birth_date']) {
                $age = \Carbon\Carbon::parse($validated['birth_date'])->age;
            }
            
            // Create registration
            $registration = ATLsRegistration::create([
                'user_id' => Auth::id(),
                'package_id' => $packageId,
                'title' => $validated['title'] ?? null,
                'full_name' => $validated['full_name'],
                'nickname' => $validated['nickname'] ?? null,
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'place_of_birth' => $validated['place_of_birth'] ?? null,
                'birth_date' => $validated['birth_date'],
                'age' => $age,
                'gender' => $validated['gender'],
                'religion' => $validated['religion'] ?? null,
                'id_number' => $validated['id_number'] ?? null,
                'nik' => $validated['nik'] ?? null,
                'plataran_sehat_name' => $validated['plataran_sehat_name'] ?? null,
                'shirt_size' => $validated['shirt_size'] ?? null,
                'certificate_file' => $certificateFilePath,
                'address' => $validated['address'],
                'city' => $validated['city'] ?? null,
                'province' => $validated['province'] ?? null,
                'shipping_address' => $validated['shipping_address'] ?? $validated['address'],
                'shipping_city' => $validated['shipping_city'] ?? $validated['city'],
                'shipping_province' => $validated['shipping_province'] ?? $validated['province'],
                'shipping_postal_code' => $validated['shipping_postal_code'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'agreed_to_terms' => true,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'amount_paid' => $package->price,
                'registered_at' => now(),
            ]);
            
            DB::commit();
            
            Toastr::success('Pendaftaran berhasil! Silakan lakukan pembayaran untuk mengkonfirmasi pendaftaran Anda.', 'Sukses');
            
            // Redirect to user's registration list or detail
            if (Auth::check()) {
                return redirect()->route('customer.registrations.index');
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

    /**
     * Upload payment proof
     */
    public function uploadPaymentProof(Request $request, $id)
    {
        $registration = ATLsRegistration::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB
        ]);

        try {
            // Delete old payment proof if exists
            if ($registration->payment_proof && file_exists(storage_path('app/public/' . $registration->payment_proof))) {
                unlink(storage_path('app/public/' . $registration->payment_proof));
            }

            // Store new payment proof
            $path = $request->file('payment_proof')->store('registrations/payment-proofs', 'public');
            
            $registration->update([
                'payment_proof' => $path,
                'payment_status' => 'pending', // Waiting for verification
            ]);

            Toastr::success('Bukti transfer berhasil diupload. Menunggu verifikasi admin.', 'Sukses');
            return redirect()->back();

        } catch (\Exception $e) {
            Toastr::error('Gagal upload bukti transfer: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }
}
