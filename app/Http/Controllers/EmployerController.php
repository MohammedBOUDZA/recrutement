<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emploi;
use App\Models\Application;
use App\Http\Controllers\Controller;

class EmployerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:recruteur']);
    }

    public function dashboard()
    {
        $user = auth()->user();
        
        if (!$user->entreprise) {
            return redirect()->route('entreprise.create')
                ->with('error', 'Please complete your company profile first.');
        }

        $postedJobs = Emploi::where('entreprise_id', $user->entreprise->id)
            ->withCount('applications')
            ->latest()
            ->take(5)
            ->get();

        $recentApplications = Application::whereHas('emploi', function($query) use ($user) {
                $query->where('entreprise_id', $user->entreprise->id);
            })
            ->with(['emploi', 'user'])
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total_jobs' => Emploi::where('entreprise_id', $user->entreprise->id)->count(),
            'total_applications' => Application::whereHas('emploi', function ($query) use ($user) {
                $query->where('entreprise_id', $user->entreprise->id);
            })->count(),
            'pending_applications' => Application::whereHas('emploi', function ($query) use ($user) {
                $query->where('entreprise_id', $user->entreprise->id);
            })->where('status', 'pending')->count(),
        ];

        return view('recruteur.dashboard', compact('postedJobs', 'recentApplications', 'stats'));
    }

    public function setup()
    {
        return view('recruteur.setup');
    }

    public function storeSetup(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_description' => 'required|string',
            'industry' => 'required|string|max:255',
            'company_size' => 'required|string|max:50',
            'company_website' => 'nullable|url|max:255',
            'company_logo' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();
        
        if ($request->hasFile('company_logo')) {
            $path = $request->file('company_logo')->store('company-logos', 'public');
            $validated['company_logo'] = $path;
        }

        $user->update($validated);

        return redirect()->route('recruteur.dashboard')
            ->with('success', 'Company profile updated successfully');
    }
}
