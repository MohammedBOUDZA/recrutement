<?php
namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Emploi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ApplicationController extends Controller
{
    public function apply($jobId)
    {
        $job = Emploi::findOrFail($jobId);
        $user = auth()->user();

        // Check if user already applied
        if (Application::where('Emploi_id', $jobId)->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'You have already applied for this job.');
        }

        // Save the application
        Application::create([
            'Emploi_id' => $job->id,
            'user_id' => $user->id,
        ]);

        return back()->with('success', 'Application submitted successfully!');
    }
    public function updateStatus(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected'
        ]);

        $application->update(['status' => $request->status]);

        return back()->with('success', 'Application status updated!');
    }
    public function userApplications()
    {
        $applications = Application::where('user_id', Auth::id())->with('job')->get();

        return view('user.applications', compact('applications'));
    }

    // Show applications for a company (employer) based on their jobs
    public function companyApplications()
    {
        $jobs = Auth::user()->Emplois; // Assuming a User has many Jobs
        $applications = Application::whereIn('Emploi_id', $jobs->pluck('id'))->with('user')->get();

        return view('company.applications', compact('applications'));
    }
}

