<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('reset', function () {
    echo 'reset';
});

Route::prefix('event')->group(function () {
    Route::post('/', function () {
        echo 'event';
    });
});

Route::prefix('balance')->group(function () {
    Route::get('/', 'AccountController@balance');
});
