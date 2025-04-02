<?php

namespace App\Http\Controllers;

use App\Models\Emploi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AhomeController extends Controller
{
    public function index()
    {
        $jobs = Emploi::where('entreprise_id', Auth::id())
                    ->latest()
                    ->paginate(10);

        return view('admin.dashboard', compact('jobs'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'salary' => 'required|numeric',
            'emploi_type' => 'required|string|max:255',
        ]);

        Emploi::create(array_merge($validated, [
            'entreprise_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'salary' => $request->salary,
            'emploi_type' => $request->emploi_type,
        ]));

        return redirect()->route('entreprise.dashboard')
            ->with('success', 'Job post created successfully!');
    }

    public function edit(Emploi $emploi)
    {
        return view('admin.edit', compact('emploi'));
    }

    public function update(Request $request, Emploi $emploi)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'salary' => 'required|numeric',
            'emploi_type' => 'required|string|max:255',
        ]);

        $emploi->update($validated);

        return redirect()->route('entreprise.dashboard')
            ->with('success', 'Job post updated successfully!');
    }

    public function destroy(Emploi $emploi)
    {
        $emploi->delete();

        return redirect()->route('entreprise.dashboard')
            ->with('success', 'Job post deleted successfully!');
    }
}