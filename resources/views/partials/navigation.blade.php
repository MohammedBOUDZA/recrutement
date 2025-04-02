<nav class="bg-white shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex justify-between h-16">
            <div class="flex">
                <a href="{{ route('home') }}" class="flex items-center">
                    <span class="text-2xl font-bold text-blue-600">JobBoard</span>
                </a>
                
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center px-1 pt-1 text-gray-500 hover:text-gray-900">
                        Browse Jobs
                    </a>
                    @auth
                        @if(auth()->user()->role === 'entreprise')
                            <a href="{{ route('entreprise.dashboard') }}" 
                               class="inline-flex items-center px-1 pt-1 text-gray-500 hover:text-gray-900">
                                Dashboard
                            </a>
                        @endif
                        @if(auth()->user()->role === 'recruteur')
                            <a href="{{ route('recruteur.dashboard') }}" 
                               class="inline-flex items-center px-1 pt-1 text-gray-500 hover:text-gray-900">
                                Dashboard
                            </a>
                            <a href="{{ route('entreprise.applications') }}" 
                               class="inline-flex items-center px-1 pt-1 text-gray-500 hover:text-gray-900">
                                Applications
                            </a>
                            <a href="{{ route('emplois.create') }}" 
                               class="inline-flex items-center px-1 pt-1 text-gray-500 hover:text-gray-900">
                                Post Job
                            </a>
                        @endif
                        @if(auth()->user()->role === 'chercheur')
                            <a href="{{ route('chercheur.dashboard') }}" 
                               class="inline-flex items-center px-1 pt-1 text-gray-500 hover:text-gray-900">
                                Dashboard
                            </a>
                            <a href="{{ route('chercheur.applications') }}" 
                               class="inline-flex items-center px-1 pt-1 text-gray-500 hover:text-gray-900">
                                My Applications
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="flex items-center">
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-gray-700">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" 
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg">
                            <a href="{{ route('profile') }}" 
                               class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" 
                       class="text-gray-500 hover:text-gray-900 px-3 py-2">Login</a>
                    <a href="{{ route('register') }}" 
                       class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>