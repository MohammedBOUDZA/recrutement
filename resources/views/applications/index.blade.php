@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">
                {{ auth()->user()->role === 'recruteur' ? 'Manage Applications' : 'My Applications' }}
            </h1>
        </div>

        @if($applications->isEmpty())
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ auth()->user()->role === 'recruteur' ? 'No applications received yet' : 'You haven\'t applied to any jobs yet' }}
                    </h3>
                    <div class="mt-2 text-sm text-gray-500">
                        @if(auth()->user()->role === 'recruteur')
                            <p>When candidates apply to your job postings, they will appear here.</p>
                        @else
                            <p>Start exploring jobs and submit your applications to find your next opportunity!</p>
                            <div class="mt-4">
                                <a href="{{ route('emplois.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                    Browse Jobs
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul role="list" class="divide-y divide-gray-200">
                    @foreach($applications as $application)
                        <li>
                            <a href="{{ route('applications.show', $application) }}" class="block hover:bg-gray-50">
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-blue-600 truncate">
                                                {{ $application->emploi->title }}
                                            </p>
                                            <p class="mt-1 flex items-center text-sm text-gray-500">
                                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                                {{ $application->emploi->entreprise->company_name }}
                                            </p>
                                        </div>
                                        <div class="flex flex-col items-end">
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
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$application->status] }}">
                                                {{ $statusLabels[$application->status] }}
                                            </span>
                                            <p class="mt-2 text-sm text-gray-500">
                                                Applied {{ $application->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                {{ $application->emploi->location }}
                                            </p>
                                            <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                                {{ $application->emploi->employment_type_label }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="mt-4">
                {{ $applications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
