<?php

use Illuminate\Support\Facades\Route;



Route::get('/', function () {
        return view('welcome');
    })->name('home');
    
Route::get('mamias/decompose', '\Lubusin\Decomposer\Controllers\DecomposerController@index');