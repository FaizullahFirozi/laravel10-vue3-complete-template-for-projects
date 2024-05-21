<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
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

Route::get('csrf', function () {
    return csrf_token();
});


Route::get('/test', function () {
    // activity()->log('Look mum, I logged something');
    $lastActivity = Activity::all()->last(); //returns the last logged activity
    return $lastActivity->description; //returns 'Look mum, I logged something';

    // return 'this is new value'. '2 + 2 ='. 21 * 12;
});

Route::get('/welcome', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    Route::resource('/api/crudtest', CrudTestController::class);


    // USER ROUTE SECTION
    Route::get('/api/users', [UserController::class, 'index']);
    Route::post('/api/users', [UserController::class, 'store']);
    Route::put('/api/users/{user}', [UserController::class, 'update']);
    Route::delete('/api/users/{user}', [UserController::class, 'destroy']);



    // PROFILE ROUTE SECTION
    // Route::resource('/api/profile', ProfileController::class);
    Route::get('/api/profile', [ProfileController::class, 'index']);
    Route::put('/api/profile', [ProfileController::class, 'update']);
    Route::post('/api/upload-profile-image', [ProfileController::class, 'uploadImage']);

    // ACTIVITY LOG
    Route::get('/api/activity_log', [ActivityLogController::class, 'index']);


    // FOR CHANGE PASSWORD
    Route::post('/api/change-user-password', [ProfileController::class, 'changePassword']);
});

Route::get('{view}', ApplicationController::class)->where('view', '.*')->middleware('auth');
