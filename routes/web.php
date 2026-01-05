<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProviderRequestController;
use App\Http\Controllers\Admin\ProviderRequestController as AdminProviderRequestController;
use App\Http\Controllers\Admin\DashboardController;

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

    // user provider request
    Route::resource('provider-requests', ProviderRequestController::class)
        ->only(['create', 'store']);
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/dashboard', DashboardController::class);

        Route::resource(
            'provider-requests',
            AdminProviderRequestController::class
        )->only(['index', 'show', 'update', 'destroy']);
    });

require __DIR__.'/auth.php';
