@extends('layouts.app')

@section('title', $emploi->title)

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Job Details -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ $emploi->title }}
                        </h1>
                        <div class="mt-2">
                            <p class="text-lg text-gray-700">{{ $emploi->entreprise->company_name }}</p>
                            <div class="mt-1 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                {{ $emploi->location }}
                                @if($emploi->remote)
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Remote
                                    </span>
                                @endif
                                @if($emploi->hybrid)
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Hybrid
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if(auth()->check())
                            @if(auth()->user()->savedJobs->contains($emploi->id))
                                <form action="{{ route('emplois.unsave', $emploi) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/>
                                        </svg>
                                        Saved
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('emplois.save', $emploi) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                        </svg>
                                        Save Job
                                    </button>
                                </form>
                            @endif
                            @if(!auth()->user()->applications()->where('emplois_id', $emploi->id)->exists())
                                <a href="{{ route('emplois.apply', $emploi) }}" class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Apply Now
                                </a>
                            @else
                                <span class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100">
                                    Applied
                                </span>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Sign in to Apply
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="px-4 py-5 sm:px-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <!-- Job Description -->
                        <div class="prose max-w-none">
                            {!! nl2br(e($emploi->description)) !!}
                        </div>

                        <!-- Skills Required -->
                        @if($emploi->skills->isNotEmpty())
                            <div class="mt-8">
                                <h3 class="text-lg font-medium text-gray-900">Skills Required</h3>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($emploi->skills as $skill)
                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            {{ $skill->name }}
                                            @if($skill->pivot->level)
                                                - {{ ucfirst($skill->pivot->level) }}
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Categories -->
                        @if($emploi->categories->isNotEmpty())
                            <div class="mt-8">
                                <h3 class="text-lg font-medium text-gray-900">Job Categories</h3>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($emploi->categories as $category)
                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-6">
                        <!-- Job Details Card -->
                        <div class="bg-gray-50 shadow sm:rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-lg font-medium text-gray-900">Job Details</h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-500">Salary Range</div>
                                        <div class="mt-1 text-sm text-gray-900">{{ $emploi->salary_range }}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-500">Employment Type</div>
                                        <div class="mt-1 text-sm text-gray-900">{{ $emploi->employment_type_label }}</div>
                                    </div>
                                    @if($emploi->experience_required)
                                        <div>
                                            <div class="text-sm font-medium text-gray-500">Experience Required</div>
                                            <div class="mt-1 text-sm text-gray-900">{{ $emploi->experience_required }}</div>
                                        </div>
                                    @endif
                                    @if($emploi->education_required)
                                        <div>
                                            <div class="text-sm font-medium text-gray-500">Education Required</div>
                                            <div class="mt-1 text-sm text-gray-900">{{ $emploi->education_required }}</div>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-500">Posted</div>
                                        <div class="mt-1 text-sm text-gray-900">{{ $emploi->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Company Card -->
                        <div class="bg-gray-50 shadow sm:rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-lg font-medium text-gray-900">About the Company</h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-500">Company Name</div>
                                        <div class="mt-1 text-sm text-gray-900">{{ $emploi->entreprise->company_name }}</div>
                                    </div>
                                    @if($emploi->entreprise->website)
                                        <div>
                                            <div class="text-sm font-medium text-gray-500">Website</div>
                                            <div class="mt-1 text-sm text-gray-900">
                                                <a href="{{ $emploi->entreprise->website }}" target="_blank" class="text-blue-600 hover:text-blue-500">
                                                    {{ $emploi->entreprise->website }}
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Job Stats -->
                        <div class="bg-gray-50 shadow sm:rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-lg font-medium text-gray-900">Job Activity</h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-500">Views</div>
                                        <div class="mt-1 text-sm text-gray-900">{{ $emploi->views_count }}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-500">Applications</div>
                                        <div class="mt-1 text-sm text-gray-900">{{ $emploi->applications_count }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Similar Jobs -->
        @if(isset($similarJobs) && $similarJobs->isNotEmpty())
            <div class="mt-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Similar Jobs</h2>
                <div class="bg-white shadow overflow-hidden sm:rounded-md">
                    <ul role="list" class="divide-y divide-gray-200">
                        @foreach($similarJobs as $similarJob)
                            <li>
                                <a href="{{ route('emplois.show', $similarJob) }}" class="block hover:bg-gray-50">
                                    <div class="px-4 py-4 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-blue-600 truncate">
                                                    {{ $similarJob->title }}
                                                </p>
                                                <div class="mt-2 flex">
                                                    <div class="flex items-center text-sm text-gray-500">
                                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                                        </svg>
                                                        {{ $similarJob->location }}
                                                    </div>
                                                    <div class="ml-6 flex items-center text-sm text-gray-500">
                                                        {{ $similarJob->salary_range }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ml-4 flex-shrink-0">
                                                <span class="text-sm text-gray-500">
                                                    {{ $similarJob->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection