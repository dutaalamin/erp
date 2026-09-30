<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (app()->environment('local')) {
        $user = User::first();
        if ($user) {
            Auth::login($user);
        }
    }
    
    return redirect('/admin');
});

Route::middleware(['auth'])->prefix('pdf')->name('pdf.')->group(function () {
    Route::get('/sales-invoice/{salesInvoice}', [PdfController::class, 'salesInvoice'])->name('sales-invoice');
    Route::get('/purchase-order/{purchaseOrder}', [PdfController::class, 'purchaseOrder'])->name('purchase-order');
    Route::get('/delivery-order/{deliveryOrder}', [PdfController::class, 'deliveryOrder'])->name('delivery-order');
    Route::get('/quotation/{quotation}', [PdfController::class, 'quotation'])->name('quotation');
});
