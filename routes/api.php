<?php

use App\Http\Controllers\Api\AcademicController;
use App\Http\Controllers\Api\AlumniController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\VerificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
*/

// Matriculation & Verification Engine (Rate Limited)
Route::middleware('throttle:verification')->group(function () {
    Route::post('/verify-matric', [VerificationController::class, 'verifyMatric']);
    Route::post('/verify-manual', [VerificationController::class, 'submitManualVerification']);
});

// Authentication & Registration (Rate Limited)
Route::middleware('throttle:auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/contact', [ContactController::class, 'submit']);
});

// Academic Metadata (Faculties & Departments)
Route::get('/academic-data', [AcademicController::class, 'getAcademicData']);

// Alumni Directory & Member Profiles
Route::get('/alumni', [AlumniController::class, 'index']);
Route::get('/alumni/{id}', [AlumniController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Protected Authenticated Routes (Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/privacy', [AuthController::class, 'updatePrivacy']);

    // NDPA 2023 Data Rights (Portability & Erasure)
    Route::get('/me/export-data', [AuthController::class, 'exportPersonalData']);
    Route::post('/me/delete-account', [AuthController::class, 'deleteAccount']);

    // Profile & Member Portal
    Route::match(['put', 'post'], '/profile', [ProfileController::class, 'update']);
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar']);
    Route::get('/dashboard/stats', [ProfileController::class, 'getDashboardStats']);
});

/*
|--------------------------------------------------------------------------
| Admin & Moderator Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Api\AdminController::class, 'dashboard']);

    // Master Graduate Records & Bulk CSV Import
    Route::get('/records', [\App\Http\Controllers\Api\AdminController::class, 'getRecords']);
    Route::post('/records', [\App\Http\Controllers\Api\AdminController::class, 'storeRecord']);
    Route::put('/records/{id}', [\App\Http\Controllers\Api\AdminController::class, 'updateRecord']);
    Route::delete('/records/{id}', [\App\Http\Controllers\Api\AdminController::class, 'deleteRecord']);
    Route::post('/import', [\App\Http\Controllers\Api\AdminController::class, 'importCsv']);
    Route::get('/template-csv', [\App\Http\Controllers\Api\AdminController::class, 'downloadTemplateCsv']);

    // Manual Verifications Queue
    Route::get('/verifications', [\App\Http\Controllers\Api\AdminController::class, 'getVerifications']);
    Route::post('/verifications/{id}/approve', [\App\Http\Controllers\Api\AdminController::class, 'approveVerification']);
    Route::post('/verifications/{id}/reject', [\App\Http\Controllers\Api\AdminController::class, 'rejectVerification']);

    // User Management & Roles
    Route::get('/users', [\App\Http\Controllers\Api\AdminController::class, 'getUsers']);
    Route::put('/users/{id}/role', [\App\Http\Controllers\Api\AdminController::class, 'updateUserRole']);
    Route::put('/users/{id}/status', [\App\Http\Controllers\Api\AdminController::class, 'updateUserStatus']);

    // Audit Logs
    Route::get('/audit-logs', [\App\Http\Controllers\Api\AdminController::class, 'getAuditLogs']);
});

