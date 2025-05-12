<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\TutorController;
use App\Models\Announcement;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ->name('logout');

Route::post('/tutor_profil', [TutorController::class, 'store'])
    ->middleware('auth:sanctum');

Route::apiResource('/categories', CategoryController::class);

Route::get('/all_announcement', [AnnouncementController::class, 'index']);
Route::apiResource('/announcement', AnnouncementController::class)->except('index')->middleware('auth:sanctum');
Route::get('/my_announcement', [AnnouncementController::class, 'myAnnoucements'])->middleware('auth:sanctum');

//connexion avec google
Route::get('/auth/oauth/{provider}/redirect', [SocialiteController::class, 'redirect']);
Route::get('/auth/oauth/{provider}/callback', [SocialiteController::class, 'callback']);