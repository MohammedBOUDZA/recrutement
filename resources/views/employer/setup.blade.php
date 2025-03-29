@extends('layouts.app')

@section('title', 'Set Up Company Profile')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="card-title mb-4 fw-bold text-center">Set Up Your Company Profile</h4>
                    
                    <form action="{{ route('entreprise.setup.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="company_name" class="form-label fw-bold">Company Name</label>
                            <input type="text" name="company_name" id="company_name" 
                                class="form-control @error('company_name') is-invalid @enderror" 
                                value="{{ old('company_name') }}" required>
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Company Description</label>
                            <textarea name="description" id="description" rows="4" 
                                class="form-control @error('description') is-invalid @enderror" 
                                required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="website" class="form-label fw-bold">Website</label>
                            <input type="url" name="website" id="website" 
                                class="form-control @error('website') is-invalid @enderror" 
                                value="{{ old('website') }}">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="location" class="form-label fw-bold">Location</label>
                            <input type="text" name="location" id="location" 
                                class="form-control @error('location') is-invalid @enderror" 
                                value="{{ old('location') }}" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="industry" class="form-label fw-bold">Industry</label>
                            <select name="industry" id="industry" 
                                class="form-select @error('industry') is-invalid @enderror" required>
                                <option value="">Select Industry</option>
                                <option value="Technology" {{ old('industry') === 'Technology' ? 'selected' : '' }}>Technology</option>
                                <option value="Healthcare" {{ old('industry') === 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                                <option value="Finance" {{ old('industry') === 'Finance' ? 'selected' : '' }}>Finance</option>
                                <option value="Education" {{ old('industry') === 'Education' ? 'selected' : '' }}>Education</option>
                                <option value="Manufacturing" {{ old('industry') === 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                                <option value="Retail" {{ old('industry') === 'Retail' ? 'selected' : '' }}>Retail</option>
                                <option value="Other" {{ old('industry') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('industry')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-building me-2"></i>Create Company Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-control, .form-select {
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    border: 1px solid #e3e6f0;
}

.form-control:focus, .form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.btn {
    border-radius: 0.5rem;
    padding: 0.75rem 1.5rem;
}
</style>
@endsection 