@extends('layouts.admin')

@section('title', 'Matching Intelligence Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4">
                <i class="fas fa-brain"></i>
                Matching Intelligence Dashboard
            </h1>
        </div>
    </div>

    <!-- Estadísticas principales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Mappings</h5>
                    <h2>{{ number_format($stats['mappings']['total_mappings']) }}</h2>
                    <small>{{ $stats['mappings']['automation_rate'] }}% automation</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Barcode Coverage</h5>
                    <h2>{{ $stats['mappings']['barcode_coverage'] }}%</h2>
                    <small>{{ number_format($stats['mappings']['with_barcode']) }} products</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Image Coverage</h5>
                    <h2>{{ $stats['mappings']['image_coverage'] }}%</h2>
                    <small>{{ number_format($stats['mappings']['with_image']) }} images</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Pending Review</h5>
                    <h2>{{ number_format($stats['failed']['pending_review']) }}</h2>
                    <small>{{ $stats['failed']['review_rate'] }}% reviewed</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos y detalles -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Match Status Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Confidence Score Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="confidenceChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('admin.matching.failed') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-exclamation-triangle"></i>
                                Review Failed Matches ({{ $stats['failed']['pending_review'] }})
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.matching.mappings') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-list"></i>
                                View All Mappings
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.matching.export') }}" class="btn btn-success btn-block">
                                <i class="fas fa-download"></i>
                                Export to CSV
                            </a>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-info btn-block" onclick="refreshStats()">
                                <i class="fas fa-sync"></i>
                                Refresh Stats
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actividad reciente -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Mappings</h5>
                </div>
                <div class="card-body">
                    @if($recentMappings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Brand</th>
                                        <th>Confidence</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentMappings as $mapping)
                                    <tr>
                                        <td>{{ Str::limit($mapping->ou_product_name, 30) }}</td>
                                        <td>{{ Str::limit($mapping->ou_brand_name, 20) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $mapping->confidence_score >= 80 ? 'success' : ($mapping->confidence_score >= 50 ? 'warning' : 'danger') }}">
                                                {{ $mapping->confidence_score }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $mapping->match_status === 'auto_matched' ? 'primary' : ($mapping->match_status === 'manual_verified' ? 'success' : 'secondary') }}">
                                                {{ $mapping->match_status }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No recent mappings found.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Failed Matches - Pending Review</h5>
                </div>
                <div class="card-body">
                    @if($pendingReviews->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Brand</th>
                                        <th>Best Score</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingReviews as $failed)
                                    <tr>
                                        <td>{{ Str::limit($failed->ou_product_name, 30) }}</td>
                                        <td>{{ Str::limit($failed->ou_brand_name, 20) }}</td>
                                        <td>
                                            <span class="badge badge-danger">
                                                {{ $failed->best_score }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.matching.failed-detail', $failed->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                Review
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($pendingReviews->count() >= 20)
                        <div class="text-center mt-2">
                            <a href="{{ route('admin.matching.failed') }}" class="btn btn-sm btn-outline-primary">
                                View All ({{ $stats['failed']['pending_review'] }})
                            </a>
                        </div>
                        @endif
                    @else
                        <p class="text-muted">No failed matches pending review.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.badge-success { background-color: #28a745; }
.badge-warning { background-color: #ffc107; color: #212529; }
.badge-danger { background-color: #dc3545; }
.badge-primary { background-color: #007bff; }
.badge-secondary { background-color: #6c757d; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Status Distribution Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Auto Matched', 'Manual Verified', 'Pending Review', 'Rejected'],
        datasets: [{
            data: [
                {{ $stats['mappings']['auto_matched'] }},
                {{ $stats['mappings']['manual_verified'] }},
                {{ $stats['mappings']['pending_review'] }},
                0 // Rejected would need to be calculated
            ],
            backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Confidence Distribution Chart
const confidenceCtx = document.getElementById('confidenceChart').getContext('2d');
new Chart(confidenceCtx, {
    type: 'bar',
    data: {
        labels: ['0-20', '21-40', '41-60', '61-80', '81-100'],
        datasets: [{
            label: 'Number of Mappings',
            data: [0, 0, 0, 0, 0], // This would need real data
            backgroundColor: '#007bff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

function refreshStats() {
    fetch('{{ route('admin.matching.api-stats') }}')
        .then(response => response.json())
        .then(data => {
            location.reload();
        });
}
</script>
@endpush
