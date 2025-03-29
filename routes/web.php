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
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/', [EmploiController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('dashboard')->with('status', 'Email verified successfully!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/emplois', [EmploiController::class, 'index'])->name('emplois.index');
    Route::get('/emplois/{emploi}', [EmploiController::class, 'show'])->name('emplois.show');
    Route::post('/emplois/{emploi}/save', [EmploiController::class, 'saveJob'])->name('emplois.save');
    Route::delete('/emplois/{emploi}/unsave', [EmploiController::class, 'unsaveJob'])->name('emplois.unsave');

    Route::middleware('role:chercheur')->group(function () {
        Route::get('dashboard', [ChercheurController::class, 'dashboard'])
            ->name('chercheur.dashboard');
        Route::get('applications', [ApplicationController::class, 'userApplications'])
            ->name('chercheur.applications');
        Route::post('emplois/{emploi}/apply', [ApplicationController::class, 'store'])
            ->name('applications.store');
    });

    Route::middleware('role:recruteur')->group(function () {
        Route::get('dashboard', [EmployerController::class, 'dashboard'])
            ->name('entreprise.dashboard');
        Route::get('entreprise/setup', [EmployerController::class, 'setup'])
            ->name('entreprise.setup');
        Route::post('entreprise/setup', [EmployerController::class, 'storeSetup'])
            ->name('entreprise.setup.store');
        Route::resource('emplois', EmploiController::class)
            ->except(['index', 'show']);
        Route::get('applications/received', [ApplicationController::class, 'companyApplications'])
            ->name('entreprise.applications');
        Route::patch('applications/{application}/status', [ApplicationController::class, 'updateStatus'])
            ->name('applications.status.update');
    });

    Route::get('/dashboard', function () {
        return auth()->user()->role === 'chercheur' 
            ? redirect()->route('emplois.index') 
            : redirect()->route('emplois.index');
    })->name('dashboard');

    // Job Application Routes
    Route::get('/emplois/{emploi}/apply', [EmploiController::class, 'apply'])->name('emplois.apply');
});