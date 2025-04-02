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
Route::get('emplois/create', [EmploiController::class, 'create'])->name('emplois.create');

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
    Route::get('login/google', [LoginController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('login/google/callback', [LoginController::class, 'handleGoogleCallback']);
    Route::get('login/linkedin', [LoginController::class, 'redirectToLinkedin'])->name('login.linkedin');
    Route::get('login/linkedin/callback', [LoginController::class, 'handleLinkedinCallback']);
    Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');
    Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])
        ->name('password.update');
});

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
        Route::get('/chercheur/dashboard', [ChercheurController::class, 'dashboard'])
            ->name('chercheur.dashboard');
        Route::get('applications', [ApplicationController::class, 'userApplications'])
            ->name('chercheur.applications');
        Route::post('emplois/{emploi}/apply', [ApplicationController::class, 'store'])
            ->name('applications.store');
    });

    Route::middleware('role:recruteur')->group(function () {
        Route::get('/recruteur/dashboard', [EmployerController::class, 'dashboard'])
            ->name('recruteur.dashboard');
        Route::get('entreprise/setup', [EmployerController::class, 'setup'])
            ->name('entreprise.setup');
        Route::post('entreprise/setup', [EmployerController::class, 'storeSetup'])
            ->name('entreprise.setup.store');
        
        
        Route::post('emplois', [EmploiController::class, 'store'])->name('emplois.store');
        Route::resource('emplois', EmploiController::class)
            ->except(['index', 'show', 'create', 'store']);
            
        Route::get('applications/received', [ApplicationController::class, 'companyApplications'])
            ->name('entreprise.applications');
        Route::get('applications/{application}', [ApplicationController::class, 'show'])
            ->name('applications.show');
        Route::patch('applications/{application}/status', [ApplicationController::class, 'updateStatus'])
            ->name('applications.status.update');
    });

    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return $user->role === 'chercheur' 
            ? redirect()->route('chercheur.dashboard')
            : redirect()->route('recruteur.dashboard');
    })->name('dashboard');

    Route::get('/emplois/{emploi}/apply', [EmploiController::class, 'apply'])->name('emplois.apply');
    Route::get('/applications', [App\Http\Controllers\ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [App\Http\Controllers\ApplicationController::class, 'show'])->name('applications.show');
    Route::patch('/applications/{application}/status', [App\Http\Controllers\ApplicationController::class, 'updateStatus'])->name('applications.update-status');
    Route::post('/applications/{application}/notes', [App\Http\Controllers\ApplicationController::class, 'addNote'])->name('applications.add-note');
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/jobs', [AdminController::class, 'jobs'])->name('admin.jobs');
    Route::get('/applications', [AdminController::class, 'applications'])->name('admin.applications');
    Route::get('/statistics', [AdminController::class, 'statistics'])->name('admin.statistics');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::patch('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
});

require __DIR__.'/auth.php';