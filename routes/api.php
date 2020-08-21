<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('reset', function () {
    return response('OK', 200);
});

Route::prefix('event')->group(function () {
    Route::post('/', 'TransactionsController@event');
});

Route::prefix('balance')->group(function () {
    Route::get('/', 'AccountController@balance');
});
