<?php

use App\Http\Controllers\TransactionDetailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/transactions', [TransactionDetailController::class, 'store']);