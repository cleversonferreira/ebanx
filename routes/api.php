<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('event')->group(function () {
    Route::post('/', function () {
        echo 'event';
    });
});

Route::prefix('balance')->group(function () {
    Route::get('/', function () {
        echo 'balance';
    });
});
