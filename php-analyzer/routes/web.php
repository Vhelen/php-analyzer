<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AnalyzerController;


Route::get('/', [AnalyzerController::class, 'index'])->name('home');
Route::post('/analyze', [AnalyzerController::class, 'analyze'])->name('analyze');
Route::get('/report/{id}', [AnalyzerController::class, 'showReport'])->name('report.show');
