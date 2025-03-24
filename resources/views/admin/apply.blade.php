@extends('admin.ahome')

@section('content')
    @foreach($jobApplications as $application)
        <p>{{ $application->user->name }} applied for this job.</p>
    @endforeach
    @foreach($jobApplications as $application)
        <p>{{ $application->user->name }} applied for this job.</p>
        
        <form action="{{ url('/applications/' . $application->id . '/update-status') }}" method="POST">
            @csrf
            <select name="status">
                <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit">Update</button>
        </form>
    @endforeach
@endsection
