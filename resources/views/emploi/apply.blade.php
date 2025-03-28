@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Job Application Form -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900">
                    Apply for {{ $job->title }}
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    at {{ $job->entreprise->name }} • {{ $job->location }}
                </p>
            </div>

            <form action="{{ route('emplois.submit-application', $job) }}" method="POST" enctype="multipart/form-data" class="divide-y divide-gray-200">
                @csrf
                <div class="px-4 py-5 sm:p-6 space-y-6">
                    <!-- Personal Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>
                        <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700">First name</label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', auth()->user()->first_name ?? '') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                       required>
                                @error('first_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700">Last name</label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', auth()->user()->last_name ?? '') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                       required>
                                @error('last_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email ?? '') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                       required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone number</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                       required>
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Resume -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Resume</h3>
                        <div class="mt-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-1">
                                    <label for="resume" class="block text-sm font-medium text-gray-700">Upload your resume</label>
                                    <input type="file" name="resume" id="resume"
                                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                           accept=".pdf,.doc,.docx"
                                           required>
                                    <p class="mt-1 text-sm text-gray-500">PDF, DOC, or DOCX up to 5MB</p>
                                    @error('resume')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cover Letter -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Cover Letter</h3>
                        <div class="mt-4">
                            <textarea name="cover_letter" id="cover_letter" rows="6"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    placeholder="Why are you interested in this position?"
                                    required>{{ old('cover_letter') }}</textarea>
                            @error('cover_letter')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Additional Questions -->
                    @if($job->questions)
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Additional Questions</h3>
                            <div class="mt-4 space-y-4">
                                @foreach(json_decode($job->questions) as $question)
                                    <div>
                                        <label for="question_{{ $loop->index }}" class="block text-sm font-medium text-gray-700">
                                            {{ $question }}
                                        </label>
                                        <textarea name="answers[{{ $loop->index }}]" id="question_{{ $loop->index }}" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                required>{{ old("answers.{$loop->index}") }}</textarea>
                                        @error("answers.{$loop->index}")
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Work Experience -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Work Experience</h3>
                        <div class="mt-4">
                            <textarea name="experience" id="experience" rows="4"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    placeholder="Briefly describe your relevant work experience">{{ old('experience') }}</textarea>
                            @error('experience')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Skills -->
                    @if($job->skills->isNotEmpty())
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Required Skills</h3>
                            <div class="mt-4 space-y-4">
                                @foreach($job->skills as $skill)
                                    <div>
                                        <label for="skill_{{ $skill->id }}" class="block text-sm font-medium text-gray-700">
                                            {{ $skill->name }}
                                        </label>
                                        <select name="skills[{{ $skill->id }}]" id="skill_{{ $skill->id }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                required>
                                            <option value="">Select your proficiency level</option>
                                            <option value="beginner" {{ old("skills.{$skill->id}") == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                            <option value="intermediate" {{ old("skills.{$skill->id}") == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                            <option value="advanced" {{ old("skills.{$skill->id}") == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                            <option value="expert" {{ old("skills.{$skill->id}") == 'expert' ? 'selected' : '' }}>Expert</option>
                                        </select>
                                        @error("skills.{$skill->id}")
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="px-4 py-4 sm:px-6 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <button type="button" onclick="history.back()"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Back
                        </button>
                        <button type="submit"
                                class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Submit Application
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 