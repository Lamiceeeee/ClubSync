<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::resource('votes', VoteController::class);
    
    Route::post('votes/{vote}/participate', [VoteController::class, 'participate'])
        ->name('votes.participate');
    
    Route::get('votes/{vote}/results', [VoteController::class, 'results'])
        ->name('votes.results');
});

require __DIR__.'/auth.php';
