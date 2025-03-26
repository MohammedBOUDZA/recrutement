@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Applications Received</h1>

        @if($applications->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <p class="text-gray-600">No applications received yet.</p>
                <a href="{{ route('entreprise.jobs.create') }}" 
                   class="mt-4 inline-block bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                    Post a New Job
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($applications as $application)
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">
                                    {{ $application->emploi->title }}
                                </h2>
                                <p class="text-gray-600">
                                    Applicant: {{ $application->chercheur->user->name }}
                                </p>
                                <div class="mt-2 text-sm text-gray-500">
                                    Applied {{ $application->created_at->diffForHumans() }}
                                </div>
                            </div>
                            
                            <form action="{{ route('applications.status.update', $application) }}" 
                                  method="POST" 
                                  class="flex items-center space-x-2">
                                @csrf
                                @method('PATCH')
                                <select name="status" 
                                        class="text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        onchange="this.form.submit()">
                                    <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="accepted" {{ $application->status === 'accepted' ? 'selected' : '' }}>
                                        Accept
                                    </option>
                                    <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>
                                        Reject
                                    </option>
                                </select>
                            </form>
                        </div>

                        @if($application->cover_letter)
                            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Cover Letter:</h3>
                                <p class="text-gray-600">{{ $application->cover_letter }}</p>
                            </div>
                        @endif

                        <div class="mt-4">
                            <a href="{{ Storage::url($application->chercheur->cv) }}" 
                               target="_blank"
                               class="inline-flex items-center text-sm text-blue-600 hover:text-blue-500">
                                <i class="fas fa-file-pdf mr-2"></i>
                                View CV
                            </a>
                        </div>
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