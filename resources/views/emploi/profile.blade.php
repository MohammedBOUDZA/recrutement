@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">{{ $user->name }}'s Profile</h2>
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($user->role === 'chercheur')
                        @if($profile)
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-4">
                                    <h4>Current CV:</h4>
                                    @if($profile->cv)
                                        <div class="d-flex align-items-center">
                                            <a href="{{ Storage::url($profile->cv) }}" class="btn btn-sm btn-primary" target="_blank">
                                                <i class="fas fa-file-pdf me-2"></i>View CV
                                            </a>
                                        </div>
                                    @else
                                        <p class="text-muted">No CV uploaded</p>
                                    @endif
                                    
                                    <label for="cv" class="form-label mt-3">Upload New CV (PDF):</label>
                                    <input type="file" name="cv" id="cv" class="form-control @error('cv') is-invalid @enderror" accept=".pdf">
                                    @error('cv')
                                        <div class="invalid-feedback">{{ $errors->first('cv') }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="skills" class="form-label">Skills:</label>
                                    <textarea name="skills" id="skills" rows="3" class="form-control @error('skills') is-invalid @enderror">{{ old('skills', $profile->skills) }}</textarea>
                                    @error('skills')
                                        <div class="invalid-feedback">{{ $errors->first('skills') }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="experience" class="form-label">Experience:</label>
                                    <textarea name="experience" id="experience" rows="4" class="form-control @error('experience') is-invalid @enderror">{{ old('experience', $profile->experience) }}</textarea>
                                    @error('experience')
                                        <div class="invalid-feedback">{{ $errors->first('experience') }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="education" class="form-label">Education:</label>
                                    <textarea name="education" id="education" rows="4" class="form-control @error('education') is-invalid @enderror">{{ old('education', $profile->education) }}</textarea>
                                    @error('education')
                                        <div class="invalid-feedback">{{ $errors->first('education') }}</div>
                                    @enderror
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Your profile information is incomplete. Please update your profile.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            You are registered as a recruiter. No additional profile information is required.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection