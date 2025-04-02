<?php
namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Chercheur;
use App\Models\Emploi;
use App\Models\Notification;
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
        $user = Auth::user();

        if ($emploi->applications()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'You have already applied for this position.');
        }

        $validated = $request->validate([
            'cover_letter' => 'nullable|string|min:10',
        ]);

        $application = Application::create([
            'emplois_id' => $emploi->id,
            'user_id' => $user->id,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'status' => 'submitted',
            'submitted_at' => now()
        ]);

        Notification::create([
            'user_id' => $emploi->entreprise->user_id,
            'emploi_id' => $emploi->id,
            'status' => 'new_application',
            'message' => "New application received for {$emploi->title} from {$user->name}"
        ]);

        return redirect()->route('emplois.show', $emploi)
            ->with('success', 'Application submitted successfully! The employer will be notified.');
    }

    public function updateStatus(Request $request, Application $application)
    {
        abort_if(auth()->user()->role !== 'recruteur' || 
                $application->emploi->entreprise_id !== auth()->user()->entreprise_id, 403);

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,reviewing,interview_scheduled,interviewed,offered,accepted,rejected,withdrawn'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $oldStatus = $application->status;
        
        $history = $application->status_history ?? [];
        $history[] = [
            'status' => $validated['status'],
            'date' => now(),
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'note' => $request->input('note'),
        ];

        $application->update([
            'status' => $validated['status'],
            'status_history' => $history,
            'status_updated_at' => now(),
        ]);

        $statusMessages = [
            'reviewing' => 'Your application is now under review',
            'interview_scheduled' => 'Congratulations! You have been selected for an interview',
            'interviewed' => 'Your interview status has been updated',
            'offered' => 'Congratulations! You have received a job offer',
            'accepted' => 'Your job offer has been confirmed',
            'rejected' => 'Thank you for your interest, but we have decided to move forward with other candidates',
            'withdrawn' => 'Your application has been marked as withdrawn',
        ];

        $message = $statusMessages[$validated['status']] ?? "Your application status has been updated to {$validated['status']}";
        
        if ($validated['note']) {
            $message .= "\nNote: " . $validated['note'];
        }

        Notification::create([
            'user_id' => $application->user_id,
            'emploi_id' => $application->emploi_id,
            'application_id' => $application->id,
            'status' => 'status_update',
            'message' => $message,
            'data' => [
                'old_status' => $oldStatus,
                'new_status' => $validated['status'],
                'updated_by' => auth()->user()->name,
                'company_name' => auth()->user()->entreprise->company_name,
                'job_title' => $application->emploi->title,
            ]
        ]);

        return back()->with('success', 'Application status updated successfully.');
    }

    public function userApplications()
    {
        $applications = Application::where('user_id', Auth::id())
            ->with(['emploi.entreprise'])
            ->latest()
            ->paginate(10);

        return view('applications.index', compact('applications'));
    }

    public function companyApplications(Request $request)
    {
        $query = Application::whereHas('emploi', function($query) {
            $query->where('entreprise_id', Auth::user()->entreprise->id);
        })->with(['emploi', 'user', 'user.chercheur']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(10);

        return view('applications.company', compact('applications'));
    }

    public function show(Application $application)
    {
        $user = auth()->user();
        
        if ($user->role === 'recruteur') {
            if (!$user->entreprise) {
                abort(403, 'You must be associated with a company to view applications.');
            }
            
            if ($application->emploi->entreprise_id !== $user->entreprise->id) {
                abort(403, 'You are not authorized to view this application.');
            }
        } 
        else {
            if ($application->user_id !== $user->id) {
                abort(403, 'You can only view your own applications.');
            }
        }

        $application->load(['emploi', 'user', 'user.chercheur']);
        return view('applications.show', compact('application'));
    }

    public function addNote(Request $request, Application $application)
    {
        abort_if(auth()->user()->role !== 'recruteur' || 
                $application->emploi->entreprise_id !== auth()->user()->entreprise_id, 403);

        $validated = $request->validate([
            'note' => ['required', 'string', 'max:1000'],
        ]);

        $notes = $application->notes ?? [];
        $notes[] = [
            'content' => $validated['note'],
            'date' => now(),
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
        ];

        $application->update(['notes' => $notes]);

        return back()->with('success', 'Note added successfully.');
    }
}

