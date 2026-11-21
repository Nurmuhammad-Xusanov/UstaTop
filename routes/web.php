<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProviderRequestController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\TelegramWebhookController;

Route::post('/api/telegram/webhook', [TelegramWebhookController::class, 'handle'])->name('telegram.webhook');

Route::get('/locale/{lang}', function ($lang) {
    if (in_array($lang, ['uz', 'ru', 'en'])) {
        session(['locale' => $lang]);
        cookie()->queue(cookie()->forever('locale', $lang));
        if (auth()->check()) {
            $user = auth()->user();
            $user->locale = $lang;
            if ($user->sync_telegram_lang) {
                $user->telegram_locale = $lang;
            }
            $user->save();
        }
    }
    return redirect()->back();
})->name('locale.switch');

Route::get('/debug-smtp', function () {
    $ports = [2525, 587, 465, 80, 443];
    $results = [];
    foreach ($ports as $port) {
        $t0 = microtime(true);
        $fp = @fsockopen('smtp-relay.brevo.com', $port, $errno, $errstr, 2);
        $duration = round(microtime(true) - $t0, 3);
        if ($fp) {
            $results[$port] = "OPEN ({$duration}s)";
            fclose($fp);
        } else {
            $results[$port] = "BLOCKED/FAILED ({$errstr}, {$duration}s)";
        }
    }

    if (request()->has('send')) {
        try {
            \Illuminate\Support\Facades\Mail::raw('Test live mail from Render', function ($msg) {
                $msg->to(request('to', 'nursherzil@gmail.com'))->subject('Test from Render UstaTop');
            });
            $results['mail_send'] = 'SUCCESS';
        } catch (\Throwable $e) {
            $results['mail_send'] = 'FAILED: ' . $e->getMessage();
        }
    }

    return response()->json($results);
});

Route::get('/', function () {
    $categories = \App\Models\Category::withCount('users')->take(9)->get();
    $masters = \App\Models\User::where('role', 'provider')
        ->with('categories')
        ->take(6)
        ->get();
    $stats = [
        'completed_orders' => \App\Models\ServiceRequests::where('status', 'completed')->count() + 1240,
        'active_masters' => \App\Models\User::where('role', 'provider')->count() + 85,
        'rating' => '99.4%',
    ];
    return view('welcome', compact('categories', 'masters', 'stats'));
})->name('home');

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
    Route::post(
        '/service-requests/{serviceRequest}/reject',
        [ServiceRequestController::class, 'clientReject']
    )->name('client.service-requests.reject');
    Route::patch('/profile/preferences', [ProfileController::class, 'updatePreferences'])->name('profile.preferences');
    Route::delete('/profile/telegram', [ProfileController::class, 'disconnectTelegram'])->name('profile.telegram.disconnect');
    Route::post('/theme', [ProfileController::class, 'updateTheme'])->name('profile.updateTheme');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Users management
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('users.index');
        Route::patch('/users/{user}/role', [AdminDashboardController::class, 'updateUserRole'])->name('users.role');
        Route::patch('/users/{user}/toggle-verify', [AdminDashboardController::class, 'toggleVerifyUser'])->name('users.toggle-verify');
        Route::delete('/users/{user}', [AdminDashboardController::class, 'destroyUser'])->name('users.destroy');

        // Service Requests management
        Route::get('/service-requests', [AdminDashboardController::class, 'serviceRequests'])->name('service-requests.index');
        Route::patch('/service-requests/{serviceRequest}/status', [AdminDashboardController::class, 'updateServiceRequestStatus'])->name('service-requests.status');
        Route::delete('/service-requests/{serviceRequest}', [AdminDashboardController::class, 'destroyServiceRequest'])->name('service-requests.destroy');

        // Active Sessions
        Route::get('/sessions', [AdminDashboardController::class, 'sessions'])->name('sessions.index');

        // Provider Applications
        Route::resource('provider-requests', ProviderRequestController::class)
            ->only(['index', 'show', 'update', 'destroy']);

        // Categories
        Route::resource('categories', CategoriesController::class)
            ->only(['index', 'create', 'store', 'destroy']);
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
