@extends('admin.ahome')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">Edit Emploi</h1>

        {{-- Error Display --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('entreprise.jobs.update', $emploi) }}">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Job Title</label>
                <input type="text" name="title" 
                       class="w-full px-4 py-2 border rounded-lg @error('title') border-red-500 @enderror"
                       value="{{ old('title', $emploi->title) }}" 
                       required>
                @error('title')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Description</label>
                <textarea name="description" 
                          class="w-full px-4 py-2 border rounded-lg @error('description') border-red-500 @enderror" 
                          rows="4"
                          required>{{ old('description', $emploi->description) }}</textarea>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Location -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Location</label>
                <input type="text" name="location" 
                       class="w-full px-4 py-2 border rounded-lg @error('location') border-red-500 @enderror"
                       value="{{ old('location', $emploi->location) }}" 
                       required>
                @error('location')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Salary -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Salary</label>
                <input type="number" step="0.01" name="salary" 
                       class="w-full px-4 py-2 border rounded-lg @error('salary') border-red-500 @enderror"
                       value="{{ old('salary', $emploi->salary) }}" 
                       required>
                @error('salary')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Job Type -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Job Type</label>
                <input type="text" name="emploi_type" 
                       class="w-full px-4 py-2 border rounded-lg @error('emploi_type') border-red-500 @enderror"
                       value="{{ old('emploi_type', $emploi->emploi_type) }}" 
                       required>
                @error('emploi_type')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                    Update Job
                </button>
                <a href="{{ route('entreprise.dashboard') }}" class="text-gray-600 hover:text-gray-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection