<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;












Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'sendOtp'])->name('forgot-password.post');

    Route::get('/otp', function () {
        return view('auth.otp');
    })->name('otp');
    Route::post('/otp', [AuthController::class, 'verifyOtp'])->name('otp.post');

    Route::get('/reset-password', function () {
        return view('auth.reset-password');
    })->name('reset-password');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


Route::post('/verify-identity', [\App\Http\Controllers\IdentityVerificationController::class, 'upload'])->name('verification.upload')->middleware('auth');

Route::middleware('auth')->group(function () {
    
    Route::get('/employer-profile/{user}', [\App\Http\Controllers\CompanyController::class, 'show'])->name('employer.public.profile');

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', [\App\Http\Controllers\DashboardController::class, 'admin'])->name('admin');
        Route::get('/admin/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users');
        Route::get('/admin/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/users/{id}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
        
        Route::get('/admin/security', [\App\Http\Controllers\Admin\SecurityController::class, 'index'])->name('admin.security');
        Route::post('/admin/security/{id}/verify', [\App\Http\Controllers\Admin\SecurityController::class, 'verify'])->name('admin.security.verify');
    });

    Route::middleware('role:employer')->prefix('pemberi-kerja')->name('employer.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Employer\DashboardController::class, 'index'])->name('dashboard');
        
        
        Route::get('/jobs', [\App\Http\Controllers\Employer\JobController::class, 'index'])->name('jobs.index');
        Route::get('/jobs/average-wage', [\App\Http\Controllers\Employer\JobController::class, 'getAverageWage'])->name('jobs.average-wage');
        Route::get('/jobs/create', [\App\Http\Controllers\Employer\JobController::class, 'create'])->name('jobs.create');
        Route::post('/jobs', [\App\Http\Controllers\Employer\JobController::class, 'store'])->name('jobs.store');
        Route::get('/jobs/{job}/edit', [\App\Http\Controllers\Employer\JobController::class, 'edit'])->name('jobs.edit');
        Route::put('/jobs/{job}', [\App\Http\Controllers\Employer\JobController::class, 'update'])->name('jobs.update');
        Route::delete('/jobs/{job}', [\App\Http\Controllers\Employer\JobController::class, 'destroy'])->name('jobs.destroy');
        
        
        Route::get('/candidates', [\App\Http\Controllers\Employer\CandidateController::class, 'index'])->name('candidates.index');
        Route::put('/candidates/{application}/status', [\App\Http\Controllers\Employer\CandidateController::class, 'updateStatus'])->name('candidates.updateStatus');
        
        // Reviews Route
        Route::post('/transactions/{transaction}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
        
        
        Route::get('/messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{user}', [\App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{user}', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
        
        
        Route::get('/profile', [\App\Http\Controllers\Employer\ProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile', [\App\Http\Controllers\Employer\ProfileController::class, 'update'])->name('profile.update');
        
        
        Route::get('/settings', [\App\Http\Controllers\Employer\SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings/password', [\App\Http\Controllers\Employer\SettingController::class, 'updatePassword'])->name('settings.password');
        Route::delete('/settings/account', [\App\Http\Controllers\Employer\SettingController::class, 'destroy'])->name('settings.destroy');
    });

    Route::middleware('role:jobseeker')->prefix('pencari-kerja')->name('jobseeker.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Jobseeker\DashboardController::class, 'index'])->name('dashboard');
        
        
        Route::get('/jobs', [\App\Http\Controllers\Jobseeker\JobController::class, 'index'])->name('jobs.index');
        Route::post('/jobs/{job}/apply', [\App\Http\Controllers\Jobseeker\JobController::class, 'apply'])->name('jobs.apply');
        
        
        Route::get('/applications', [\App\Http\Controllers\Jobseeker\ApplicationController::class, 'index'])->name('applications.index');
        
        // Reviews Route
        Route::post('/transactions/{transaction}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
        
        Route::get('/saved', [\App\Http\Controllers\Jobseeker\SavedJobController::class, 'index'])->name('saved.index');
        Route::post('/saved/{job}', [\App\Http\Controllers\Jobseeker\SavedJobController::class, 'store'])->name('saved.store');
        
        
        Route::get('/messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{user}', [\App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{user}', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
        
        
        Route::get('/profile', [\App\Http\Controllers\Jobseeker\ProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile', [\App\Http\Controllers\Jobseeker\ProfileController::class, 'update'])->name('profile.update');
        
        
        Route::get('/settings', [\App\Http\Controllers\Jobseeker\SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings/password', [\App\Http\Controllers\Jobseeker\SettingController::class, 'updatePassword'])->name('settings.password');
        Route::delete('/settings/account', [\App\Http\Controllers\Jobseeker\SettingController::class, 'destroy'])->name('settings.destroy');
    });
});

