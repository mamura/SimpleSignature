<?php

use Illuminate\Support\Facades\Route;
use Mamura\SimpleSignature\Http\Controllers\PdfEditorController;

Route::prefix('simple-signature')->group(function () {
    Route::get('/', [PdfEditorController::class, 'index']);
    Route::post('/', [PdfEditorController::class, 'store']);
});
