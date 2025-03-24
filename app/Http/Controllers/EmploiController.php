<?php

namespace App\Http\Controllers;

use App\Models\Emploi;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmploiController extends Controller
{
    public function index()
    {
        $jobs = Emploi::with('entreprise')
            ->latest()
            ->paginate(9);
        
        return view('emploi.index', compact('jobs'));
    }

    public function show(Emploi $emploi)
    {
        $emploi->load(['entreprise', 'applications' => function($query) {
            $query->with('chercheur.user');
        }]);

        $similarJobs = Emploi::where('id', '!=', $emploi->id)
            ->where(function($query) use ($emploi) {
                $query->where('emploi_type', $emploi->emploi_type)
                      ->orWhere('location', $emploi->location);
            })
            ->with('entreprise')
            ->limit(3)
            ->get();

        return view('emploi.show', compact('emploi', 'similarJobs'));
    }

    public function create()
    {
        return view('emploi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:100',
            'location' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'emploi_type' => 'required|in:full-time,part-time,contract,internship'
        ]);

        $emploi = $request->user()->entreprise->emplois()->create($validated);

        return redirect()->route('emplois.show', $emploi)
            ->with('success', 'Job posted successfully!');
    }

    public function edit(Emploi $emploi)
    {
        $this->authorize('update', $emploi);
        return view('emploi.edit', compact('emploi'));
    }

    public function update(Request $request, Emploi $emploi)
    {
        $this->authorize('update', $emploi);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:100',
            'location' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'emploi_type' => 'required|in:full-time,part-time,contract,internship'
        ]);

        $emploi->update($validated);

        return redirect()->route('emplois.show', $emploi)
            ->with('success', 'Job updated successfully!');
    }

    public function destroy(Emploi $emploi)
    {
        $this->authorize('delete', $emploi);
        
        $emploi->delete();

        return redirect()->route('entreprise.dashboard')
            ->with('success', 'Job deleted successfully!');
    }
}
