<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    //Contacts
    Route::apiResource('/contacts', ContactController::class);

    //Products
    Route::apiResource('/products', ProductController::class);

});

