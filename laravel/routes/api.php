<?php

use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Api\UserContactController;
use Illuminate\Support\Facades\Route;

Route::post('logar', [UserAuthController::class, 'login'])->name('login');
Route::post('registrar', [UserAuthController::class, 'register'])->name('register');
Route::get('ativar/{token}', [UserAuthController::class, 'activate'])->name('activate');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('atualizar/token', [UserAuthController::class, 'refresh'])->name('token');
    Route::get('deslogar', [UserAuthController::class, 'logout'])->name('logout');
    Route::post('atualizar', [UserAuthController::class, 'refresh']);
    Route::get('contatos', [UserContactController::class, 'show'])->name('show-contact');
    Route::post('criar/contato', [UserContactController::class, 'create'])->name('create.conctact');
    Route::put('editar/contato/{id}', [UserContactController::class, 'update'])->name('updated.contact');
    Route::delete('deletar/contato/{id}', [UserContactController::class, 'delete'])->name('delete.contact');
});


