<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/test', function () {
    return 'this is new value'. '2 + 2 ='. 21 * 12;
});

Route::get('/welcome', function () {
    return view('welcome');
});





Route::get('{view}', ApplicationController::class)->where('view', '.*');