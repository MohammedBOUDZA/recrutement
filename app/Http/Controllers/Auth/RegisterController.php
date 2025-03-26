<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Chercheur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()],
            'role' => ['required', 'in:chercheur,recruteur'],
            'cv' => ['required_if:role,chercheur', 'file', 'mimes:pdf', 'max:5120'],
            'skills' => ['required_if:role,chercheur', 'string', 'nullable'],
            'experience' => ['required_if:role,chercheur', 'string', 'nullable'],
            'education' => ['required_if:role,chercheur', 'string', 'nullable'],
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            if ($request->role === 'chercheur') {
                $cvPath = null;
                if ($request->hasFile('cv')) {
                    $cvPath = $request->file('cv')->store('cvs/' . $user->id, 'public');
                }

                Chercheur::create([
                    'user_id' => $user->id,
                    'cv' => $cvPath,
                    'skills' => $request->skills,
                    'experience' => $request->experience,
                    'education' => $request->education,
                ]);
            }

            DB::commit();
            auth()->login($user);
            
            return redirect()->route('dashboard')
                ->with('success', 'Registration successful! Welcome to our platform.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if (isset($cvPath) && $cvPath) {
                Storage::disk('public')->delete($cvPath);
            }

            return back()
                ->with('error', 'Registration failed. Please try again.')
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }
}