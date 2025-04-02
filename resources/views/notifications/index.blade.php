@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Notifications</h2>
                    @if($notifications->where('read_at', null)->count() > 0)
                        <form action="{{ route('notifications.mark-all-as-read') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">
                                Mark all as read
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($notifications as $notification)
                    <x-notification-item :notification="$notification" />
                @empty
                    <div class="p-6 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-500 mb-4">
                            <i class="fas fa-bell-slash text-2xl"></i>
                        </div>
                        <p class="text-gray-500">No notifications yet</p>
                    </div>
                @endforelse
            </div>

            @if($notifications->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 