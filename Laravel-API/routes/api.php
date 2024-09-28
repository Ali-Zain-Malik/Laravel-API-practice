<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get("/index", [UserController::class, "index"]);
Route::post("/user/store", [UserController::class, "store"]);
Route::get("/user/show", [UserController::class, "show"]);
Route::post("/user/update/{id}", [UserController::class, "update"]);