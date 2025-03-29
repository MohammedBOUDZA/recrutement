@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Profile Card -->
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
                                        <i class="fas fa-phone text-blue-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Phone Number</p>
                                        <p class="font-medium text-gray-800">{{ $profile->phone ?? 'Not set' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-envelope text-blue-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Email</p>
                                        <p class="font-medium text-gray-800">{{ $user->email }}</p>
                                    </div>
                                </div>
                                @if($user->role === 'chercheur')
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-blue-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Location</p>
                                        <p class="font-medium text-gray-800">{{ $profile->location ?? 'Not set' }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="mt-6 space-y-3">
                                <button class="w-full px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                    <i class="fas fa-key"></i>
                                    <span>Change Password</span>
                                </button>
                                @if($user->role === 'chercheur')
                                <div class="flex items-center justify-between px-4 py-3 bg-gray-50 rounded-lg">
                                    <label for="smsAlerts" class="text-sm text-gray-700">SMS Alerts</label>
                                    <div class="relative inline-block w-12 mr-2 align-middle select-none">
                                        <input type="checkbox" id="smsAlerts" {{ $profile->sms_alerts ? 'checked' : '' }}
                                               class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"/>
                                        <label for="smsAlerts"
                                               class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-8 space-y-8">
                    @if($user->role === 'chercheur')
                        <!-- Account Status -->
                        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-gray-800">Account Overview</h2>
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Active</span>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                                            <i class="fas fa-bookmark text-2xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-3xl font-bold mb-1">{{ $user->savedJobs()->count() }}</h3>
                                            <p class="text-blue-100">Saved Jobs</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                                            <i class="fas fa-paper-plane text-2xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-3xl font-bold mb-1">{{ $user->applications()->count() }}</h3>
                                            <p class="text-green-100">Applications</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                                            <i class="fas fa-eye text-2xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-3xl font-bold mb-1">{{ $profile->views ?? 0 }}</h3>
                                            <p class="text-purple-100">Profile Views</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Applications -->
                        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-gray-800">Recent Applications</h2>
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="px-4 py-2 text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200 flex items-center space-x-2">
                                        <span>Filter by</span>
                                        <i class="fas fa-chevron-down text-sm"></i>
                                    </button>
                                    <div x-show="open" @click.away="open = false" 
                                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-10">
                                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">All</a>
                                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Pending</a>
                                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Accepted</a>
                                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Rejected</a>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                @forelse($user->applications()->latest()->take(5)->get() as $application)
                                    <div class="group p-4 rounded-xl border border-gray-100 hover:border-blue-100 hover:bg-blue-50 transition-all duration-200">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <h3 class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors duration-200">
                                                    {{ $application->emploi->title }}
                                                </h3>
                                                <p class="text-sm text-gray-500">{{ $application->emploi->entreprise->company_name }}</p>
                                            </div>
                                            <span class="px-3 py-1 rounded-full text-sm font-medium 
                                                {{ $application->status === 'submitted' ? 'bg-blue-100 text-blue-800' : 
                                                   ($application->status === 'reviewing' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($application->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                                   'bg-green-100 text-green-800')) }}">
                                                {{ ucfirst($application->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-12">
                                        <img src="{{ asset('images/no-data.svg') }}" alt="No applications" class="w-32 h-32 mx-auto mb-4 opacity-75">
                                        <p class="text-gray-500">No applications yet</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @else
                        <!-- Employer Stats -->
                        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-gray-800">Company Overview</h2>
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Active</span>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                                            <i class="fas fa-briefcase text-2xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-3xl font-bold mb-1">{{ $user->entreprise ? $user->entreprise->emplois()->count() : 0 }}</h3>
                                            <p class="text-blue-100">Active Jobs</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                                            <i class="fas fa-users text-2xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-3xl font-bold mb-1">{{ $user->entreprise ? $user->entreprise->applications()->count() : 0 }}</h3>
                                            <p class="text-green-100">Total Applications</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                                            <i class="fas fa-eye text-2xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-3xl font-bold mb-1">{{ $user->entreprise ? ($user->entreprise->total_views ?? 0) : 0 }}</h3>
                                            <p class="text-purple-100">Total Views</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(!$user->entreprise)
                            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6">
                                <div class="text-center py-8">
                                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-building text-3xl text-blue-500"></i>
                                    </div>
                                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Complete Your Company Profile</h2>
                                    <p class="text-gray-500 mb-6 max-w-md mx-auto">Set up your company profile to start posting jobs and connecting with talented candidates.</p>
                                    <a href="{{ route('entreprise.setup') }}" 
                                       class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                                        <i class="fas fa-plus-circle mr-2"></i>
                                        Set Up Company Profile
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6">
                                <div class="flex justify-between items-center mb-6">
                                    <h2 class="text-xl font-bold text-gray-800">Recent Job Posts</h2>
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" class="px-4 py-2 text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200 flex items-center space-x-2">
                                            <span>Filter by</span>
                                            <i class="fas fa-chevron-down text-sm"></i>
                                        </button>
                                        <div x-show="open" @click.away="open = false" 
                                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-10">
                                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">All</a>
                                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Active</a>
                                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Expired</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    @forelse($user->entreprise->emplois()->latest()->take(5)->get() as $job)
                                        <div class="group p-4 rounded-xl border border-gray-100 hover:border-blue-100 hover:bg-blue-50 transition-all duration-200">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <h3 class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors duration-200">
                                                        {{ $job->title }}
                                                    </h3>
                                                    <div class="flex items-center space-x-4 mt-1">
                                                        <span class="text-sm text-gray-500">
                                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                                            {{ $job->location }}
                                                        </span>
                                                        <span class="text-sm text-gray-500">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            {{ $job->created_at->diffForHumans() }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $job->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($job->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-12">
                                            <img src="{{ asset('images/no-data.svg') }}" alt="No jobs" class="w-32 h-32 mx-auto mb-4 opacity-75">
                                            <p class="text-gray-500">No job posts yet</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.toggle-checkbox:checked {
    @apply: right-0 border-blue-600;
    right: 0;
    border-color: #2563eb;
}
.toggle-checkbox:checked + .toggle-label {
    @apply: bg-blue-600;
    background-color: #2563eb;
}
.toggle-label {
    @apply: block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer;
}
</style>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-gradient">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-4">
                        <label for="cv" class="form-label fw-bold">CV (PDF)</label>
                        <div class="input-group">
                            <input type="file" name="cv" id="cv" class="form-control @error('cv') is-invalid @enderror" accept=".pdf">
                            <button class="btn btn-outline-secondary" type="button" id="clearCv">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        @error('cv')
                            <div class="invalid-feedback">{{ $errors->first('cv') }}</div>
                        @enderror
                        <small class="text-muted">Upload your CV in PDF format (max 5MB)</small>
                    </div>

                    <div class="mb-4">
                        <label for="skills" class="form-label fw-bold">Skills</label>
                        <textarea name="skills" id="skills" rows="3" class="form-control @error('skills') is-invalid @enderror" placeholder="Enter your skills separated by commas">{{ old('skills', $profile->skills ?? '') }}</textarea>
                        @error('skills')
                            <div class="invalid-feedback">{{ $errors->first('skills') }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="experience" class="form-label fw-bold">Experience</label>
                        <textarea name="experience" id="experience" rows="4" class="form-control @error('experience') is-invalid @enderror" placeholder="Describe your work experience">{{ old('experience', $profile->experience ?? '') }}</textarea>
                        @error('experience')
                            <div class="invalid-feedback">{{ $errors->first('experience') }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="education" class="form-label fw-bold">Education</label>
                        <textarea name="education" id="education" rows="4" class="form-control @error('education') is-invalid @enderror" placeholder="Describe your educational background">{{ old('education', $profile->education ?? '') }}</textarea>
                        @error('education')
                            <div class="invalid-feedback">{{ $errors->first('education') }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary hover-scale">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-gradient">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-4">
                        <label for="current_password" class="form-label fw-bold">Current Password</label>
                        <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $errors->first('current_password') }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-bold">New Password</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                        @enderror
                        <small class="text-muted">Password must be at least 8 characters long and contain at least one number.</small>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-bold">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary hover-scale">Change Password</button>
                </div>
            </form>
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

.profile-photo-wrapper {
    position: relative;
    display: inline-block;
}

.profile-photo-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.profile-photo-wrapper:hover .profile-photo-overlay {
    opacity: 1;
}

.profile-photo-overlay i {
    font-size: 2rem;
}

.transition-all {
    transition: all 0.3s ease;
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

.badge {
    border-radius: 0.5rem;
    font-weight: 500;
}

.alert {
    border-radius: 0.5rem;
    border: none;
}

.list-group-item {
    border-radius: 0.5rem;
}

.input-group .btn {
    border-radius: 0 0.5rem 0.5rem 0;
}

.input-group .form-control {
    border-radius: 0.5rem 0 0 0.5rem;
}

.rounded-4 {
    border-radius: 1rem !important;
}

.stat-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon i {
    font-size: 1.2rem;
}

.application-item, .job-item {
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05);
}

.application-item:hover, .job-item:hover {
    background-color: rgba(0,0,0,0.02);
    transform: translateX(5px);
}

.stat-card {
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.profile-photo {
    border: 4px solid #fff;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Clear CV input
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