@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900">
                    Post a New Job
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Fill in the details below to create a new job posting.
                </p>
            </div>

            <form action="{{ route('emplois.store') }}" method="POST" class="divide-y divide-gray-200">
                @csrf
                <div class="px-4 py-5 sm:p-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                        <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Job Title</label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                       required>
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                                <input type="text" name="location" id="location" value="{{ old('location') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                       required>
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="employment_type" class="block text-sm font-medium text-gray-700">Employment Type</label>
                                <select name="employment_type" id="employment_type"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        required>
                                    <option value="">Select Type</option>
                                    <option value="full-time" {{ old('employment_type') == 'full-time' ? 'selected' : '' }}>Full Time</option>
                                    <option value="part-time" {{ old('employment_type') == 'part-time' ? 'selected' : '' }}>Part Time</option>
                                    <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="temporary" {{ old('employment_type') == 'temporary' ? 'selected' : '' }}>Temporary</option>
                                    <option value="internship" {{ old('employment_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                                </select>
                                @error('employment_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="expires_at" class="block text-sm font-medium text-gray-700">Expiration Date</label>
                                <input type="date" name="expires_at" id="expires_at" value="{{ old('expires_at') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       required>
                                @error('expires_at')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Salary Information</h3>
                        <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-3 sm:gap-x-4">
                            <div>
                                <label for="salary_min" class="block text-sm font-medium text-gray-700">Minimum Salary</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">€</span>
                                    </div>
                                    <input type="number" name="salary_min" id="salary_min" value="{{ old('salary_min') }}"
                                           class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                           required>
                                </div>
                                @error('salary_min')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="salary_max" class="block text-sm font-medium text-gray-700">Maximum Salary</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">€</span>
                                    </div>
                                    <input type="number" name="salary_max" id="salary_max" value="{{ old('salary_max') }}"
                                           class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                           required>
                                </div>
                                @error('salary_max')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="salary_type" class="block text-sm font-medium text-gray-700">Salary Type</label>
                                <select name="salary_type" id="salary_type"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        required>
                                    <option value="">Select Type</option>
                                    <option value="hourly" {{ old('salary_type') == 'hourly' ? 'selected' : '' }}>Hourly</option>
                                    <option value="daily" {{ old('salary_type') == 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly" {{ old('salary_type') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="monthly" {{ old('salary_type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="yearly" {{ old('salary_type') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                </select>
                                @error('salary_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Job Description</h3>
                        <div class="mt-4">
                            <textarea name="description" id="description" rows="6"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Requirements</h3>
                        <div class="mt-4">
                            <textarea name="requirements" id="requirements" rows="4"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    required>{{ old('requirements') }}</textarea>
                            @error('requirements')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Benefits</h3>
                        <div class="mt-4">
                            <textarea name="benefits" id="benefits" rows="4"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    required>{{ old('benefits') }}</textarea>
                            @error('benefits')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Categories and Skills</h3>
                        <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                            <div>
                                <label for="categories" class="block text-sm font-medium text-gray-700">Job Categories</label>
                                <select name="categories[]" id="categories" multiple
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categories')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="skills" class="block text-sm font-medium text-gray-700">Required Skills</label>
                                <div class="mt-1 space-y-2">
                                    @foreach($skills as $skill)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="skills[{{ $skill->id }}]" id="skill_{{ $skill->id }}"
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                   value="beginner">
                                            <label for="skill_{{ $skill->id }}" class="ml-2 block text-sm text-gray-900">
                                                {{ $skill->name }}
                                            </label>
                                            <select name="skills[{{ $skill->id }}]" class="ml-2 block w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                                <option value="beginner">Beginner</option>
                                                <option value="intermediate">Intermediate</option>
                                                <option value="advanced">Advanced</option>
                                                <option value="expert">Expert</option>
                                            </select>
                                        </div>
                                    @endforeach
                                </div>
                                @error('skills')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Additional Questions</h3>
                        <div class="mt-4 space-y-4">
                            <div id="questions-container">
                                @if(old('questions'))
                                    @foreach(old('questions') as $question)
                                        <div class="flex items-center space-x-2">
                                            <input type="text" name="questions[]" value="{{ $question }}"
                                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                   placeholder="Enter a question">
                                            <button type="button" class="remove-question text-red-600 hover:text-red-900">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" id="add-question"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Question
                            </button>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Job Options</h3>
                        <div class="mt-4 space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="remote" id="remote" value="1" {{ old('remote') ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="remote" class="ml-2 block text-sm text-gray-900">
                                    Remote Work Available
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="hybrid" id="hybrid" value="1" {{ old('hybrid') ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="hybrid" class="ml-2 block text-sm text-gray-900">
                                    Hybrid Work Available
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="urgent" id="urgent" value="1" {{ old('urgent') ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="urgent" class="ml-2 block text-sm text-gray-900">
                                    Urgent Position
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-4 py-4 sm:px-6 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <button type="button" onclick="history.back()"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button type="submit"
                                class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Post Job
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const questionsContainer = document.getElementById('questions-container');
    const addQuestionButton = document.getElementById('add-question');

    addQuestionButton.addEventListener('click', function() {
        const questionDiv = document.createElement('div');
        questionDiv.className = 'flex items-center space-x-2';
        questionDiv.innerHTML = `
            <input type="text" name="questions[]"
                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                   placeholder="Enter a question">
            <button type="button" class="remove-question text-red-600 hover:text-red-900">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;
        questionsContainer.appendChild(questionDiv);
    });

    questionsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-question')) {
            e.target.closest('.flex').remove();
        }
    });

    const salaryMin = document.getElementById('salary_min');
    const salaryMax = document.getElementById('salary_max');

    salaryMax.addEventListener('change', function() {
        if (parseInt(this.value) < parseInt(salaryMin.value)) {
            this.setCustomValidity('Maximum salary must be greater than minimum salary');
        } else {
            this.setCustomValidity('');
        }
    });

    salaryMin.addEventListener('change', function() {
        if (parseInt(salaryMax.value) < parseInt(this.value)) {
            salaryMax.setCustomValidity('Maximum salary must be greater than minimum salary');
        } else {
            salaryMax.setCustomValidity('');
        }
    });
});
</script>
@endpush
@endsection 