<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\ATLsRegistration;
use App\Models\Package;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    /**
     * Display list of registrations for agent's packages
     */
    public function index(Request $request)
    {
        $page_data['active'] = 'registrations';
        
        // Get agent's packages
        $agentPackages = Package::where('user_id', Auth::id())->pluck('id');
        
        $query = ATLsRegistration::with(['user', 'package.region'])
            ->whereIn('package_id', $agentPackages);
        
        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != 'all') {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Filter by package
        if ($request->has('package_id') && $request->package_id != 'all') {
            $query->where('package_id', $request->package_id);
        }
        
        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $page_data['registrations'] = $query->orderBy('registered_at', 'desc')->paginate(20);
        $page_data['packages'] = Package::where('user_id', Auth::id())->get();
        $page_data['filterStatus'] = $request->get('status', 'all');
        $page_data['filterPaymentStatus'] = $request->get('payment_status', 'all');
        $page_data['filterPackageId'] = $request->get('package_id', 'all');
        $page_data['search'] = $request->get('search', '');
        
        return view('user.agent.registrations.index', $page_data);
    }

    /**
     * Show registration detail
     */
    public function show($id)
    {
        $page_data['active'] = 'registrations';
        
        // Get agent's packages
        $agentPackages = Package::where('user_id', Auth::id())->pluck('id');
        
        $page_data['registration'] = ATLsRegistration::with(['user', 'package.region'])
            ->whereIn('package_id', $agentPackages)
            ->findOrFail($id);
        
        return view('user.agent.registrations.show', $page_data);
    }

    /**
     * Approve or reject payment
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        // Get agent's packages
        $agentPackages = Package::where('user_id', Auth::id())->pluck('id');
        
        $registration = ATLsRegistration::whereIn('package_id', $agentPackages)
            ->findOrFail($id);

        $request->validate([
            'payment_status' => 'required|in:paid,unpaid',
            'status' => 'nullable|in:confirmed,pending',
        ]);

        try {
            $updateData = [
                'payment_status' => $request->payment_status,
            ];

            // If payment approved, also confirm registration
            if ($request->payment_status === 'paid') {
                $updateData['status'] = 'confirmed';
                $updateData['confirmed_at'] = now();
            }

            $registration->update($updateData);

            $message = $request->payment_status === 'paid' 
                ? 'Pembayaran berhasil disetujui dan pendaftaran dikonfirmasi.' 
                : 'Status pembayaran berhasil diupdate.';

            Toastr::success($message, 'Sukses');
            return redirect()->back();

        } catch (\Exception $e) {
            Toastr::error('Gagal update status: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    /**
     * Export registrations to Excel
     */
    public function export(Request $request)
    {
        // Get agent's packages
        $agentPackages = Package::where('user_id', Auth::id())->pluck('id');
        
        $query = ATLsRegistration::with(['user', 'package.region'])
            ->whereIn('package_id', $agentPackages);
        
        // Apply same filters as index
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('payment_status') && $request->payment_status != 'all') {
            $query->where('payment_status', $request->payment_status);
        }
        
        if ($request->has('package_id') && $request->package_id != 'all') {
            $query->where('package_id', $request->package_id);
        }
        
        $registrations = $query->orderBy('registered_at', 'desc')->get();
        
        // Create CSV content
        $filename = 'registrations_' . date('Y-m-d_His') . '.csv';
        $handle = fopen('php://output', 'w');
        
        // Set headers for download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Add BOM for UTF-8
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Add header row
        fputcsv($handle, [
            'ID',
            'Nama Lengkap',
            'Email',
            'Telepon',
            'Paket',
            'Wilayah',
            'Tanggal Lahir',
            'Usia',
            'Jenis Kelamin',
            'Agama',
            'Alamat',
            'Kota',
            'Provinsi',
            'Status Pendaftaran',
            'Status Pembayaran',
            'Jumlah',
            'Tanggal Daftar',
        ]);
        
        // Add data rows
        foreach ($registrations as $reg) {
            fputcsv($handle, [
                $reg->id,
                $reg->title . ' ' . $reg->full_name,
                $reg->email,
                $reg->phone,
                $reg->package->title ?? '-',
                $reg->package->region->name ?? '-',
                $reg->birth_date ? $reg->birth_date->format('d/m/Y') : '-',
                $reg->calculated_age ?? '-',
                $reg->gender == 'male' ? 'Laki-laki' : 'Perempuan',
                $reg->religion ?? '-',
                $reg->address ?? '-',
                $reg->city ?? '-',
                $reg->province ?? '-',
                $reg->status_label,
                $reg->payment_status_label,
                $reg->package->formatted_price ?? '-',
                $reg->registered_at->format('d/m/Y H:i'),
            ]);
        }
        
        fclose($handle);
        exit;
    }
}
