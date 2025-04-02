<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Chercheur;
use App\Models\Entreprise;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                'confirmed'
            ],
            'role' => ['required', 'string', 'in:chercheur,recruteur'],
        ];

        if ($data['role'] === 'chercheur') {
            $rules = array_merge($rules, [
                'cv' => ['required', 'file', 'mimes:pdf', 'max:5120'], // 5MB max
                'skills' => ['required', 'string', 'max:1000'],
                'experience' => ['required', 'string', 'max:2000'],
                'education' => ['required', 'string', 'max:2000'],
            ]);
        } else {
            $rules = array_merge($rules, [
                'company_name' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string', 'max:2000'],
                'website' => ['required', 'url', 'max:255'],
                'location' => ['required', 'string', 'max:255'],
                'industry' => ['required', 'string', 'max:255'],
            ]);
        }

        return Validator::make($data, $rules, [
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'cv.max' => 'The CV file must not be larger than 5MB.',
        ]);
    }

    protected function create(array $data)
    {
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
            ]);

            if ($data['role'] === 'chercheur') {
                $cvPath = $data['cv']->store('cvs', 'public');

                Chercheur::create([
                    'user_id' => $user->id,
                    'cv_path' => $cvPath,
                    'skills' => $data['skills'],
                    'experience' => $data['experience'],
                    'education' => $data['education'],
                ]);
            } else {
                Entreprise::create([
                    'user_id' => $user->id,
                    'company_name' => $data['company_name'],
                    'description' => $data['description'],
                    'website' => $data['website'],
                    'location' => $data['location'],
                    'industry' => $data['industry'],
                ]);
            }

            return $user;
        } catch (\Exception $e) {
            // If user was created but profile creation failed, delete the user
            if (isset($user)) {
                $user->delete();
            }

            // If CV was uploaded but profile creation failed, delete the CV
            if (isset($cvPath)) {
                Storage::disk('public')->delete($cvPath);
            }

            throw $e;
        }
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new \Illuminate\Auth\Events\Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new \Illuminate\Http\JsonResponse([], 201)
            : redirect($this->redirectPath());
    }

    protected function registered(Request $request, $user)
    {
        $user->notify(new \App\Notifications\WelcomeNotification());

        if ($user->role === 'chercheur') {
            return redirect()->route('chercheur.dashboard');
        } else {
            return redirect()->route('recruteur.dashboard');
        }
    }
}