@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <form id="searchForm" action="{{ route('emplois.index') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-4">
                        <div>
                            <label for="keyword" class="block text-sm font-medium text-gray-700">Mots clés</label>
                            <input type="text" name="keyword" id="keyword" value="{{ request('keyword') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                   placeholder="Titre, mots clés, ou entreprise">
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700">Localisation</label>
                            <input type="text" name="location" id="location" value="{{ request('location') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                   placeholder="Ville ou région">
                        </div>

                        <div>
                            <label for="employment_type" class="block text-sm font-medium text-gray-700">Type d'emploi</label>
                            <select name="employment_type" id="employment_type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Tous les types</option>
                                <option value="full-time" {{ request('employment_type') == 'full-time' ? 'selected' : '' }}>Temps plein</option>
                                <option value="part-time" {{ request('employment_type') == 'part-time' ? 'selected' : '' }}>Temps partiel</option>
                                <option value="contract" {{ request('employment_type') == 'contract' ? 'selected' : '' }}>Contrat</option>
                                <option value="temporary" {{ request('employment_type') == 'temporary' ? 'selected' : '' }}>Temporaire</option>
                                <option value="internship" {{ request('employment_type') == 'internship' ? 'selected' : '' }}>Stage</option>
                            </select>
                        </div>

                        <div>
                            <label for="salary_range" class="block text-sm font-medium text-gray-700">Fourchette de salaire</label>
                            <select name="salary_range" id="salary_range"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Toutes les fourchettes</option>
                                <option value="0-30000" {{ request('salary_range') == '0-30000' ? 'selected' : '' }}>Moins de 30 000€</option>
                                <option value="30000-50000" {{ request('salary_range') == '30000-50000' ? 'selected' : '' }}>30 000€ - 50 000€</option>
                                <option value="50000-80000" {{ request('salary_range') == '50000-80000' ? 'selected' : '' }}>50 000€ - 80 000€</option>
                                <option value="80000-120000" {{ request('salary_range') == '80000-120000' ? 'selected' : '' }}>80 000€ - 120 000€</option>
                                <option value="120000+" {{ request('salary_range') == '120000+' ? 'selected' : '' }}>Plus de 120 000€</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="remote" id="remote" value="1" {{ request('remote') ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded filter-checkbox">
                            <label for="remote" class="ml-2 block text-sm text-gray-900">
                                Télétravail
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="hybrid" id="hybrid" value="1" {{ request('hybrid') ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded filter-checkbox">
                            <label for="hybrid" class="ml-2 block text-sm text-gray-900">
                                Hybride
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="urgent" id="urgent" value="1" {{ request('urgent') ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded filter-checkbox">
                            <label for="urgent" class="ml-2 block text-sm text-gray-900">
                                Recrutement urgent
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Rechercher
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ $emplois->total() }} offre(s) trouvée(s)
                        </h2>
                        @if(request()->hasAny(['keyword', 'location', 'employment_type', 'salary_range', 'remote', 'hybrid', 'urgent']))
                            <p class="mt-1 text-sm text-gray-500">
                                Résultats filtrés
                            </p>
                        @endif
                    </div>

                    <div class="flex items-center space-x-4">
                        <label for="sort" class="block text-sm font-medium text-gray-700">Trier par:</label>
                        <select name="sort" id="sort" onchange="window.location.href=this.value"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="{{ route('emplois.index', array_merge(request()->query(), ['sort' => 'relevance'])) }}"
                                    {{ request('sort') == 'relevance' ? 'selected' : '' }}>
                                Pertinence
                            </option>
                            <option value="{{ route('emplois.index', array_merge(request()->query(), ['sort' => 'date'])) }}"
                                    {{ request('sort') == 'date' ? 'selected' : '' }}>
                                Date de publication
                            </option>
                            <option value="{{ route('emplois.index', array_merge(request()->query(), ['sort' => 'salary_high'])) }}"
                                    {{ request('sort') == 'salary_high' ? 'selected' : '' }}>
                                Salaire : Du plus haut
                            </option>
                            <option value="{{ route('emplois.index', array_merge(request()->query(), ['sort' => 'salary_low'])) }}"
                                    {{ request('sort') == 'salary_low' ? 'selected' : '' }}>
                                Salaire : Du plus bas
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse($emplois as $emploi)
                    <div class="p-6 hover:bg-gray-50 transition duration-150">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        <a href="{{ route('emplois.show', $emploi) }}" class="hover:text-blue-600">
                                            {{ $emploi->title }}
                                        </a>
                                    </h3>
                                    @if($emploi->urgent)
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Urgent
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-1 flex items-center text-sm text-gray-500">
                                    <span>{{ $emploi->entreprise->name }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $emploi->location }}</span>
                                    @if($emploi->remote || $emploi->hybrid)
                                        <span class="mx-2">•</span>
                                        @if($emploi->remote && $emploi->hybrid)
                                            <span>Télétravail & Hybride</span>
                                        @elseif($emploi->remote)
                                            <span>Télétravail</span>
                                        @else
                                            <span>Hybride</span>
                                        @endif
                                    @endif
                                </div>

                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <span>{{ $emploi->employment_type }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $emploi->salary_range }}</span>
                                    <span class="mx-2">•</span>
                                    <span>Publié {{ $emploi->created_at->diffForHumans() }}</span>
                                </div>

                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 line-clamp-2">
                                        {{ Str::limit(strip_tags($emploi->description), 200) }}
                                    </p>
                                </div>

                                <div class="mt-4 flex flex-wrap gap-2">
                                    @foreach($emploi->categories as $category)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                    @foreach($emploi->skills->take(3) as $skill)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $skill->name }}
                                        </span>
                                    @endforeach
                                    @if($emploi->skills->count() > 3)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            +{{ $emploi->skills->count() - 3 }} autres
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="ml-4 flex-shrink-0">
                                @auth
                                    @if(auth()->user()->role === 'chercheur')
                                        @if(auth()->user()->savedJobs->contains($emploi->id))
                                            <form action="{{ route('emplois.unsave', $emploi) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center p-2 border border-transparent rounded-md text-blue-600 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('emplois.save', $emploi) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center p-2 border border-gray-300 rounded-md shadow-sm text-gray-400 bg-white hover:text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                @else
                                    <a href="{{ route('login') }}"
                                       class="inline-flex items-center p-2 border border-gray-300 rounded-md shadow-sm text-gray-400 bg-white hover:text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                        </svg>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">
                        Aucune offre d'emploi ne correspond à vos critères.
                    </div>
                @endforelse
            </div>

            <div class="px-4 py-4 sm:px-6 bg-gray-50">
                {{ $emplois->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const filterInputs = searchForm.querySelectorAll('input, select');

    searchForm.querySelectorAll('select').forEach(select => {
        select.addEventListener('change', () => searchForm.submit());
    });

    searchForm.querySelectorAll('.filter-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', () => searchForm.submit());
    });

    let timeout = null;
    const textInputs = searchForm.querySelectorAll('input[type="text"]');
    textInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                searchForm.submit();
            }, 500);
        });
    });

    textInputs.forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchForm.submit();
            }
        });
    });
});
</script>
@endpush
@endsection 