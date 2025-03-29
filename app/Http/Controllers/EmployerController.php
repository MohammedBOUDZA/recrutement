<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployerController extends Controller
{
    public function setup()
    {
        $user = auth()->user();
        if ($user->entreprise) {
            return redirect()->route('entreprise.dashboard')
                ->with('info', 'Company profile already exists.');
        }
        return view('employer.setup');
    }

    public function storeSetup(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'website' => ['nullable', 'url', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'industry' => ['required', 'string', 'max:255'],
        ]);

        $user = auth()->user();
        $user->entreprise()->create($validated);

        return redirect()->route('entreprise.dashboard')
            ->with('success', 'Company profile created successfully!');
    }
}
