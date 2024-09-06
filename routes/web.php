<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, "index"])->name('home');
Route::post('/lead/store', [LeadController::class, "store"])->name('lead.store');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, "index"])->name('dashboard');
    Route::post('/dashboard/lead/destroy', [LeadController::class, "destroy"])->name('dashboard.lead.destroy');
    Route::post('/dashboard/lead/edit', [LeadController::class, "edit"])->name('dashboard.lead.edit');
    Route::post('/dashboard/lead/edit-status', [LeadController::class, "editStatus"])->name('dashboard.lead.edit.status');

    Route::get('/dashboard/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/dashboard/profile/update-data', [ProfileController::class, 'updateData'])->name('profile.data.update');
});

require __DIR__.'/auth.php';
