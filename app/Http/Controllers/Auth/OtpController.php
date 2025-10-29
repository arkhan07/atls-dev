<?php
// app/Http/Controllers/Auth/OtpController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OtpVerification;
use App\Services\OneSenderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class OtpController extends Controller
{
    protected $oneSenderService;

    public function __construct(OneSenderService $oneSenderService)
    {
        $this->oneSenderService = $oneSenderService;
    }

    /**
     * Show phone number input form for login
     */
    public function showPhoneForm()
    {
        // Check if WhatsApp OTP is enabled
        if (get_settings('whatsapp_otp_enabled') != 1) {
            Session::flash('error', get_phrase('WhatsApp OTP login is currently disabled'));
            return redirect()->route('login');
        }

        return view('auth.phone-login');
    }

    /**
     * Send OTP for login
     */
    public function sendLoginOTP(Request $request)
    {
        // Check if WhatsApp OTP is enabled
        if (get_settings('whatsapp_otp_enabled') != 1) {
            return response()->json([
                'success' => false,
                'message' => get_phrase('WhatsApp OTP login is currently disabled')
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|min:8|max:15'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => get_phrase('Invalid phone number format'),
                'errors' => $validator->errors()
            ], 422);
        }

        $phoneNumber = $this->oneSenderService->formatPhoneNumber($request->phone);
        
        // Check if user exists
        $user = User::where('phone', $phoneNumber)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => get_phrase('Phone number not registered')
            ], 404);
        }

        // Check if user is active
        if ($user->status != 1) {
            return response()->json([
                'success' => false,
                'message' => get_phrase('Account is inactive. Please contact administrator.')
            ], 403);
        }

        // Clean old OTPs
        OtpVerification::cleanExpired();
        
        // Check rate limiting
        $recentOtp = OtpVerification::where('phone_number', $phoneNumber)
            ->where('type', 'login')
            ->where('created_at', '>', Carbon::now()->subMinute())
            ->first();

        if ($recentOtp) {
            return response()->json([
                'success' => false,
                'message' => get_phrase('Please wait 1 minute before requesting new OTP')
            ], 429);
        }
        
        // Generate new OTP
        $otpCode = OtpVerification::generateOTP();
        
        // Save OTP to database
        OtpVerification::create([
            'phone_number' => $phoneNumber,
            'otp_code' => $otpCode,
            'type' => 'login',
            'expires_at' => Carbon::now()->addMinutes(5)
        ]);

        // Send OTP via WhatsApp
        $sent = $this->oneSenderService->sendOTP($phoneNumber, $otpCode, 'login');
        
        if ($sent) {
            return response()->json([
                'success' => true,
                'message' => get_phrase('OTP code sent to your WhatsApp'),
                'phone_number' => $phoneNumber
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => get_phrase('Failed to send OTP. Please try again.')
            ], 500);
        }
    }

    /**
     * Verify OTP and login
     */
    public function verifyLoginOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
            'otp_code' => 'required|string|size:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => get_phrase('Invalid data provided'),
                'errors' => $validator->errors()
            ], 422);
        }

        $phoneNumber = sanitize($request->phone_number);
        $otpCode = sanitize($request->otp_code);

        // Find OTP record
        $otpRecord = OtpVerification::findValidOTP($phoneNumber, $otpCode, 'login');

        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'message' => get_phrase('Invalid OTP code')
            ], 404);
        }

        // Find user
        $user = User::where('phone', $phoneNumber)->where('status', 1)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => get_phrase('User not found or account inactive')
            ], 404);
        }

        // Mark OTP as verified
        $otpRecord->markAsVerified();
        
        // Login user
        Auth::login($user);

        // Redirect based on user type/role
        $redirectRoute = 'admin.dashboard';
        if ($user->role == 2) { // Agent/Customer
            $redirectRoute = 'user.account';
        }

        return response()->json([
            'success' => true,
            'message' => get_phrase('Login successful'),
            'redirect' => route($redirectRoute)
        ]);
    }

    /**
     * Send OTP for registration (called from register form)
     */
    public function sendRegisterOTP(Request $request)
    {
        // Check if WhatsApp OTP is enabled
        if (get_settings('whatsapp_otp_enabled') != 1) {
            return response()->json([
                'success' => false,
                'message' => get_phrase('WhatsApp OTP registration is currently disabled')
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|min:8|max:15|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => get_phrase('Validation failed'),
                'errors' => $validator->errors()
            ], 422);
        }

        $phoneNumber = $this->oneSenderService->formatPhoneNumber($request->phone);
        
        // Check if phone number is already used (double check)
        if (User::where('phone', $phoneNumber)->exists()) {
            return response()->json([
                'success' => false,
                'message' => get_phrase('Phone number already registered'),
                'errors' => ['phone' => [get_phrase('Phone number already registered')]]
            ], 422);
        }

        // Store registration data in session
        session([
            'register_data' => [
                'name' => sanitize($request->name),
                'email' => sanitize($request->email),
                'phone' => $phoneNumber,
                'password' => Hash::make($request->password),
                'role' => 2, // Default role (customer/agent)
                'type' => 'customer', // Default type
                'status' => 1, // Active status
            ]
        ]);

        // Clean old OTPs
        OtpVerification::cleanExpired();
        
        // Check rate limiting
        $recentOtp = OtpVerification::where('phone_number', $phoneNumber)
            ->where('type', 'register')
            ->where('created_at', '>', Carbon::now()->subMinute())
            ->first();

        if ($recentOtp) {
            return response()->json([
                'success' => false,
                'message' => get_phrase('Please wait 1 minute before requesting new OTP')
            ], 429);
        }
        
        // Generate new OTP
        $otpCode = OtpVerification::generateOTP();
        
        // Save OTP to database
        OtpVerification::create([
            'phone_number' => $phoneNumber,
            'otp_code' => $otpCode,
            'type' => 'register',
            'expires_at' => Carbon::now()->addMinutes(5)
        ]);

        // Send OTP via WhatsApp
        $sent = $this->oneSenderService->sendOTP($phoneNumber, $otpCode, 'register');
        
        if ($sent) {
            return response()->json([
                'success' => true,
                'message' => get_phrase('OTP code sent to your WhatsApp'),
                'phone_number' => $phoneNumber
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => get_phrase('Failed to send OTP. Please try again.')
            ], 500);
        }
    }

    /**
     * Verify OTP and complete registration
     */
    public function verifyRegisterOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
            'otp_code' => 'required|string|size:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => get_phrase('Invalid data provided'),
                'errors' => $validator->errors()
            ], 422);
        }

        $phoneNumber = sanitize($request->phone_number);
        $otpCode = sanitize($request->otp_code);

        // Find OTP record
        $otpRecord = OtpVerification::findValidOTP($phoneNumber, $otpCode, 'register');

        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'message' => get_phrase('Invalid OTP code')
            ], 404);
        }

        // Get registration data from session
        $registerData = session('register_data');
        if (!$registerData) {
            return response()->json([
                'success' => false,
                'message' => get_phrase('Registration data not found. Please restart registration.')
            ], 422);
        }

        // Double check phone number matches
        if ($registerData['phone'] !== $phoneNumber) {
            return response()->json([
                'success' => false,
                'message' => get_phrase('Phone number mismatch. Please restart registration.')
            ], 422);
        }

        try {
            // Create user
            $user = User::create($registerData);
            
            // Mark OTP as verified
            $otpRecord->markAsVerified();
            
            // Clear registration data from session
            session()->forget('register_data');
            
            // Send welcome message (in background, don't wait for response)
            $this->oneSenderService->sendWelcomeMessage($phoneNumber, $registerData['name']);
            
            // Login user
            Auth::login($user);

            // Determine redirect route based on user type
            $redirectRoute = 'admin.dashboard';
            if ($user->role == 2) {
                $redirectRoute = 'user.account';
            }

            return response()->json([
                'success' => true,
                'message' => get_phrase('Registration successful. Welcome!'),
                'redirect' => route($redirectRoute)
            ]);

        } catch (\Exception $e) {
            \Log::error('Registration failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => get_phrase('Registration failed. Please try again.')
            ], 500);
        }
    }

    /**
     * Show OTP verification form
     */
    public function showOtpForm(Request $request)
    {
        $phoneNumber = $request->phone;
        $type = $request->type ?? 'login';
        
        if (!$phoneNumber) {
            Session::flash('error', get_phrase('Phone number required'));
            return redirect()->route($type === 'register' ? 'register' : 'auth.phone-form');
        }

        // Validate type
        if (!in_array($type, ['login', 'register'])) {
            Session::flash('error', get_phrase('Invalid verification type'));
            return redirect()->route('login');
        }
        
        return view('auth.verify-otp', compact('phoneNumber', 'type'));
    }

    /**
     * Resend OTP
     */
    public function resendOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
            'type' => 'required|string|in:login,register'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => get_phrase('Invalid data provided'),
                'errors' => $validator->errors()
            ], 422);
        }

        $phoneNumber = sanitize($request->phone_number);
        $type = $request->type;

        // Check if WhatsApp OTP is enabled
        if (get_settings('whatsapp_otp_enabled') != 1) {
            return response()->json([
                'success' => false,
                'message' => get_phrase('WhatsApp OTP is currently disabled')
            ], 403);
        }

        // Check rate limiting (prevent spam)
        $recentOtp = OtpVerification::where('phone_number', $phoneNumber)
            ->where('type', $type)
            ->where('created_at', '>', Carbon::now()->subMinute())
            ->first();

        if ($recentOtp) {
            return response()->json([
                'success' => false,
                'message' => get_phrase('Please wait 1 minute before resending OTP')
            ], 429);
        }

        // For login type, verify user exists
        if ($type === 'login') {
            $user = User::where('phone', $phoneNumber)->where('status', 1)->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => get_phrase('Phone number not registered')
                ], 404);
            }
        }

        // For register type, check session data
        if ($type === 'register') {
            $registerData = session('register_data');
            if (!$registerData || $registerData['phone'] !== $phoneNumber) {
                return response()->json([
                    'success' => false,
                    'message' => get_phrase('Registration session expired. Please restart registration.')
                ], 422);
            }
        }

        // Generate new OTP
        $otpCode = OtpVerification::generateOTP();
        
        // Save OTP to database
        OtpVerification::create([
            'phone_number' => $phoneNumber,
            'otp_code' => $otpCode,
            'type' => $type,
            'expires_at' => Carbon::now()->addMinutes(5)
        ]);

        // Send OTP via WhatsApp
        $sent = $this->oneSenderService->sendOTP($phoneNumber, $otpCode, $type);
        
        if ($sent) {
            return response()->json([
                'success' => true,
                'message' => get_phrase('New OTP code sent')
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => get_phrase('Failed to send OTP. Please try again.')
            ], 500);
        }
    }

    /**
     * Admin: Show OTP logs and statistics
     */
    public function adminOtpLogs(Request $request)
    {
        // Check admin permission
        if (!auth()->user() || auth()->user()->role != 1) {
            abort(403, 'Unauthorized');
        }

        $query = OtpVerification::latest();
        
        // Filter by type if specified
        if ($request->type && in_array($request->type, ['login', 'register'])) {
            $query->where('type', $request->type);
        }
        
        // Filter by verification status
        if ($request->status) {
            if ($request->status === 'verified') {
                $query->where('is_verified', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_verified', false)
                      ->where('expires_at', '>', Carbon::now());
            } elseif ($request->status === 'expired') {
                $query->where('expires_at', '<', Carbon::now())
                      ->where('is_verified', false);
            }
        }
        
        // Date range filter
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $otpLogs = $query->paginate(20);
        
        // Statistics
        $stats = [
            'total' => OtpVerification::count(),
            'today' => OtpVerification::whereDate('created_at', Carbon::today())->count(),
            'verified' => OtpVerification::where('is_verified', true)->count(),
            'expired' => OtpVerification::where('expires_at', '<', Carbon::now())
                                      ->where('is_verified', false)->count(),
            'success_rate' => 0
        ];
        
        if ($stats['total'] > 0) {
            $stats['success_rate'] = round(($stats['verified'] / $stats['total']) * 100, 1);
        }
        
        // Recent activity (last 7 days)
        $recentActivity = OtpVerification::where('created_at', '>=', Carbon::now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, 
                         SUM(CASE WHEN is_verified = 1 THEN 1 ELSE 0 END) as verified')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.otp.logs', compact('otpLogs', 'stats', 'recentActivity'));
    }

    /**
     * Admin: Clean OTP logs
     */
    public function cleanOtpLogs(Request $request)
    {
        // Check admin permission
        if (!auth()->user() || auth()->user()->role != 1) {
            abort(403, 'Unauthorized');
        }

        $type = $request->clean_type ?? 'expired';
        $deleted = 0;

        switch ($type) {
            case 'expired':
                $deleted = OtpVerification::where('expires_at', '<', Carbon::now())->delete();
                break;
            case 'verified':
                $deleted = OtpVerification::where('is_verified', true)
                                         ->where('updated_at', '<', Carbon::now()->subHour())
                                         ->delete();
                break;
            case 'old':
                $deleted = OtpVerification::where('created_at', '<', Carbon::now()->subWeek())->delete();
                break;
            case 'all':
                if ($request->confirm === 'yes') {
                    $deleted = OtpVerification::query()->delete();
                }
                break;
        }

        Session::flash('success', get_phrase("Deleted {$deleted} OTP records successfully"));

        return response()->json([
            'success' => true,
            'message' => get_phrase("Deleted {$deleted} OTP records"),
            'deleted' => $deleted
        ]);
    }

    /**
     * Admin: Export OTP logs to CSV
     */
    public function exportOtpLogs(Request $request)
    {
        // Check admin permission
        if (!auth()->user() || auth()->user()->role != 1) {
            abort(403, 'Unauthorized');
        }

        $query = OtpVerification::query();
        
        // Apply filters if provided
        if ($request->type && in_array($request->type, ['login', 'register'])) {
            $query->where('type', $request->type);
        }
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $logs = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'otp_logs_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Phone Number', 'OTP Code', 'Type', 'Verified', 
                'Created At', 'Expires At', 'Verified At'
            ]);
            
            // Data rows
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->phone_number,
                    $log->otp_code,
                    $log->type,
                    $log->is_verified ? 'Yes' : 'No',
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->expires_at->format('Y-m-d H:i:s'),
                    $log->is_verified && $log->updated_at ? $log->updated_at->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get OTP statistics for dashboard widget
     */
    public function getOtpStats()
    {
        // Check admin permission
        if (!auth()->user() || auth()->user()->role != 1) {
            abort(403, 'Unauthorized');
        }

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $thisMonth = Carbon::now()->startOfMonth();
        
        $stats = [
            'today' => [
                'total' => OtpVerification::whereDate('created_at', $today)->count(),
                'verified' => OtpVerification::whereDate('created_at', $today)
                                           ->where('is_verified', true)->count(),
                'login' => OtpVerification::whereDate('created_at', $today)
                                         ->where('type', 'login')->count(),
                'register' => OtpVerification::whereDate('created_at', $today)
                                            ->where('type', 'register')->count(),
            ],
            'yesterday' => [
                'total' => OtpVerification::whereDate('created_at', $yesterday)->count(),
                'verified' => OtpVerification::whereDate('created_at', $yesterday)
                                           ->where('is_verified', true)->count(),
            ],
            'month' => [
                'total' => OtpVerification::where('created_at', '>=', $thisMonth)->count(),
                'verified' => OtpVerification::where('created_at', '>=', $thisMonth)
                                           ->where('is_verified', true)->count(),
            ],
            'system' => [
                'enabled' => get_settings('whatsapp_otp_enabled') == 1,
                'api_configured' => !empty(get_settings('onesender_api_key')),
                'total_users_with_phone' => User::whereNotNull('phone')->where('phone', '!=', '')->count(),
            ]
        ];

        // Calculate success rates
        foreach (['today', 'yesterday', 'month'] as $period) {
            $total = $stats[$period]['total'];
            $verified = $stats[$period]['verified'];
            $stats[$period]['success_rate'] = $total > 0 ? round(($verified / $total) * 100, 1) : 0;
        }

        return response()->json($stats);
    }
}