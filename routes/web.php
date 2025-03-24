<?php

use App\Http\Controllers\EmploiController;
use App\Http\Controllers\ChercheurController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AhomeController;
use App\Http\Controllers\ApplicationController;

// Home Page
Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

// Home Route
Route::get('/home', [HomeController::class, 'index'])
    ->middleware('auth') 
    ->name('home');

Route::get('/ahome', [AhomeController::class, 'index'])
    ->middleware('auth') 
    ->name('ahome');

// Profile Route
Route::get('/profile', [ProfileController::class, 'show'])
    ->middleware('auth') 
    ->name('profile');

// Emploi (Jobs) Routes
Route::middleware("auth")->group(
    function () {
        Route::get('/emplois', [EmploiController::class, 'index'])->name('emplois.index'); // List all jobs
        Route::get('/emploisdetail/{id}', [EmploiController::class, 'show'])->name('emplois.show'); // Show a single job
        Route::post('/emplois/postuler', [EmploiController::class, 'apply'])->name('emplois.apply'); // Apply for a job
        Route::post('/create', [AhomeController::class, 'create'])->name('emplois.store');
    }
);
// Chercheur (Job Seeker) Routes
Route::middleware('auth')->group(function () {
    Route::get('/chercheur/profil', [ChercheurController::class, 'profile'])->name('chercheur.profile'); // Job seeker profile
    Route::post('/chercheur/profil', [ChercheurController::class, 'updateProfile'])->name('chercheur.updateProfile'); // Update profile
    Route::get('/chercheur/candidatures', [ChercheurController::class, 'applications'])->name('chercheur.applications'); // View job applications
});

// Employer Routes
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/entreprise/dashboard', [AhomeController::class, 'index'])->name('entreprise.dashboard'); // Employer dashboard
    Route::get('/entreprise/emplois', [AhomeController::class, 'jobs'])->name('entreprise.jobs'); // List employer's jobs
    Route::get('/entreprise/emplois/create', [AhomeController::class, 'create'])->name('entreprise.jobs.create'); // Create a new job
    Route::post('/entreprise/emplois', [AhomeController::class, 'store'])->name('entreprise.jobs.store'); // Store a new job
    Route::get('/entreprise/emplois/{emploi}/edit', [AhomeController::class, 'edit'])->name('entreprise.jobs.edit'); // Edit a job
    Route::put('/entreprise/emplois/{emploi}', [AhomeController::class, 'update'])->name('entreprise.jobs.update'); // Update a job
    Route::delete('/entreprise/emplois/{emploi}', [AhomeController::class, 'destroy'])->name('entreprise.jobs.destroy'); // Delete a job
});
Route::middleware(['auth'])->group(function () {
    // Route for job seekers to view their applications
    Route::get('/uapplications', [ApplicationController::class, 'userApplications'])->name('user.applications');

    // Route for employers to view applications for their posted jobs
    Route::get('/capplications', [ApplicationController::class, 'companyApplications'])->name('company.applications');

    // Route to update application status (only employers should access this)
    Route::post('//jobs/{job}/uapplication', [ApplicationController::class, 'updateStatus'])->name('applications.updateStatus');
});


// Fallback Route (404 Page)
Route::fallback(function () {
    return view('errors.404');
}); 
// GET route to show the registration form
Route::get('auth/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// POST route to handle the registration form submission
Route::post('auth/register', [RegisterController::class, 'register']);