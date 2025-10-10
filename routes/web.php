<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/invoice/{invoice}/pdf', [\App\Http\Controllers\InvoicePdfController::class, 'generate'])->name('invoice.pdf');
