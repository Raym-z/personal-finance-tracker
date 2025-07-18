<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SettingsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Settings routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/tag-colors', [SettingsController::class, 'updateTagColors'])->name('settings.tag-colors');
    Route::post('/settings/custom-tags', [SettingsController::class, 'createCustomTag'])->name('settings.custom-tags');
    Route::delete('/settings/custom-tags', [SettingsController::class, 'deleteCustomTag'])->name('settings.delete-custom-tag');
});

Route::resource('transactions', TransactionController::class)->middleware(['auth']);

require __DIR__.'/auth.php';