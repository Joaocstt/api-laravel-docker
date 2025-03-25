<?php

use App\Http\Controllers\User\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('me', [AuthController::class, 'me']);
});
