<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FileController;


Route::get('/', function () {
    return view('welcome');
});


Route::view('/select-directory', 'select-directory');
Route::post('/find-php-files', [FileController::class, 'findPhpFiles'])->name('find-php-files');
