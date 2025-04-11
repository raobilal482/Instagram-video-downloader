<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// routes/web.php
use App\Http\Controllers\InstagramDownloaderController;

Route::get('/download', [InstagramDownloaderController::class, 'showForm'])->name('download.form');
Route::post('/download', [InstagramDownloaderController::class, 'download'])->name('download.video');
