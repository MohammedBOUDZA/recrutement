@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-gray-800">My Profile</h2>
                                <button class="p-2 text-gray-500 hover:text-blue-600 rounded-full hover:bg-blue-50 transition-colors duration-200" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                            </div>
                            
                            <div class="text-center mb-6">
                                <div class="relative inline-block">
                                    <div class="w-32 h-32 mx-auto mb-4 relative">
                                        <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                                             alt="{{ $user->name }}" 
                                             class="w-full h-full object-cover rounded-2xl shadow-sm">
                                        <div class="absolute inset-0 rounded-2xl bg-black opacity-0 hover:opacity-10 transition-opacity duration-200"></div>
                                    </div>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-500 mb-2">Last active: {{ auth()->user()->last_login_at?->diffForHumans() ?? 'Never' }}</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $user->role === 'chercheur' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>

                            <div class="border-t border-gray-100 pt-6 space-y-4">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-envelope text-blue-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Email</p>
                                        <p class="font-medium text-gray-800">{{ $user->email }}</p>
                                    </div>
                                </div>
                                @if($user->role === 'recruteur' && $user->entreprise)
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-building text-blue-500"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Company</p>
                                            <p class="font-medium text-gray-800">{{ $user->entreprise->company_name }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-industry text-blue-500"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Industry</p>
                                            <p class="font-medium text-gray-800">{{ $user->entreprise->industry }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6">
                                <button class="w-full px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                    <i class="fas fa-key"></i>
                                    <span>Change Password</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-8">
                    @if($user->role === 'chercheur')
                        @include('emplois.partials.profile-jobseeker')
                    @else
                        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 mb-8">
                            <h2 class="text-xl font-bold text-gray-800 mb-6">Recruitment Overview</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-blue-50 rounded-xl p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm text-blue-600">Active Jobs</p>
                                            <h3 class="text-2xl font-bold text-blue-800">
                                                {{ App\Models\Emploi::where('entreprise_id', $user->entreprise->id)
                                                    ->where('status', 'active')
                                                    ->count() }}
                                            </h3>
                                        </div>
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-briefcase text-blue-500"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-green-50 rounded-xl p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm text-green-600">Total Applications</p>
                                            <h3 class="text-2xl font-bold text-green-800">
                                                {{ App\Models\Application::whereHas('emploi', function($query) use ($user) {
                                                    $query->where('entreprise_id', $user->entreprise->id);
                                                })->count() }}
                                            </h3>
                                        </div>
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-users text-green-500"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-yellow-50 rounded-xl p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm text-yellow-600">Pending Reviews</p>
                                            <h3 class="text-2xl font-bold text-yellow-800">
                                                {{ App\Models\Application::whereHas('emploi', function($query) use ($user) {
                                                    $query->where('entreprise_id', $user->entreprise->id);
                                                })->where('status', 'pending')->count() }}
                                            </h3>
                                        </div>
                                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-clock text-yellow-500"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 mb-8">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-gray-800">Recent Job Postings</h2>
                                <a href="{{ route('emplois.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                    <i class="fas fa-plus mr-2"></i> Post New Job
                                </a>
                            </div>
                            <div class="space-y-4">
                                @forelse($user->entreprise->emplois()->latest()->take(5)->get() as $job)
                                    <div class="group p-4 rounded-xl border border-gray-100 hover:border-blue-100 hover:bg-blue-50 transition-all duration-200">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors duration-200">
                                                    {{ $job->title }}
                                                </h3>
                                                <p class="text-sm text-gray-500">
                                                    <i class="fas fa-map-marker-alt mr-1"></i> {{ $job->location }}
                                                </p>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    <i class="fas fa-clock mr-1"></i> Posted {{ $job->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $job->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($job->status) }}
                                                </span>
                                                <p class="text-sm text-gray-500 mt-2">
                                                    {{ $job->applications->count() }} applications
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-12">
                                        <img src="{{ asset('images/no-data.svg') }}" alt="No jobs" class="w-32 h-32 mx-auto mb-4 opacity-75">
                                        <p class="text-gray-500">No job postings yet</p>
                                        <a href="{{ route('emplois.create') }}" class="mt-4 inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                            Post Your First Job
                                        </a>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-gray-800">Recent Applications</h2>
                                <a href="{{ route('applications.index') }}" class="text-blue-600 hover:text-blue-800">
                                    View All <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                            <div class="space-y-4">
                                @php
                                    $recentApplications = App\Models\Application::whereHas('emploi', function($query) use ($user) {
                                        $query->where('entreprise_id', $user->entreprise->id);
                                    })->with(['emploi', 'user'])->latest()->take(5)->get();
                                @endphp
                                
                                @forelse($recentApplications as $application)
                                    <div class="group p-4 rounded-xl border border-gray-100 hover:border-blue-100 hover:bg-blue-50 transition-all duration-200">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors duration-200">
                                                    {{ $application->user->name }}
                                                </h3>
                                                <p class="text-sm text-gray-500">
                                                    Applied for: {{ $application->emploi->title }}
                                                </p>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    <i class="fas fa-clock mr-1"></i> {{ $application->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="px-3 py-1 rounded-full text-sm font-medium 
                                                    {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                       ($application->status === 'reviewing' ? 'bg-blue-100 text-blue-800' : 
                                                       ($application->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                                       'bg-green-100 text-green-800')) }}">
                                                    {{ ucfirst($application->status) }}
                                                </span>
                                                <a href="{{ route('applications.show', $application) }}" class="block mt-2 text-sm text-blue-600 hover:text-blue-800">
                                                    Review Application
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-12">
                                        <img src="{{ asset('images/no-data.svg') }}" alt="No applications" class="w-32 h-32 mx-auto mb-4 opacity-75">
                                        <p class="text-gray-500">No applications received yet</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


<style>
.text-gradient {
    background: linear-gradient(45deg, #4e73df, #224abe);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.hover-scale {
    transition: all 0.3s ease;
}

.hover-scale:hover {
    transform: scale(1.05);
}

.hover-bg-light {
    transition: all 0.3s ease;
}

.hover-bg-light:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.modal-content {
    border-radius: 1rem;
}

.modal-header {
    padding: 1.5rem;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    padding: 1.5rem;
}

.form-control {
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    border: 1px solid #e3e6f0;
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.btn {
    border-radius: 0.5rem;
    padding: 0.75rem 1.5rem;
}

.input-group .btn {
    border-radius: 0 0.5rem 0.5rem 0;
}

.input-group .form-control {
    border-radius: 0.5rem 0 0 0.5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clearCvBtn = document.getElementById('clearCv');
    const cvInput = document.getElementById('cv');
    
    if (clearCvBtn && cvInput) {
        clearCvBtn.addEventListener('click', function() {
            cvInput.value = '';
        });
    }
});
</script>
@endsection