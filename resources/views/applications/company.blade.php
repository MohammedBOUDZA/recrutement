@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Applications Management</h1>
            <a href="{{ route('emplois.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Post New Job
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <div class="flex items-center space-x-4">
                <span class="text-gray-700 font-medium">Filter by Status:</span>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ request()->url() }}" 
                       class="px-3 py-1 rounded-full text-sm {{ !request('status') ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }} hover:bg-blue-200">
                        All
                    </a>
                    @foreach(['submitted', 'reviewing', 'shortlisted', 'interviewed', 'offered', 'accepted', 'rejected'] as $status)
                        <a href="{{ request()->fullUrlWithQuery(['status' => $status]) }}" 
                           class="px-3 py-1 rounded-full text-sm {{ request('status') === $status ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }} hover:bg-blue-200">
                            {{ ucfirst($status) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        @if($applications->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <div class="text-gray-500 mb-4">No applications found</div>
                <p class="text-sm text-gray-400">Applications will appear here once candidates apply to your job postings.</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($applications as $application)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $application->user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $application->user->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $application->emploi->title }}</div>
                                        <div class="text-sm text-gray-500">{{ $application->emploi->location }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $application->created_at->format('M d, Y') }}
                                        <div class="text-xs text-gray-400">{{ $application->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="{{ route('applications.update-status', $application) }}" 
                                              method="POST" 
                                              class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" 
                                                    onchange="this.form.submit()"
                                                    class="text-sm rounded-full px-3 py-1 border-gray-300 focus:ring-blue-500 focus:border-blue-500
                                                    {{ $application->status === 'accepted' ? 'bg-green-100 text-green-800' :
                                                       ($application->status === 'rejected' ? 'bg-red-100 text-red-800' :
                                                       ($application->status === 'reviewing' ? 'bg-yellow-100 text-yellow-800' :
                                                       'bg-gray-100 text-gray-800')) }}">
                                                <option value="submitted" {{ $application->status === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                                <option value="reviewing" {{ $application->status === 'reviewing' ? 'selected' : '' }}>Under Review</option>
                                                <option value="shortlisted" {{ $application->status === 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                                <option value="interviewed" {{ $application->status === 'interviewed' ? 'selected' : '' }}>Interviewed</option>
                                                <option value="offered" {{ $application->status === 'offered' ? 'selected' : '' }}>Offered</option>
                                                <option value="accepted" {{ $application->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                                <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('applications.show', $application) }}" 
                                               class="text-blue-600 hover:text-blue-900">Review</a>
                                            @if($application->user->chercheur && $application->user->chercheur->cv_path)
                                                <a href="{{ Storage::url($application->user->chercheur->cv_path) }}" 
                                                   target="_blank"
                                                   class="text-gray-600 hover:text-gray-900">
                                                    View CV
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $applications->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelects = document.querySelectorAll('select[name="status"]');
    statusSelects.forEach(select => {
        select.addEventListener('change', function(e) {
            if (this.value === 'rejected' || this.value === 'accepted') {
                if (!confirm(`Are you sure you want to ${this.value} this application?`)) {
                    e.preventDefault();
                    this.value = this.getAttribute('data-previous-value');
                    return false;
                }
            }
            this.setAttribute('data-previous-value', this.value);
        });
    });
});
</script>
@endpush
@endsection