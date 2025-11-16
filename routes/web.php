<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\EventController;

Route::get('/', fn () => Inertia::render('Welcome'));

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->group(function () {

    // Dashboard & Profile
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'showDetail'])->name('profile.show');

    // Roles
    Route::resource('/roles', RoleController::class);
    Route::get('/roles/{role}/stats', [RoleController::class, 'stats'])->name('roles.stats');

    // Users
    Route::resource('/users', UserController::class);

    // Companies
    Route::resource('/companies', CompanyController::class);
    Route::post('/companies/quick-store', [CompanyController::class, 'quickStore'])->name('companies.quick-store');

    // Drivers
    Route::resource('/drivers', DriverController::class);

    // Events
    Route::resource('/events', EventController::class);

    // Materis (Event Content)
    Route::prefix('/events/{event}/materis')->group(function () {
        Route::get('/create', [EventController::class, 'createMateri'])->name('events.materis.create');
        Route::post('/', [EventController::class, 'storeMateri'])->name('events.materis.store');
        Route::get('/{materi}/edit', [EventController::class, 'editMateri'])->name('events.materis.edit');
        Route::put('/{materi}', [EventController::class, 'updateMateri'])->name('events.materis.update');
        Route::delete('/{materi}', [EventController::class, 'destroyMateri'])->name('events.materis.destroy');
    });

    // Exams
    Route::prefix('/events/{event}/exams')->group(function () {
        Route::get('/create', [EventController::class, 'createExam'])->name('events.exams.create');
        Route::post('/', [EventController::class, 'storeExam'])->name('events.exams.store');
        Route::get('/{exam}', [EventController::class, 'showExam'])->name('events.exams.show');
        Route::get('/{exam}/edit', [EventController::class, 'editExam'])->name('events.exams.edit');
        Route::put('/{exam}', [EventController::class, 'updateExam'])->name('events.exams.update');
        Route::delete('/{exam}', [EventController::class, 'destroyExam'])->name('events.exams.destroy');

        // Exam Questions
        Route::get('/{exam}/questions/create', [EventController::class, 'createQuestion'])->name('events.exams.questions.create');
        Route::post('/{exam}/questions', [EventController::class, 'storeQuestion'])->name('events.exams.questions.store');
        Route::get('/{exam}/questions/{question}/edit', [EventController::class, 'editQuestion'])->name('events.exams.questions.edit');
        Route::put('/{exam}/questions/{question}', [EventController::class, 'updateQuestion'])->name('events.exams.questions.update');
        Route::delete('/{exam}/questions/{question}', [EventController::class, 'destroyQuestion'])->name('events.exams.questions.destroy');
    });

    // Settings
    Route::resource('/settings', SettingController::class)->only(['index', 'update']);
    Route::post('/settings/update-multiple', [SettingController::class, 'updateMultiple'])->name('settings.update-multiple');

    // Web logout for Inertia (used by router.post(route('logout')))
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'auth'])->name('auth');
});
