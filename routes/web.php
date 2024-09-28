<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\RentalController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\CarController as FrontendCarController;
use App\Http\Controllers\Frontend\RentalController as FrontendRentalController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::resource('cars', CarController::class);
    Route::resource('rentals', RentalController::class);
    Route::resource('customers', CustomerController::class);
});

// Frontend Routes
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/cars', [FrontendCarController::class, 'index'])->name('cars.index');
Route::post('/book', [FrontendRentalController::class, 'store'])->name('book');

Route::middleware('auth')->group(function () {
    Route::get('/my-bookings', [FrontendRentalController::class, 'myBookings'])->name('my.bookings');
    Route::delete('/cancel-booking/{id}', [FrontendRentalController::class, 'cancel'])->name('cancel.booking');
});
Auth::routes();

require __DIR__.'/auth.php';
