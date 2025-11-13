<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ATLsRegistration;
use Illuminate\Http\Request;

class ATLsRegistrationController extends Controller
{
    /**
     * Display list of all registrations
     */
    public function index(Request $request)
    {
        $query = ATLsRegistration::with(['user', 'package.region']);
        
        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != 'all') {
            $query->where('payment_status', $request->payment_status);
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
        $page_data['filterStatus'] = $request->get('status', 'all');
        $page_data['filterPaymentStatus'] = $request->get('payment_status', 'all');
        $page_data['search'] = $request->get('search', '');
        
        return view('admin.registrations.index', $page_data);
    }

    /**
     * Display registration details
     */
    public function show($id)
    {
        $page_data['registration'] = ATLsRegistration::with(['user', 'package.region'])
            ->findOrFail($id);
        
        return view('admin.registrations.show', $page_data);
    }
}
