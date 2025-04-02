@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Total Users</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['total_users'] }}</p>
            <p class="text-sm text-gray-600">{{ $stats['new_users_today'] }} new today</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Total Jobs</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['total_jobs'] }}</p>
            <p class="text-sm text-gray-600">{{ $stats['active_jobs'] }} active</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Total Applications</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['total_applications'] }}</p>
            <p class="text-sm text-gray-600">{{ $stats['new_applications_today'] }} new today</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Total Companies</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['total_companies'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">User Registration Trend</h3>
            <canvas id="userTrendChart" width="400" height="200"></canvas>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Application Trend</h3>
            <canvas id="applicationTrendChart" width="400" height="200"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">User Distribution by Role</h3>
            <canvas id="userRolesChart" width="400" height="200"></canvas>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Top Job Categories</h3>
            <canvas id="categoriesChart" width="400" height="200"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('userTrendChart'), {
        type: 'line',
        data: {
            labels: @json($userTrend->pluck('date')),
            datasets: [{
                label: 'New Users',
                data: @json($userTrend->pluck('count')),
                borderColor: 'rgb(59, 130, 246)',
                tension: 0.1
            }]
        }
    });

    new Chart(document.getElementById('applicationTrendChart'), {
        type: 'line',
        data: {
            labels: @json($applicationTrend->pluck('date')),
            datasets: [{
                label: 'New Applications',
                data: @json($applicationTrend->pluck('count')),
                borderColor: 'rgb(16, 185, 129)',
                tension: 0.1
            }]
        }
    });

    new Chart(document.getElementById('userRolesChart'), {
        type: 'doughnut',
        data: {
            labels: @json($usersByRole->pluck('role')),
            datasets: [{
                data: @json($usersByRole->pluck('count')),
                backgroundColor: [
                    'rgb(59, 130, 246)',
                    'rgb(16, 185, 129)',
                    'rgb(245, 158, 11)'
                ]
            }]
        }
    });

    new Chart(document.getElementById('categoriesChart'), {
        type: 'bar',
        data: {
            labels: @json($topCategories->pluck('category')),
            datasets: [{
                label: 'Jobs per Category',
                data: @json($topCategories->pluck('count')),
                backgroundColor: 'rgb(59, 130, 246)'
            }]
        }
    });
</script>
@endpush
@endsection