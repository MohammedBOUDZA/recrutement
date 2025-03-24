@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="bg-blue-600 text-white py-16">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold mb-4">Find Your Dream Job</h1>
        <div class="max-w-2xl">
            <form action="{{ route('emplois.index') }}" method="GET" class="flex gap-4">
                <input type="text" 
                       name="search" 
                       placeholder="Job title or keywords"
                       class="flex-1 rounded px-4 py-2 text-gray-800">
                <button type="submit" class="bg-white text-blue-600 px-6 py-2 rounded hover:bg-blue-50">
                    Search
                </button>
            </form>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">Latest Job Postings</h2>
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach($latestJobs as $job)
            @include('partials.job-card', ['job' => $job])
        @endforeach
    </div>
</div>
@endsection