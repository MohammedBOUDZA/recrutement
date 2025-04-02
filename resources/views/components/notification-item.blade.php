@props(['notification'])

<div class="notification-item p-4 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }} hover:bg-gray-50 border-b border-gray-100 transition-colors duration-200">
    <div class="flex items-start space-x-4">
        <div class="flex-shrink-0">
            @if($notification->status === 'status_update')
                @php
                    $statusColors = [
                        'reviewing' => 'text-blue-500 bg-blue-100',
                        'interview_scheduled' => 'text-purple-500 bg-purple-100',
                        'interviewed' => 'text-indigo-500 bg-indigo-100',
                        'offered' => 'text-green-500 bg-green-100',
                        'accepted' => 'text-emerald-500 bg-emerald-100',
                        'rejected' => 'text-red-500 bg-red-100',
                        'withdrawn' => 'text-gray-500 bg-gray-100',
                    ];
                    $color = $statusColors[$notification->data['new_status']] ?? 'text-blue-500 bg-blue-100';
                @endphp
                <div class="w-10 h-10 rounded-full {{ $color }} flex items-center justify-center">
                    <i class="fas fa-clipboard-check"></i>
                </div>
            @elseif($notification->status === 'new_application')
                <div class="w-10 h-10 rounded-full text-green-500 bg-green-100 flex items-center justify-center">
                    <i class="fas fa-paper-plane"></i>
                </div>
            @else
                <div class="w-10 h-10 rounded-full text-gray-500 bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-bell"></i>
                </div>
            @endif
        </div>

        <div class="flex-1 min-w-0">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-900">
                        {{ $notification->message }}
                    </p>
                    @if($notification->status === 'status_update')
                        <p class="mt-1 text-xs text-gray-500">
                            Status changed by {{ $notification->data['updated_by'] }} from {{ ucfirst($notification->data['old_status']) }} to {{ ucfirst($notification->data['new_status']) }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $notification->data['company_name'] }} - {{ $notification->data['job_title'] }}
                        </p>
                    @endif
                </div>
                <span class="text-xs text-gray-500 whitespace-nowrap ml-4">
                    {{ $notification->created_at->diffForHumans() }}
                </span>
            </div>

            @if($notification->status === 'status_update' && isset($notification->data['new_status']))
                <div class="mt-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $notification->data['new_status'] === 'reviewing' ? 'bg-blue-100 text-blue-800' :
                           ($notification->data['new_status'] === 'interview_scheduled' ? 'bg-purple-100 text-purple-800' :
                           ($notification->data['new_status'] === 'interviewed' ? 'bg-indigo-100 text-indigo-800' :
                           ($notification->data['new_status'] === 'offered' ? 'bg-green-100 text-green-800' :
                           ($notification->data['new_status'] === 'accepted' ? 'bg-emerald-100 text-emerald-800' :
                           ($notification->data['new_status'] === 'rejected' ? 'bg-red-100 text-red-800' :
                           'bg-gray-100 text-gray-800'))))) }}">
                        {{ ucfirst($notification->data['new_status']) }}
                    </span>
                </div>
            @endif

            @if(!$notification->read_at)
                <div class="mt-2">
                    <form action="{{ route('notifications.mark-as-read', $notification) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs text-blue-600 hover:text-blue-800">
                            Mark as read
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div> 