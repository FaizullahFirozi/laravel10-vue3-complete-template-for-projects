<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CrudTestController;
use Illuminate\Support\Facades\Route;
use Spatie\Activitylog\Models\Activity;

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
    // activity()->log('Look mum, I logged something');
    $lastActivity = Activity::all()->last(); //returns the last logged activity
    return $lastActivity->description; //returns 'Look mum, I logged something';

    // return 'this is new value'. '2 + 2 ='. 21 * 12;
});

Route::get('/welcome', function () {
    return view('welcome');
});

Route::resource('/api/crudtest', CrudTestController::class);


Route::get('{view}', ApplicationController::class)->where('view', '.*');
