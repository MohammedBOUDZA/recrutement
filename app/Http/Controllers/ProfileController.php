<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chercheur;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = null;

        if ($user->role === 'chercheur') {
            $profile = Chercheur::where('user_id', $user->id)->first();
            $applications = Application::where('user_id', $user->id)
                ->with(['emploi.entreprise'])
                ->latest()
                ->take(5)
                ->get();
            return view('emploi.profile', compact('profile', 'user', 'applications'));
        }

        return view('emplois.profile', compact('profile', 'user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'chercheur') {
            return back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'cv' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'skills' => ['required', 'string'],
            'experience' => ['required', 'string'],
            'education' => ['required', 'string'],
        ]);

        $profile = $user->chercheur;

        if ($request->hasFile('cv')) {
            if ($profile->cv) {
                Storage::disk('public')->delete($profile->cv);
            }
            $cvPath = $request->file('cv')->store('cvs/' . $user->id, 'public');
            $profile->cv = $cvPath;
        }

        $profile->update([
            'skills' => $request->skills,
            'experience' => $request->experience,
            'education' => $request->education,
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password updated successfully!');
    }
}
