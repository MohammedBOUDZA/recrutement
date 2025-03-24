@extends('layouts.app')

@section('title', 'Job Listings')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($jobs as $job)
            @include('partials.job-card', ['job' => $job])
        @endforeach
    </div>
    
    <div class="mt-8">
        {{ $jobs->links() }}
    </div>
</div>
@endsection