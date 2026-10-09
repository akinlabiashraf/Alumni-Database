<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Web Routes (Marketing & Information)
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome')->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/features', 'features')->name('features');
Route::view('/how-it-works', 'how-it-works')->name('how-it-works');
Route::view('/contact', 'contact')->name('contact');
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/terms', 'terms')->name('terms');

/*
|--------------------------------------------------------------------------
| Onboarding & Authentication Routes
|--------------------------------------------------------------------------
*/
Route::view('/register', 'register')->name('register');
Route::view('/login', 'login')->name('login');
Route::view('/forgot-password', 'forgot-password')->name('forgot-password');

/*
|--------------------------------------------------------------------------
| Directory & Alumni Profiles
|--------------------------------------------------------------------------
*/
Route::view('/directory', 'directory')->name('directory');
Route::get('/alumni/{id}', function ($id) {
    return view('profile', ['id' => $id]);
})->name('alumni.show');

/*
|--------------------------------------------------------------------------
| Authenticated Member Portal
|--------------------------------------------------------------------------
*/
Route::view('/dashboard', 'dashboard')->name('dashboard');
Route::view('/profile-settings', 'profile-settings')->name('profile.settings');
Route::view('/privacy-settings', 'privacy-settings')->name('privacy.settings');
Route::view('/change-password', 'change-password')->name('password.change');

/*
|--------------------------------------------------------------------------
| Admin & Moderator Web Views
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::view('/', 'admin.dashboard')->name('admin.index');
    Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');
    Route::view('/records', 'admin.records')->name('admin.records');
    Route::view('/verifications', 'admin.verifications')->name('admin.verifications');
    Route::view('/users', 'admin.users')->name('admin.users');
    Route::view('/audit-logs', 'admin.audit-logs')->name('admin.audit-logs');
});
