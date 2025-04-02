<div class="flex items-center space-x-4">
    @auth
        @if(auth()->user()->role === 'chercheur')
            <x-notifications-dropdown :notifications="App\Models\Notification::where('user_id', auth()->id())->latest()->take(5)->get()" />
        @endif
        
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                <img src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" 
                     alt="{{ auth()->user()->name }}" 
                     class="w-8 h-8 rounded-full">
                <span>{{ auth()->user()->name }}</span>
                <i class="fas fa-chevron-down text-xs"></i>
            </button>

            <div x-show="open" 
                 @click.away="open = false"
                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg overflow-hidden z-50">
                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-user mr-2"></i> Profile
                </a>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-chart-line mr-2"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.users') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-users mr-2"></i> Manage Users
                    </a>
                    <a href="{{ route('admin.statistics') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-chart-bar mr-2"></i> Statistics
                    </a>
                @elseif(auth()->user()->role === 'chercheur')
                    <a href="{{ route('applications.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-paper-plane mr-2"></i> My Applications
                    </a>
                    <a href="{{ route('saved-jobs') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-bookmark mr-2"></i> Saved Jobs
                    </a>
                @else
                    <a href="{{ route('emplois.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-plus mr-2"></i> Post Job
                    </a>
                    <a href="{{ route('applications.company') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-users mr-2"></i> Manage Applications
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    @else
        <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Login</a>
        <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Register</a>
    @endauth
</div> 