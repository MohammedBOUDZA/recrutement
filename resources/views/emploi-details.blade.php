@extends('layouts.app')

@section('title', 'emploi Details')

@section('content')
    <h1>{{ $emploi->title }}</h1>
    <p>{{ $emploi->description }}</p>
    <p><strong>Location:</strong> {{ $emploi->location }}</p>
    <p><strong>Salary:</strong> ${{ number_format($emploi->salary, 2) }}</p>
    <p><strong>emploi Type:</strong> {{ $emploi->emploi_type }}</p>
    @if(Auth::check())
    <form action="{{ url('/jobs/' . $emploi->id . '/uapplications') }}" method="POST">
        @csrf
        <button type="submit">Apply Now</button>
    </form>
    @else
        <p><a href="{{ url('/login') }}">Login</a> to apply</p>
    @endif

@endsection