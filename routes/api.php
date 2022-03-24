<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// protect routes
Route::middleware('auth:sanctum')->group(function () {
    // controller group
    Route::controller(UserController::class)->group(function () {
        // get authenticate user
        Route::get("/user", 'getUser');
        // logout user
        Route::post("/logout", 'logout');
    });

    // contact controller
    Route::controller(ContactController::class)->group(function () {
        // create contacts
        Route::post("/contact", 'store');
        Route::get("/contact", 'getContact');
        Route::put("/contact/{id}", 'edit');
        Route::patch("/contact/{id}", 'update');
        Route::delete("/contact/{id}", 'delete');
        Route::get("/contact/{search}", 'search');
    });
});

Route::controller(UserController::class)->group(function () {
    // create user
    Route::post('/register', 'create');
    // login user
    Route::post('/login', 'login');
});
