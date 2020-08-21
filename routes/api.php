<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('reset', 'Controller@reset');

Route::prefix('event')->group(function () {
    Route::post('/', 'TransactionsController@event');
});

Route::prefix('balance')->group(function () {
    Route::get('/', 'AccountController@balance');
});
