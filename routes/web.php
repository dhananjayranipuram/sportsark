<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::any('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::any('/admin/get-dashboard-booking-data', [AdminController::class, 'getDashboardBooking']);
    
    Route::any('/admin/reports', [AdminController::class, 'reports']);
    
    Route::any('/admin/ground-list', [AdminController::class, 'grounds']);
    Route::any('/admin/add-ground', [AdminController::class, 'addGrounds']);
    Route::any('/admin/delete-ground', [AdminController::class, 'deleteGround']);
    Route::any('/admin/edit-ground/{id}', [AdminController::class, 'editGround']);

    Route::any('/admin/games-list', [AdminController::class, 'games']);
    Route::any('/admin/add-games', [AdminController::class, 'addGames']);
    Route::any('/admin/delete-game', [AdminController::class, 'deletGame']);
    Route::any('/admin/get-game-data', [AdminController::class, 'getGameData']);
    Route::any('/admin/update-game', [AdminController::class, 'updateGameData']);

    Route::any('/admin/bookings', [AdminController::class, 'showBookings']);

    Route::any('/admin/register', [AdminController::class, 'registration']);

    Route::any('/admin/logout', [LoginController::class, 'logout']);

});

Route::any('/home', [SiteController::class, 'home']);
Route::any('/games', [SiteController::class, 'games']);
Route::any('/grounds', [SiteController::class, 'grounds']);
Route::any('/ground-details', [SiteController::class, 'groundDetails']);
Route::any('/get-ground-timeslot', [SiteController::class, 'groundTimeSlot']);
Route::any('/check-login', [SiteController::class, 'checkLogin']);
Route::any('/send-otp', [SiteController::class, 'sendOtp']);
Route::any('/send-otp-login', [SiteController::class, 'sendOtpLogin']);
Route::any('/verify-otp', [SiteController::class, 'verifyOtp']);
Route::any('/verify-otp-login', [SiteController::class, 'verifyOtpLogin']);

Route::any('/book-ground', [SiteController::class, 'bookGround']);
Route::any('/booking-status/{status}', [SiteController::class, 'statusOfBooking']);

//Clear Cache facade value:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

//To create symbolic link
Route::get('/sym-link', function () {
    Artisan::call('storage:link');
});

require __DIR__.'/auth.php';
