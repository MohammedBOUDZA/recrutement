@props(['notifications'])

<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-colors duration-200">
        <i class="fas fa-bell text-xl"></i>
        @if($notifications->where('read_at', null)->count() > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                {{ $notifications->where('read_at', null)->count() }}
            </span>
        @endif
    </button>

    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-lg overflow-hidden z-50">
        
        <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
            @if($notifications->where('read_at', null)->count() > 0)
                <form action="{{ route('notifications.mark-all-as-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs text-blue-600 hover:text-blue-800">
                        Mark all as read
                    </button>
                </form>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                <x-notification-item :notification="$notification" />
            @empty
                <div class="p-4 text-center text-gray-500">
                    <i class="fas fa-bell-slash text-2xl mb-2"></i>
                    <p>No notifications yet</p>
                </div>
            @endforelse
        </div>

        @if($notifications->count() > 0)
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 text-center">
                <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    View all notifications
                </a>
            </div>
        @endif
    </div>
</div> 