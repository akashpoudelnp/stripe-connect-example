<?php

use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\EventBookingController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\StripeConnectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'index'])->name('front.index');

# Stripe Connect Onboarding
Route::prefix('/stripe/connect')->name('stripe.connect.')->group(function () {
    Route::get('/{client}', [StripeConnectController::class, 'connect'])->name('connect');
    Route::get('/return/{client}', [StripeConnectController::class, 'return'])->name('return');
    Route::get('/refresh/{client}', [StripeConnectController::class, 'refresh'])->name('refresh');
});

Route::resource('admin/events', AdminEventController::class)->names('admin.events');

Route::prefix('/events/bookings')->name('events.bookings.')->group(function () {
    Route::get('/{event}', [EventBookingController::class, 'create'])->name('create');
    Route::post('/{event}', [EventBookingController::class, 'store'])->name('store');

    // Payment
    Route::get('/payment/{booking}', [EventBookingController::class, 'payment'])->name('payment');
    Route::post('/payment/{booking}/initiate', [EventBookingController::class, 'initiatePayment'])->name('payment.initiate');
    Route::get('/callback/payment', [EventBookingController::class, 'callback'])->name('payment.callback');
});

Route::view('/test/embed', 'embed');
