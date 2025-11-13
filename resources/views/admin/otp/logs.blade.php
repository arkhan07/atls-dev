{{-- resources/views/admin/otp/logs.blade.php --}}
@extends('layouts.admin')
@section('title', get_phrase('WhatsApp OTP Logs'))
@section('admin_layout')

<div class="ol-card radius-8px">
    <div class="ol-card-body my-2 py-12px px-20px">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-document me-2"></i>
                {{ get_phrase('WhatsApp OTP Logs') }}
            </h4>
            <div class="d-flex gap-2">
                <button type="button" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px" onclick="exportLogs()">
                    <span class="fi-rr-download"></span>
                    <span>{{ get_phrase('Export CSV') }}</span>
                </button>
                <div class="dropdown">
                    <button class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <span class="fi-rr-trash"></span>
                        <span>{{ get_phrase('Cleanup') }}</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="javascript:void(0);" onclick="cleanupOTP('expired')">{{ get_phrase('Clean Expired') }}</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);" onclick="cleanupOTP('verified')">{{ get_phrase('Clean Verified') }}</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);" onclick="cleanupOTP('old')">{{ get_phrase('Clean Old (7+ days)') }}</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="cleanupOTP('all')">{{ get_phrase('Clean All') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-3"
{{-- Statistics Cards --}}
<div class="row">
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="card-title">{{ get_phrase('Total OTPs') }}</p>
                        <h3 class="font-weight-bold mb-0">{{ number_format($stats['total']) }}</h3>
                    </div>
                    <div class="icon-holder">
                        <i class="mdi mdi-message-text-outline icon-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="card-title">{{ get_phrase('Today') }}</p>
                        <h3 class="font-weight-bold mb-0">{{ $stats['today'] }}</h3>
                    </div>
                    <div class="icon-holder">
                        <i class="mdi mdi-calendar-today icon-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="card-title">{{ get_phrase('Verified') }}</p>
                        <h3 class="font-weight-bold mb-0 text-success">{{ number_format($stats['verified']) }}</h3>
                    </div>
                    <div class="icon-holder">
                        <i class="mdi mdi-check-circle icon-lg text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="card-title">{{ get_phrase('Success Rate') }}</p>
                        <h3 class="font-weight-bold mb-0">{{ $stats['success_rate'] }}%</h3>
                    </div>
                    <div class="icon-holder">
                        <i class="mdi mdi-chart-line icon-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ get_phrase('Filters') }}</h5>
                <form method="GET" class="row g-3">
                    <div class="col-md-2">
                        <select name="type" class="form-select">
                            <option value="">{{ get_phrase('All Types') }}</option>
                            <option value="login" {{ request('type') == 'login' ? 'selected' : '' }}>Login</option>
                            <option value="register" {{ request('type') == 'register' ? 'selected' : '' }}>Register</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">{{ get_phrase('All Status') }}</option>
                            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From Date">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To Date">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">{{ get_phrase('Filter') }}</button>
                        <a href="{{ route('admin.otp.logs') }}" class="btn btn-secondary">{{ get_phrase('Reset') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- OTP Logs Table --}}
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ get_phrase('OTP Records') }}</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ get_phrase('Phone Number') }}</th>
                                <th>{{ get_phrase('Type') }}</th>
                                <th>{{ get_phrase('Status') }}</th>
                                <th>{{ get_phrase('Created At') }}</th>
                                <th>{{ get_phrase('Expires At') }}</th>
                                <th>{{ get_phrase('Verified At') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($otpLogs as $log)
                            <tr>
                                <td>
                                    <span class="font-weight-bold">+{{ $log->phone_number }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $log->type == 'login' ? 'info' : 'primary' }}">
                                        {{ ucfirst($log->type) }}
                                    </span>
                                </td>
                                <td>
                                    @if($log->is_verified)
                                        <span class="badge badge-success">{{ get_phrase('Verified') }}</span>
                                    @elseif($log->expires_at->isPast())
                                        <span class="badge badge-danger">{{ get_phrase('Expired') }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ get_phrase('Pending') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $log->created_at->format('Y-m-d H:i:s') }}</small><br>
                                    <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <small>{{ $log->expires_at->format('Y-m-d H:i:s') }}</small><br>
                                    @if($log->expires_at->isPast() && !$log->is_verified)
                                        <small class="text-danger">{{ $log->expires_at->diffForHumans() }}</small>
                                    @else
                                        <small class="text-muted">{{ $log->expires_at->diffForHumans() }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($log->is_verified)
                                        <small>{{ $log->updated_at->format('Y-m-d H:i:s') }}</small><br>
                                        <small class="text-muted">{{ $log->updated_at->diffForHumans() }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">{{ get_phrase('No OTP records found') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination --}}
                @if($otpLogs->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $otpLogs->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Recent Activity Chart --}}
@if($recentActivity->isNotEmpty())
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ get_phrase('Recent Activity (Last 7 Days)') }}</h5>
                <canvas id="otpChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
@endif

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Cleanup OTP function
function cleanupOTP(type) {
    let message = '';
    switch(type) {
        case 'expired':
            message = 'Clean all expired OTP records?';
            break;
        case 'verified':
            message = 'Clean all verified OTP records (older than 1 hour)?';
            break;
        case 'old':
            message = 'Clean all OTP records older than 7 days?';
            break;
        case 'all':
            message = 'WARNING: This will delete ALL OTP records. Are you sure?';
            break;
    }
    
    if (!confirm(message)) {
        return;
    }
    
    if (type === 'all' && !confirm('Type "DELETE ALL" to confirm:')) {
        return;
    }
    
    fetch('{{ route("admin.otp.clean") }}', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            clean_type: type,
            confirm: type === 'all' ? 'yes' : 'no'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}

// Export logs function
function exportLogs() {
    const params = new URLSearchParams(window.location.search);
    const exportUrl = '{{ route("admin.otp.export") }}?' + params.toString();
    window.location.href = exportUrl;
}

// Chart for recent activity
@if($recentActivity->isNotEmpty())
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('otpChart').getContext('2d');
    
    const chartData = {
        labels: [
            @foreach($recentActivity as $activity)
                '{{ \Carbon\Carbon::parse($activity->date)->format("M d") }}',
            @endforeach
        ],
        datasets: [
            {
                label: 'Total OTPs',
                data: [
                    @foreach($recentActivity as $activity)
                        {{ $activity->count }},
                    @endforeach
                ],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            },
            {
                label: 'Verified OTPs',
                data: [
                    @foreach($recentActivity as $activity)
                        {{ $activity->verified }},
                    @endforeach
                ],
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                tension: 0.1
            }
        ]
    };
    
    new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
@endif
</script>
@endpush

@endsection