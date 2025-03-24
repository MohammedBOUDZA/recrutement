@extends('layouts.app')

@section('title', 'Job Listings')

@section('content')
<div class="container mx-auto px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-6">Available Positions</h1>
        
        <form action="{{ route('emplois.index') }}" method="GET" 
              class="bg-white p-6 rounded-lg shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" 
                           name="search" 
                           placeholder="Job title or keywords"
                           value="{{ request('search') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" class="w-full rounded-lg border-gray-300 shadow-sm">
                        <option value="">All Types</option>
                        @foreach(['full-time', 'part-time', 'contract', 'internship'] as $type)
                            <option value="{{ $type }}" @selected(request('type') == $type)>
                                {{ Str::title($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input type="text" 
                           name="location" 
                           placeholder="City or region"
                           value="{{ request('location') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm">
                </div>
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        Search Jobs
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse($emplois as $job)
            @include('partials.job-card', ['job' => $job])
        @empty
            <div class="col-span-full text-center py-12">
                <h3 class="text-xl text-gray-500">No jobs found matching your criteria.</h3>
                <p class="mt-2 text-gray-400">Try adjusting your search filters</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $emplois->links() }}
    </div>
</div>
@endsection