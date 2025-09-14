<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;

Route::get('/', fn() => redirect()->route('invoices.index'));

Route::resource('customers', CustomerController::class)->except(['show']);
Route::resource('products',  ProductController::class)->except(['show']);

Route::get('invoices',               [InvoiceController::class, 'index'])->name('invoices.index');
Route::get('invoices/create',        [InvoiceController::class, 'create'])->name('invoices.create');
Route::post('invoices',              [InvoiceController::class, 'store'])->name('invoices.store');
Route::get('invoices/{invoice}',     [InvoiceController::class, 'show'])->name('invoices.show');
Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
Route::patch('invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.status');
