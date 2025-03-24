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

    // Job listings routes
    Route::get('/emplois', [EmploiController::class, 'index'])->name('emplois.index');
    Route::get('/emplois/create', [EmploiController::class, 'create'])->name('emplois.create');
    Route::post('/emplois', [EmploiController::class, 'store'])->name('emplois.store');
    Route::get('/emplois/{emploi}', [EmploiController::class, 'show'])->name('emplois.show');
    Route::get('/emplois/{emploi}/edit', [EmploiController::class, 'edit'])->name('emplois.edit');
    Route::put('/emplois/{emploi}', [EmploiController::class, 'update'])->name('emplois.update');
    Route::delete('/emplois/{emploi}', [EmploiController::class, 'destroy'])->name('emplois.destroy');

    // Applications routes
    Route::post('/emplois/{emploi}/apply', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/applications', [ApplicationController::class, 'userApplications'])->name('applications.index');
    Route::patch('/applications/{application}/status', [ApplicationController::class, 'updateStatus'])
        ->name('applications.status.update');

    // Job seeker routes
    Route::middleware('role:chercheur')->group(function () {
        Route::get('applications', [ApplicationController::class, 'userApplications'])->name('chercheur.applications');
        Route::post('emplois/{emploi}/apply', [ApplicationController::class, 'store'])->name('applications.store');
    });

    // Employer routes
    Route::middleware('role:entreprise')->group(function () {
        Route::get('dashboard', [EmployerController::class, 'dashboard'])->name('entreprise.dashboard');
        Route::resource('mes-emplois', EmploiController::class)->except('index');
        Route::get('applications/received', [ApplicationController::class, 'companyApplications'])
            ->name('entreprise.applications');
        Route::patch('applications/{application}/status', [ApplicationController::class, 'updateStatus'])
            ->name('applications.status.update');
    });

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::resource('users', AdminUserController::class);
    });
});