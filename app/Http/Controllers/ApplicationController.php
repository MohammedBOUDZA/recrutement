<?php
namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Chercheur;
use App\Models\Emploi;
use App\Notifications\ApplicationStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function apply($jobId)
    {
        $job = Emploi::findOrFail($jobId);
        $user = auth()->user();

        if (Application::where('Emploi_id', $jobId)->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'You have already applied for this job.');
        }

        Application::create([
            'Emploi_id' => $job->id,
            'user_id' => $user->id,
        ]);

        return back()->with('success', 'Application submitted successfully!');
    }

    public function store(Request $request, Emploi $emploi)
    {
        $chercheur = Chercheur::where('user_id', Auth::id())->firstOrFail();

        if ($emploi->applications()->where('chercheurs_id', $chercheur->id)->exists()) {
            return back()->with('error', 'You have already applied for this position.');
        }

        $validated = $request->validate([
            'cover_letter' => 'required|string|min:100',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048'
        ]);

        $resumePath = $request->file('resume')->store('resumes', 'public');

        Application::create([
            'emplois_id' => $emploi->id,
            'chercheurs_id' => $chercheur->id,
            'cover_letter' => $validated['cover_letter'],
            'resume_path' => $resumePath,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Application submitted successfully!');
    }

    public function updateStatus(Request $request, Application $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,accepted,rejected'
        ]);

        $application->update($validated);

        $application->chercheur->user->notify(
            new ApplicationStatusChanged($application)
        );

        return back()->with('success', 'Application status updated successfully.');
    }

    public function userApplications()
    {
        $applications = Application::where('chercheurs_id', Auth::user()->chercheur->id)
            ->with(['emploi.entreprise'])
            ->latest()
            ->paginate(10);

        return view('applications.index', compact('applications'));
    }

    public function companyApplications()
    {
        $applications = Application::whereHas('emploi', function($query) {
            $query->where('entreprise_id', Auth::user()->entreprise->id);
        })
        ->with(['chercheur.user', 'emploi'])
        ->latest()
        ->paginate(10);

        return view('applications.company', compact('applications'));
    }
}

