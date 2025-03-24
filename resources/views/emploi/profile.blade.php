@extends('layouts.app')

@section('title', 'Profile')

@section('content')
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mt-5">
                <div class="card shadow">
                    <div class="card-body">
                        <h1 class="card-title text-center mb-4">Profile</h1>

                        <!-- Display Profile Information -->
                        @if($profile)
                            <div class="mb-3">
                                <h4>Resume:</h4>
                                <p>{{ $profile->resume }}</p>
                            </div>
                            <div class="mb-3">
                                <h4>Skills:</h4>
                                <p>{{ $profile->skills }}</p>
                            </div>
                            <div class="mb-3">
                                <h4>Experience:</h4>
                                <p>{{ $profile->experience }}</p>
                            </div>
                            <div class="mb-3">
                                <h4>Education:</h4>
                                <p>{{ $profile->education }}</p>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                No profile information found.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @endsection