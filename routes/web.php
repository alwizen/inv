<?php

use App\Http\Controllers\InvoicePdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/invoice/{invoice}/pdf', [\App\Http\Controllers\InvoicePdfController::class, 'generate'])->name('invoice.pdf');


Route::get('/invoice/{invoice}/pdf',  [InvoicePdfController::class, 'generate'])->name('invoice.pdf');
Route::get('/invoice/{invoice}/pdf2', [InvoicePdfController::class, 'generateV2'])->name('invoice.pdf2');
