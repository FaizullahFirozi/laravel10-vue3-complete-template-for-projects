<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RolesController;
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

Route::get('/ip', [UserController::class, 'showIpAddress']); //show ip and mac


Route::get('/test', function () {
    // activity()->log('Look mum, I logged something');
    $lastActivity = Activity::all()->last(); //returns the last logged activity
    return $lastActivity->description; //returns 'Look mum, I logged something';

    // return 'this is new value'. '2 + 2 ='. 21 * 12;
});

Route::get('/welcome', function () {
    return view('welcome');
});


// // only Can Super Admin 
// Route::middleware(['auth', 'role:Super Admin'])->name('admin.')->prefix('admin')->group(function () {
//     Route::resource('/api/roles', RolesController::class);
// });


Route::middleware('auth')->group(function () {

    // CRUD TEST ROUTE SECTION
    // Route::resource('/api/crudtest', CrudTestController::class); //THAT NOT WORK
    Route::get('/api/crudtest', [CrudTestController::class, 'index']);
    Route::get('/api/crudtest/search', [CrudTestController::class, 'search']);
    Route::post('/api/crudtest', [CrudTestController::class, 'store']);
    Route::put('/api/crudtest/{crudTest}', [CrudTestController::class, 'update']);
    Route::delete('/api/crudtest/{crudTest}', [CrudTestController::class, 'destroy']);

    // USER ROUTE SECTION
    Route::get('/api/users', [UserController::class, 'index']);
    Route::get('/api/users/search', [UserController::class, 'search']);
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
    Route::get('/api/activity_log/search', [ActivityLogController::class, 'search']);

    // ROLES GO ABOVE 
    Route::get('/api/roles', [RolesController::class, 'index']);
    Route::get('/api/roles/create', [RolesController::class, 'create']);
    Route::post('/api/roles', [RolesController::class, 'store']);
    Route::get('/api/roles/{id}', [RolesController::class, 'show']);
    Route::get('/api/roles/{id}/edit', [RolesController::class, 'edit']);
    Route::put('/api/roles/{id}', [RolesController::class, 'update']);


    // FOR CHANGE PASSWORD
    Route::post('/api/change-user-password', [ProfileController::class, 'changePassword']);
});

Route::get('{view}', ApplicationController::class)->where('view', '.*')->middleware('auth');
