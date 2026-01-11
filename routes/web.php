<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProviderRequestController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ServiceRequestController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::resource('provider-requests', ProviderRequestController::class)
        ->only(['create', 'store', 'index', 'destroy']);
    Route::resource('service-requests', ServiceRequestController::class)
        ->only(['index', 'show', 'create', 'store',]);
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index']);

        Route::resource(
            'provider-requests',
            ProviderRequestController::class
        )->only(['index', 'show', 'update', 'destroy',]);
    });

require __DIR__ . '/auth.php';
