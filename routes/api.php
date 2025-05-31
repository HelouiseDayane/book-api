<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BookController;

Route::post('v1/auth/token', [AuthController::class, 'token']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('v1/livros', [BookController::class, 'index']);
    Route::post('v1/livros', [BookController::class, 'store']);
    Route::post('v1/livros/{livro}/importar-indices-xml', [BookController::class, 'importIndicesXml']);
});
