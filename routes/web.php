<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update'); // Ensure this line is present

    // Voting System Routes
    Route::resource('votes', VoteController::class);
    Route::post('votes/{vote}/participate', [VoteController::class, 'participate'])
        ->name('votes.participate');
    Route::get('votes/{vote}/results', [VoteController::class, 'results'])
        ->name('votes.results');

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
}); // Make sure this closing brace matches the opening one

require __DIR__.'/auth.php';