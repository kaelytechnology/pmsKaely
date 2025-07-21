<?php

use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

 

Route::get('/', [LandingController::class, 'showLandingPage'])->name('landing.page');
Route::post('/register', [LandingController::class, 'registerTenant'])->name('tenant.register');
