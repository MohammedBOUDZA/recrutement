@extends('layouts.app')

@section('title', 'emplois')

@section('content')
    <h1>emploi Listings</h1>
    <div class="emploi-list">
        @foreach($emplois as $emploi)
            <div class="emploi-item">
                <h2>{{ $emploi->title }}</h2>
                <p>{{ $emploi->description }}</p>
                <p><strong>Location:</strong> {{ $emploi->location }}</p>
                <p><strong>Salary:</strong> ${{ number_format($emploi->salary, 2) }}</p>
                <a href="/emploisdetail/{{ $emploi->id }}" class="btn btn-primary">View Details</a>
            </div>
        @endforeach
    </div>
@endsection