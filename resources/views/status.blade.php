@extends('layouts.app')
@section('title', 'emplois')
@section('content')
    @foreach(auth()->user()->jobApplications as $application)
        <p>You applied for {{ $application->job->title }} - Status: {{ ucfirst($application->status) }}</p>
    @endforeach

@endsection 