<div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100">
    <div class="p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">
                    <a href="{{ route('emplois.show', $job) }}" 
                       class="hover:text-blue-600 transition-colors">
                        {{ $job->title }}
                    </a>
                </h2>
                <p class="text-gray-600 mt-1">{{ $job->entreprise->company_name }}</p>
            </div>
            <div class="flex items-center space-x-3">
                @auth
                    @if(auth()->user()->savedJobs->contains($job->id))
                        <form action="{{ route('emplois.unsave', $job) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                <i class="fas fa-bookmark"></i>
                            </button>
                        </form>
                    @else
                        <form action="{{ route('emplois.save', $job) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center p-2 text-gray-400 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                <i class="far fa-bookmark"></i>
                            </button>
                        </form>
                    @endif
                @endauth
                <span class="px-3 py-1 text-sm rounded-full font-medium whitespace-nowrap {{ 
                    $job->emploi_type === 'full-time' ? 'bg-green-100 text-green-800' :
                    ($job->emploi_type === 'part-time' ? 'bg-blue-100 text-blue-800' :
                    ($job->emploi_type === 'contract' ? 'bg-purple-100 text-purple-800' :
                    'bg-gray-100 text-gray-800')) 
                }}">
                    {{ Str::title($job->emploi_type) }}
                </span>
            </div>
        </div>

        <div class="space-y-3 mb-4">
            <div class="flex items-center text-gray-600">
                <i class="fas fa-map-marker-alt w-4 h-4 mr-2"></i>
                <span>{{ $job->location }}</span>
            </div>
            <div class="flex items-center text-gray-600">
                <i class="fas fa-money-bill-wave w-4 h-4 mr-2"></i>
                <span>{{ number_format($job->salary) }} €/year</span>
            </div>
            <p class="text-gray-600 line-clamp-2">{{ $job->description }}</p>
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
            <span class="text-sm text-gray-500">
                <i class="far fa-clock mr-1"></i>
                {{ $job->created_at->diffForHumans() }}
            </span>
            <a href="{{ route('emplois.show', $job) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                View Details
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</div>
