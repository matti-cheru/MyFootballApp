<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;

Route::get('/', [FrontController::class, 'getHome'])->name('home');

Route::get('/user/login', [AuthController::class, 'authentication'])->name('user.login');
Route::post('/user/login', [AuthController::class, 'login'])->name('user.login');
Route::get('/user/logout', [AuthController::class, 'logout'])->name('user.logout');
Route::post('/user/register', [AuthController::class, 'registration'])->name('user.register');
Route::get('/ajaxUser', [AuthController::class, 'ajaxCheckForEmail']);


Route::group(['middleware' => ['authCustom']], function() {

    Route::group(['middleware' => ['isAdmin']], function() {
        Route::get('/field/create', [FieldController::class, 'create'])->name('field.create'); // admin può creare un campo con form
        Route::post('/field', [FieldController::class, 'store'])->name('field.store');         // admin può salvare un campo
        Route::get('/field/{id}/edit', [FieldController::class, 'edit'])->name('field.edit');  // admin può modificare un campo con form
        Route::put('/field/{id}', [FieldController::class, 'update'])->name('field.update');   // admin può salvare le modifiche a un campo
        Route::delete('/field/{id}', [FieldController::class, 'destroy'])->name('field.destroy'); // admin può distruggere un campo
        Route::get('/field/{id}/destroy/confirm', [FieldController::class, 'confirmDestroy'])->name('field.destroy.confirm'); // rotta custom
        Route::get('/ajaxField', [FieldController::class, 'ajaxCheckForFields']); // Se anche questa è solo per admin

        Route::get('/reservation/{id}', [ReservationController::class, 'show'])->name('reservation.show');
        Route::post('/reservation/{id}/accept', [ReservationController::class, 'accept'])->name('reservation.accept');
        Route::delete('/reservation/{id}', [ReservationController::class, 'reject'])->name('reservation.reject');
        Route::post('/reservation/{id}/reject/confirm', [ReservationController::class, 'confirmReject'])->name('reservation.reject.confirm');     

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/users/{id}/destroy/confirm', [UserController::class, 'confirmDestroy'])->name('users.destroy.confirm');
        Route::get('/users/{id}/reservation', [UserController::class, 'showUserReservations'])->name('users.reservations.history');
    });

    Route::group(['middleware' => ['isRegisteredPlayer']], function() {
        Route::get('/field/{id}/book', [FieldController::class, 'bookThisField'])->name('field.book');

        Route::get('/reseration/create', [ReservationController::class, 'create'])->name('reservation.create');
        Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');
        Route::get('/reservation/{id}/edit', [ReservationController::class, 'edit'])->name('reservation.edit');
        Route::put('/reservation/{id}', [ReservationController::class, 'update'])->name('reservation.update');
        Route::delete('/reservation/{id}', [ReservationController::class, 'destroy'])->name('reservation.destroy');
        Route::get('/reservation/{id}/destroy/confirm', [ReservationController::class, 'confirmDestroy'])->name('reservation.destroy.confirm');

        Route::get('/ajaxReservation', [ReservationController::class, 'ajaxCheckForReservations']);

        Route::get('/users/{id}/myReservation', [UserController::class, 'showMyReservations'])->name('users.myReservations');   
    });

    Route::get('/field', [FieldController::class, 'index'])->name('field.index');
    Route::get('/field/{id}', [FieldController::class, 'show'])->name('field.show');

    Route::get('/reservation', [ReservationController::class, 'index'])->name('reservation.index');
});


Route::fallback(function() {
    return view('errors.404')->with('message','Error 404 - Page not Found!');
});
