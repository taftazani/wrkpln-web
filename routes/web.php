<?php

use App\Http\Controllers\Api\FunctionsController;
use App\Http\Controllers\Api\JenisAktifitasController;
use App\Http\Controllers\Api\LevelStructureController;
use App\Http\Controllers\Api\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/export-organization', [OrganizationController::class, 'export']);
Route::get('/export-function', [FunctionsController::class, 'export']);
Route::get('/export-level-structure', [LevelStructureController::class, 'export']);

// Route::post('/jenis-aktifitas/bulk-upload', [JenisAktifitasController::class, 'bulkUpload'])->name('jenis_aktifitas.bulk_upload');
// Route::get('/jenis-aktifitas/export', [JenisAktifitasController::class, 'export'])->name('jenis_aktifitas.export');
