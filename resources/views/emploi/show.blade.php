@extends('layouts.app')

@section('title', $emploi->title)

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">{{ $emploi->title }}</h1>
                        <div class="flex items-center gap-4 text-gray-600">
                            <span>{{ $emploi->entreprise->company_name }}</span>
                            <span>•</span>
                            <span>{{ $emploi->location }}</span>
                            <span>•</span>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">
                                {{ Str::title($emploi->emploi_type) }}
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-gray-700">
                            {{ number_format($emploi->salary) }} €/year
                        </div>
                        <div class="text-sm text-gray-500">
                            Posted {{ $emploi->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>

                <div class="mt-8 prose max-w-none">
                    {!! nl2br(e($emploi->description)) !!}
                </div>

                @auth
                    @if(auth()->user()->role === 'chercheur' && !$emploi->hasApplied(auth()->user()))
                        <form action="{{ route('applications.store', $emploi) }}" 
                              method="POST" 
                              class="mt-8 pt-8 border-t">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cover Letter</label>
                                <textarea name="cover_letter" 
                                        rows="4" 
                                        class="w-full rounded-lg border-gray-300 shadow-sm @error('cover_letter') border-red-500 @enderror"
                                        required></textarea>
                                @error('cover_letter')
                                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('cover_letter') }}</p>
                                @enderror
                            </div>
                            
                            <button type="submit" 
                                    class="w-full md:w-auto bg-blue-500 text-white px-8 py-3 rounded-lg hover:bg-blue-600 transition-colors">
                                Apply Now
                            </button>
                        </form>
                    @elseif(auth()->user()->role === 'chercheur' && $emploi->hasApplied(auth()->user()))
                        <div class="mt-8 pt-8 border-t">
                            <p class="text-green-600 font-medium">
                                <i class="fas fa-check-circle mr-2"></i>
                                You have already applied for this position
                            </p>
                        </div>
                    @endif
                @else
                    <div class="mt-8 pt-8 border-t text-center">
                        <p class="mb-4 text-gray-600">Please log in to apply for this position</p>
                        <a href="{{ route('login') }}" 
                           class="inline-block bg-blue-500 text-white px-8 py-3 rounded-lg hover:bg-blue-600 transition-colors">
                            Login to Apply
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection