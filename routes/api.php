<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseScheduleController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ImageCourseController;
use App\Http\Controllers\ReservationControlller;
use App\Http\Controllers\StateController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/forgotPassword', [AuthController::class, 'forgotPassword']);
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
    //Categorías
    Route::resource('/categories', CategoriesController::class);

    //Cursos
    Route::resource('/course', CourseController::class);
    Route::get('/indexTypeUserCourse/{id}', [CourseController::class, 'indexTypeUserCourse']);
    Route::post('/courses/{id}/images', [ImageCourseController::class, 'courseImageUpload']);

    //Agendación Fecha
    Route::post('/courses/{courseId}/assign-instructor', [CourseScheduleController::class, 'assignInstructor']);
    Route::get('/indexTypeUserAgenda/{id}', [CourseScheduleController::class, 'indexTypeUserAgenda']);
    Route::post('/courseSchedule/{id}/edit', [CourseScheduleController::class, 'update']);
    Route::resource('/courseSchedule', CourseScheduleController::class);

    //Status Reservas
    Route::post('/reservations/{reservationId}/confirm', [ReservationControlller::class, 'confirmReservation']);
    Route::post('/reservations/{reservationId}/cancel', [ReservationControlller::class, 'cancelReservation']);
    Route::post('/reservations/{reservationId}/served', [ReservationControlller::class, 'servedReservation']);
    Route::post('/reservations/{reservationId}/reschedule', [ReservationControlller::class, 'rescheduleReservation']);

    //Auth Controller
    Route::post('/profile/image/update', [AuthController::class, 'profileImageUpdate']);
    Route::post('/updateProfile', [AuthController::class, 'updateProfile']);
    Route::post('/resetPassword', [AuthController::class, 'resetPassword']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{course}', [FavoriteController::class, 'destroy']);

    Route::get('/states/municipalities/{state_id}', [StateController::class, 'getMunicipalitiesByState']);
    Route::get('/states', [StateController::class, 'index']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = $request->user();

    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'last_name' => $user->last_name,
        'email' => $user->email,
        'type_user' => $user->type_user,
        'phone' => $user->phone_number,
        'type_person' => $user->type_person,
    ]);
});
