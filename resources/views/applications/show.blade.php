@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('applications.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Applications
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-2xl font-bold leading-6 text-gray-900">Application Details</h3>
            </div>

            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Job Details</h4>
                        <dl class="grid grid-cols-1 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Position</dt>
                                <dd class="mt-1 text-lg text-gray-900">{{ $application->emploi->title }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Company</dt>
                                <dd class="mt-1 text-gray-900">{{ $application->emploi->entreprise->company_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Location</dt>
                                <dd class="mt-1 text-gray-900">{{ $application->emploi->location }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Employment Type</dt>
                                <dd class="mt-1 text-gray-900">{{ $application->emploi->employment_type_label }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Posted</dt>
                                <dd class="mt-1 text-gray-900">{{ $application->emploi->created_at->format('M d, Y') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Applicant Information</h4>
                        <dl class="grid grid-cols-1 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="mt-1 text-gray-900">{{ $application->user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-gray-900">{{ $application->user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Applied</dt>
                                <dd class="mt-1 text-gray-900">{{ $application->created_at->diffForHumans() }}</dd>
                            </div>
                            @if($application->resume_path)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Resume</dt>
                                    <dd class="mt-1">
                                        <a href="{{ Storage::url($application->resume_path) }}" 
                                           class="inline-flex items-center text-sm text-blue-600 hover:text-blue-500"
                                           target="_blank">
                                            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            View Resume
                                        </a>
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <div class="mt-8">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Application Status</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-500 mr-2">Current Status:</span>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'reviewing' => 'bg-blue-100 text-blue-800',
                                        'interview_scheduled' => 'bg-purple-100 text-purple-800',
                                        'interviewed' => 'bg-indigo-100 text-indigo-800',
                                        'offered' => 'bg-green-100 text-green-800',
                                        'accepted' => 'bg-emerald-100 text-emerald-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'withdrawn' => 'bg-gray-100 text-gray-800'
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Pending Review',
                                        'reviewing' => 'Under Review',
                                        'interview_scheduled' => 'Interview Scheduled',
                                        'interviewed' => 'Interviewed',
                                        'offered' => 'Job Offered',
                                        'accepted' => 'Offer Accepted',
                                        'rejected' => 'Not Selected',
                                        'withdrawn' => 'Application Withdrawn'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$application->status] }}">
                                    {{ $statusLabels[$application->status] }}
                                </span>
                            </div>
                            @if(auth()->user()->role === 'recruteur' && $application->emploi->entreprise_id === auth()->user()->entreprise_id)
                                <div class="flex items-center space-x-4">
                                    <form action="{{ route('applications.update-status', $application) }}" method="POST" class="flex items-center">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" 
                                                class="mr-2 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                            @foreach($statusLabels as $value => $label)
                                                <option value="{{ $value }}" {{ $application->status === $value ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Update Status
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        @if($application->status_updated_at)
                            <p class="mt-2 text-sm text-gray-500">
                                Last updated {{ $application->status_updated_at->diffForHumans() }}
                            </p>
                        @endif
                    </div>
                </div>

                @if($application->status_history && count($application->status_history) > 0)
                    <div class="mt-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Status History</h4>
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach($application->status_history as $history)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white bg-gray-100">
                                                        @switch($history['status'])
                                                            @case('pending')
                                                                <svg class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                                </svg>
                                                                @break
                                                            @case('reviewing')
                                                                <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                                                </svg>
                                                                @break
                                                            @case('interview_scheduled')
                                                                <svg class="h-5 w-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                                                </svg>
                                                                @break
                                                            @case('interviewed')
                                                                <svg class="h-5 w-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                </svg>
                                                                @break
                                                            @case('offered')
                                                                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                </svg>
                                                                @break
                                                            @case('accepted')
                                                                <svg class="h-5 w-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                </svg>
                                                                @break
                                                            @case('rejected')
                                                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                                </svg>
                                                                @break
                                                            @default
                                                                <svg class="h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                                                </svg>
                                                        @endswitch
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Status changed to <span class="font-medium text-gray-900">{{ $statusLabels[$history['status']] }}</span></p>
                                                        @if($history['note'])
                                                            <p class="mt-1 text-sm text-gray-500">{{ $history['note'] }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        <time datetime="{{ $history['date'] }}">{{ \Carbon\Carbon::parse($history['date'])->diffForHumans() }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="mt-8">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Cover Letter</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="prose max-w-none">
                            {!! nl2br(e($application->cover_letter)) !!}
                        </div>
                    </div>
                </div>

                @if(auth()->user()->role === 'recruteur' && $application->emploi->entreprise_id === auth()->user()->entreprise_id)
                    <div class="mt-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Internal Notes</h4>
                        <form action="{{ route('applications.add-note', $application) }}" method="POST">
                            @csrf
                            <div>
                                <label for="note" class="sr-only">Add note</label>
                                <textarea id="note" name="note" rows="3" 
                                        class="shadow-sm block w-full focus:ring-blue-500 focus:border-blue-500 sm:text-sm border border-gray-300 rounded-md"
                                        placeholder="Add a note about this application..."></textarea>
                            </div>
                            <div class="mt-3">
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Add Note
                                </button>
                            </div>
                        </form>

                        @if($application->notes && count($application->notes) > 0)
                            <div class="mt-4 space-y-4">
                                @foreach($application->notes as $note)
                                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                        <div class="px-4 py-5 sm:p-6">
                                            <div class="text-sm text-gray-900">{{ $note['content'] }}</div>
                                            <div class="mt-2 text-sm text-gray-500">
                                                Added by {{ $note['user_name'] }} • {{ \Carbon\Carbon::parse($note['date'])->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 