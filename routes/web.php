<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/db-check', function() {
    if (!app()->isLocal()) abort(404); // Block in production
    
    try {
        $tables = DB::select('SHOW TABLES');
        return response()->json([
            'database' => DB::connection()->getDatabaseName(),
            'tables' => $tables ?: 'No tables found'
        ]);
    } catch (Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/test-db', function() {
    try {
        DB::connection()->getPdo();
        return "Connected successfully to: " . DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Voting System Routes
    Route::resource('votes', VoteController::class); // This includes create, store, edit, update, destroy, and index routes

    // Notification Routes
    Route::prefix('notifications')->group(function () {
        Route::post('{notification}/mark-as-read', function ($notificationId) {
            auth()->user()->notifications()->where('id', $notificationId)->update(['read_at' => now()]);
            return response()->json(['success' => true]);
        })->name('notifications.mark-as-read');

        Route::post('mark-all-read', function () {
            auth()->user()->unreadNotifications->markAsRead();
            return response()->json(['success' => true]);
        })->name('notifications.mark-all-read');

        Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index'])
            ->name('notifications.index');
    });
});

// Include authentication routes
require __DIR__.'/auth.php';