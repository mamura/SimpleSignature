<?php

use Illuminate\Support\Facades\Route;
use Mamura\SimpleSignature\Http\Controllers\SimpleSignatureController;

Route::prefix('simple-signature')->group(function () {
    Route::get('/', [SimpleSignatureController::class, 'index']);
    Route::post('/', [SimpleSignatureController::class, 'store']);
});
