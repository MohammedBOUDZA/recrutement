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

    Route::get('/emplois', [EmploiController::class, 'index'])->name('emplois.index');
    Route::get('/emplois/{emploi}', [EmploiController::class, 'show'])->name('emplois.show');

    Route::middleware('role:chercheur')->group(function () {
        Route::get('applications', [ApplicationController::class, 'userApplications'])
            ->name('chercheur.applications');
        Route::post('emplois/{emploi}/apply', [ApplicationController::class, 'store'])
            ->name('applications.store');
    });

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

    Route::get('/dashboard', function () {
        return auth()->user()->role === 'chercheur' 
            ? redirect()->route('emplois.index') 
            : redirect()->route('emplois.index');
    })->name('dashboard');
});