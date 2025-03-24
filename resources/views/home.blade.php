@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <h1>Welcome to Our Recruitment Website</h1>
    
    <p>Find your dream emploi or post a emploi opening.</p>
    <a href="/emplois" class="btn btn-primary">Browse emplois</a>
    @if(session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif

    <p>You are logged in!</p>
@endsection