<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->view('api_docs');
});
