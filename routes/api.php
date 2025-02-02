<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtController;

Route::get('/departments', [ArtController::class, 'getDepartments']);
Route::get('/search', [ArtController::class, 'searchArtworks']);
Route::get('/object/{id}', [ArtController::class, 'getObject']);
