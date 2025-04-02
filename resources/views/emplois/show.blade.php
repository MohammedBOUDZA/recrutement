@extends('layouts.app')

@section('title', $emploi->title)

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('emplois.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Jobs
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ $emploi->title }}
                            @if($emploi->urgent)
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Urgent
                                </span>
                            @endif
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
                        @auth
                            @if(auth()->user()->role === 'chercheur')
                                @if(auth()->user()->savedJobs->contains($emploi->id))
                                    <form action="{{ route('emplois.unsave', $emploi) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/>
                                            </svg>
                                            Saved
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('emplois.save', $emploi) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                            </svg>
                                            Save Job
                                        </button>
                                    </form>
                                @endif

                                @if(!auth()->user()->applications()->where('emploi_id', $emploi->id)->exists())
                                    <button onclick="document.getElementById('applicationModal').classList.remove('hidden')" 
                                            class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        Apply Now
                                    </button>
                                @else
                                    <span class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Applied
                                    </span>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                Sign in to Apply
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <div class="px-4 py-5 sm:px-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <div class="prose max-w-none">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Job Description</h3>
                            {!! nl2br(e($emploi->description)) !!}
                        </div>

                        @if($emploi->requirements)
                            <div class="mt-8 prose max-w-none">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Requirements</h3>
                                {!! nl2br(e($emploi->requirements)) !!}
                            </div>
                        @endif

                        @if($emploi->benefits)
                            <div class="mt-8 prose max-w-none">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Benefits</h3>
                                {!! nl2br(e($emploi->benefits)) !!}
                            </div>
                        @endif

                        @if($emploi->skills->isNotEmpty())
                            <div class="mt-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Skills Required</h3>
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

                        @if($emploi->categories->isNotEmpty())
                            <div class="mt-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Job Categories</h3>
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
                                    <div>
                                        <div class="text-sm font-medium text-gray-500">Expires</div>
                                        <div class="mt-1 text-sm text-gray-900">{{ $emploi->expires_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                                <a href="{{ $emploi->entreprise->website }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-500">
                                                    {{ $emploi->entreprise->website }}
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                    @if($emploi->entreprise->description)
                                        <div>
                                            <div class="text-sm font-medium text-gray-500">About</div>
                                            <div class="mt-1 text-sm text-gray-900">
                                                {{ $emploi->entreprise->description }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

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

@auth
    @if(auth()->user()->role === 'chercheur')
        <div id="applicationModal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('applications.store', $emploi) }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                        Apply for {{ $emploi->title }}
                                    </h3>
                                    <div class="mt-4">
                                        <label for="cover_letter" class="block text-sm font-medium text-gray-700">
                                            Cover Letter
                                        </label>
                                        <div class="mt-1">
                                            <textarea id="cover_letter" name="cover_letter" rows="6" 
                                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md @error('cover_letter') border-red-500 @enderror"
                                                    placeholder="Tell us why you're interested in this position and what makes you a great fit...">{{ old('cover_letter') }}</textarea>
                                            @error('cover_letter')
                                                <p class="mt-1 text-sm text-red-500">{{ $errors->first('cover_letter') }}</p>
                                            @enderror
                                        </div>
                                        <p class="mt-2 text-sm text-gray-500">
                                            A well-written cover letter can help you stand out from other candidates.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Submit Application
                            </button>
                            <button type="button" onclick="document.getElementById('applicationModal').classList.add('hidden')"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endauth
@endsection