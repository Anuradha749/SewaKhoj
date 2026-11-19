<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProviderProfileController;
use App\Http\Controllers\ServiceDirectoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', ServiceDirectoryController::class)->name('home');
Route::get('/services', ServiceDirectoryController::class)->name('services.index');

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/provider/profile', [ProviderProfileController::class, 'create'])->name('provider.profile');
    Route::post('/provider/profile', [ProviderProfileController::class, 'store'])->name('provider.profile.store');
});

require __DIR__.'/auth.php';
