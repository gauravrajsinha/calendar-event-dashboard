<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\EventController;

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

// Home routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

// Calendar routes
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
Route::get('/calendar/events', [CalendarController::class, 'getEvents'])->name('calendar.events');

// Event routes
Route::resource('events', EventController::class);
Route::post('/events/{event}/attendee-status', [EventController::class, 'updateAttendeeStatus'])->name('events.attendee-status');

// For the demo, add a simple login route
Route::get('/login', function() {
    // Login the first user (teacher) for demonstration purposes
    auth()->loginUsingId(2); // Ms. Reshma Roychoudhari  (Science Teacher)
    return redirect()->route('calendar.index');
})->name('login');

// Logout route
Route::get('/logout', function() {
    auth()->logout();
    return redirect()->route('home');
})->name('logout');
