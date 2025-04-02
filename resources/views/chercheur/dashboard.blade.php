@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Tableau de bord du chercheur d'emploi</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Mes Candidatures</h2>
            @if($applications->count() > 0)
                <div class="space-y-4">
                    @foreach($applications as $application)
                        <div class="border-b pb-4">
                            <h3 class="font-medium">{{ $application->emploi->title }}</h3>
                            <p class="text-gray-600">{{ $application->emploi->entreprise->company_name }}</p>
                            <p class="text-sm text-gray-500">Statut: {{ $application->status }}</p>
                            <p class="text-sm text-gray-500">Postulé le: {{ $application->created_at->format('d/m/Y') }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Vous n'avez pas encore postulé à des offres d'emploi.</p>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Offres sauvegardées</h2>
            @if($savedJobs->count() > 0)
                <div class="space-y-4">
                    @foreach($savedJobs as $job)
                        <div class="border-b pb-4">
                            <h3 class="font-medium">{{ $job->title }}</h3>
                            <p class="text-gray-600">{{ $job->entreprise->company_name }}</p>
                            <p class="text-sm text-gray-500">{{ $job->location }}</p>
                            <a href="{{ route('emplois.show', $job) }}" class="text-blue-600 hover:text-blue-800 text-sm">Voir l'offre →</a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Vous n'avez pas encore sauvegardé d'offres d'emploi.</p>
            @endif
        </div>
    </div>
</div>
@endsection 