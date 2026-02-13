<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    //Contacts
    Route::apiResource('/profiles', ProfileController::class);

    //Products
    Route::apiResource('/products', ProductController::class);

});

