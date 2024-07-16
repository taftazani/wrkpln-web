<?php

use App\Http\Controllers\Api\OrganizationController;
use Illuminate\Support\Facades\Route;
use Tymon\JWTAuth\Facades\JWTAuth;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/export', [OrganizationController::class, 'export']);
