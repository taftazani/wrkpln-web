<?php

use App\Http\Controllers\Api\FunctionsController;
use App\Http\Controllers\Api\LevelStructureController;
use App\Http\Controllers\Api\OrganizationController;
use Illuminate\Support\Facades\Route;
use Tymon\JWTAuth\Facades\JWTAuth;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/export-organization', [OrganizationController::class, 'export']);
Route::get('/export-function', [FunctionsController::class, 'export']);
Route::get('/export-level-structure', [LevelStructureController::class, 'export']);