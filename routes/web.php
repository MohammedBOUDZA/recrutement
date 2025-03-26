<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    EmploiController,
    ChercheurController,
    EmployerController,
    ApplicationController,
    HomeController,
    ProfileController,
    AdminController,
    AdminUserController
};
use App\Http\Controllers\Auth\{LoginController, RegisterController};

// Public routes
Route::get('/', [EmploiController::class, 'index'])->name('home');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

// Protected routes
Route::middleware('auth')->group(function () {
    // Common authenticated routes
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    // Job listings routes (accessible to all authenticated users)
    Route::get('/emplois', [EmploiController::class, 'index'])->name('emplois.index');
    Route::get('/emplois/{emploi}', [EmploiController::class, 'show'])->name('emplois.show');

    // Job seeker routes
    Route::middleware('role:chercheur')->group(function () {
        Route::get('applications', [ApplicationController::class, 'userApplications'])
            ->name('chercheur.applications');
        Route::post('emplois/{emploi}/apply', [ApplicationController::class, 'store'])
            ->name('applications.store');
    });

    // Recruiter routes
    Route::middleware('role:recruteur')->group(function () {
        Route::get('dashboard', [EmployerController::class, 'dashboard'])
            ->name('entreprise.dashboard');
        Route::resource('mes-emplois', EmploiController::class)
            ->except(['index', 'show']);
        Route::get('applications/received', [ApplicationController::class, 'companyApplications'])
            ->name('entreprise.applications');
        Route::patch('applications/{application}/status', [ApplicationController::class, 'updateStatus'])
            ->name('applications.status.update');
    });

    // Dashboard redirect based on role
    Route::get('/dashboard', function () {
        return auth()->user()->role === 'chercheur' 
            ? redirect()->route('emplois.index') 
            : redirect()->route('entreprise.dashboard');
    })->name('dashboard');
});