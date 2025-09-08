<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseScheduleController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/users/fisicas', [UsersController::class, 'indexFisicas']);
Route::post('/store/fisicas', [UsersController::class, 'storeFisica']);
Route::post('/update/fisicas/{id}', [UsersController::class, 'updateFisica']);

Route::get('/users/morales', [UsersController::class, 'indexMorales']);
Route::post('/store/morales', [UsersController::class, 'storeMorales']);
Route::post('/update/morales/{id}', [UsersController::class, 'updateMorales']);

Route::get('/instructores', [UsersController::class, 'instructores']);
Route::resource('/users', UsersController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('/courseSchedule', CourseScheduleController::class);
    Route::resource('/categories', CategoriesController::class);
    Route::resource('/course', CourseController::class);

    Route::post('/courses/{id}/images', [CourseController::class, 'CourseImage']);
    Route::post('/profile/image/update', [AuthController::class, 'profileImageUpdate']);
    Route::post('/resetPassword', [AuthController::class, 'resetPassword']);
    Route::post('/updateProfile', [AuthController::class, 'updateProfile']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = $request->user();

    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'type_user' => $user->type_user,
        'last_name' => $user->last_name,
        'phone' => $user->phone_number,
    ]);
});
