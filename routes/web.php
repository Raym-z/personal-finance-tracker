<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\BudgetGoalController;
use App\Http\Controllers\ReportsController;

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

    // Budgets & Goals routes
    Route::get('/budgets-goals', [BudgetGoalController::class, 'index'])->name('budgets_goals.index');
    // Budget CRUD
    Route::get('/budgets/create', [BudgetGoalController::class, 'createBudget'])->name('budgets.create');
    Route::post('/budgets', [BudgetGoalController::class, 'storeBudget'])->name('budgets.store');
    Route::get('/budgets/{id}/edit', [BudgetGoalController::class, 'editBudget'])->name('budgets.edit');
    Route::put('/budgets/{id}', [BudgetGoalController::class, 'updateBudget'])->name('budgets.update');
    Route::delete('/budgets/{id}', [BudgetGoalController::class, 'destroyBudget'])->name('budgets.destroy');
    // Goal CRUD
    Route::get('/goals/create', [BudgetGoalController::class, 'createGoal'])->name('goals.create');
    Route::post('/goals', [BudgetGoalController::class, 'storeGoal'])->name('goals.store');
    Route::get('/goals/{id}/edit', [BudgetGoalController::class, 'editGoal'])->name('goals.edit');
    Route::put('/goals/{id}', [BudgetGoalController::class, 'updateGoal'])->name('goals.update');
    Route::delete('/goals/{id}', [BudgetGoalController::class, 'destroyGoal'])->name('goals.destroy');

    // Reports & Insights routes
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
});

Route::resource('transactions', TransactionController::class)->middleware(['auth']);

require __DIR__.'/auth.php';