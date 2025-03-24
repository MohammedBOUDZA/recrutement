@extends('admin.ahome')

@section('content')
<div class="container">
    <h2>Company Dashboard</h2>
    <a class="nav-link" href="/entreprise/emplois/create">
                            <i class="bi bi-briefcase"></i> post a job
                        </a>
    
    @if($jobs->isEmpty())
        <p>No jobs posted yet.</p>
    @else
        <div class="list-group">
            @foreach($jobs as $job)
                <div class="list-group-item">
                    <h5>{{ $job->title }}</h5>
                    <p>{{ Str::limit($job->description, 150) }}</p>
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="badge bg-secondary">{{ $job->location }}</span>
                            @if($job->salary)
                                <span class="badge bg-success">{{ number_format($job->salary) }}</span>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('entreprise.jobs.edit', $job) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('entreprise.jobs.destroy', $job->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $jobs->links() }}
    @endif
</div>
@endsection