<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProviderRequestController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CategoriesControlelr;
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
        ->only(['index', 'show', 'create', 'store', 'destroy']);
    Route::post(
        '/service-requests/{serviceRequest}/confirm',
        [ServiceRequestController::class, 'clientConfirm']
    )->name('client.service-requests.confirm');
    Route::post('/theme', [ProfileController::class, 'updateTheme'])->name('profile.updateTheme');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource(
            'provider-requests',
            ProviderRequestController::class
        )->only(['index', 'show', 'update', 'destroy',]);
        Route::resource('categories', CategoriesControlelr::class);
    });
Route::middleware(['auth', 'provider'])
    ->prefix('provider')
    ->group(function () {

        Route::post(
            '/service-requests/{serviceRequest}/accept',
            [ServiceRequestController::class, 'accept']
        )->name('provider.service-requests.accept');

        Route::post(
            '/service-requests/{serviceRequest}/cancel',
            [ServiceRequestController::class, 'cancel']
        )->name('provider.service-requests.cancel');

        Route::post(
            '/service-requests/{serviceRequest}/done',
            [ServiceRequestController::class, 'providerDone']
        )->name('provider.service-requests.done');
    });


require __DIR__ . '/auth.php';
