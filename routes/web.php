<?php

use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\TableController as AdminTableController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('table', AdminTableController::class)
        ->names('admin.table');
        
    Route::resource('user', AdminUserController::class)
        ->names('admin.user');

    Route::resource('reservation', AdminReservationController::class)
        ->names('admin.reservation')
        ->only(['index', 'create', 'destroy']);
});

Route::middleware('auth')->group(function () {
    Route::resource('reservation', ReservationController::class)
        ->names('user.reservation')
        ->only(['index', 'create', 'destroy']);

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';
