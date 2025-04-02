@extends('admin.ahome')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">Post New Job</h1>

        <form method="POST" action="{{ route('entreprise.jobs.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Job Title</label>
                <input type="text" name="title" class="w-full px-4 py-2 border rounded-lg" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Description</label>
                <input type="text" name="description" class="w-full px-4 py-2 border rounded-lg" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Location</label>
                <input type="text" name="location" class="w-full px-4 py-2 border rounded-lg" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Salary</label>
                <input type="number" step="0.01" name="salary" class="w-full px-4 py-2 border rounded-lg" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Job Type</label>
                <input type="text" name="emploi_type" class="w-full px-4 py-2 border rounded-lg" required>
            </div>

            <input type="hidden" name="entreprise_id" value="{{ Auth::id() }}">

            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                Post Job
            </button>
        </form>
    </div>
</div>
@endsection