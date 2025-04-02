<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;

class ChercheurController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $applications = Application::where('user_id', $user->id)
            ->with(['emploi.entreprise'])
            ->latest()
            ->get();
        
        $savedJobs = $user->savedJobs()
            ->with('entreprise')
            ->get();
        
        return view('chercheur.dashboard', compact('applications', 'savedJobs'));
    }
}
