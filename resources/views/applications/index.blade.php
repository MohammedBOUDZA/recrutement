@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">My Applications</h1>

        @if($applications->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <p class="text-gray-600">You haven't applied to any jobs yet.</p>
                <a href="{{ route('emplois.index') }}" 
                   class="mt-4 inline-block bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                    Browse Jobs
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($applications as $application)
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-xl font-semibold">
                                    <a href="{{ route('emplois.show', $application->emploi) }}" 
                                       class="text-gray-900 hover:text-blue-600">
                                        {{ $application->emploi->title }}
                                    </a>
                                </h2>
                                <p class="text-gray-600">{{ $application->emploi->entreprise->company_name }}</p>
                                <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                    <span>
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        {{ $application->emploi->location }}
                                    </span>
                                    <span>
                                        <i class="fas fa-clock mr-1"></i>
                                        Applied {{ $application->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                            <span class="px-3 py-1 text-sm rounded-full font-medium capitalize
                                {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($application->status === 'accepted' ? 'bg-green-100 text-green-800' : 
                                   'bg-red-100 text-red-800') }}">
                                {{ $application->status }}
                            </span>
                        </div>

                        @if($application->cover_letter)
                            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Cover Letter:</h3>
                                <p class="text-gray-600 text-sm">{{ $application->cover_letter }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $applications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
