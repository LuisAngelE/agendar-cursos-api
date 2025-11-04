<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EventsScheduleController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\GraphicsController;
use App\Http\Controllers\ImageCourseController;
use App\Http\Controllers\ModelsController;
use App\Http\Controllers\ReservationControlller;
use App\Http\Controllers\StateController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/forgotPassword', [AuthController::class, 'forgotPassword']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/users', [UsersController::class, 'index']);
Route::delete('/users/{id}', [UsersController::class, 'destroy']);
Route::get('/usersShow/{id}', [UsersController::class, 'show']);
Route::get('/coursePublic', [CourseController::class, 'index']);

//Endpoind Fechas en Events
Route::get('/course-schedules/dates', [EventsScheduleController::class, 'getDates']);
Route::get('/course-schedules/dates/admin/{userId}', [EventsScheduleController::class, 'getDatesAdmin']);
Route::get('/course-schedules/dates/{userId}', [EventsScheduleController::class, 'getDatesTypeUser']);
Route::post('/storeDemo', [EventsScheduleController::class, 'storeDemo']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users/students', [UsersController::class, 'indexStudents']);
    Route::get('/instructores/{user_id}', [UsersController::class, 'instructores']);

    Route::get('/users/fisicas', [UsersController::class, 'indexFisicas']);
    Route::post('/store/fisicas', [UsersController::class, 'storeFisica']);
    Route::post('/update/fisicas/{id}', [UsersController::class, 'updateFisica']);

    Route::get('/users/morales', [UsersController::class, 'indexMorales']);
    Route::post('/store/morales', [UsersController::class, 'storeMorales']);
    Route::post('/update/morales/{id}', [UsersController::class, 'updateMorales']);

    //Categorías
    Route::get('/categories/user/{user_id}', [CategoriesController::class, 'indexByUser']);
    Route::resource('/categories', CategoriesController::class);

    //Modelos
    Route::get('/models/user/{user_id}', [ModelsController::class, 'indexByUser']);
    Route::resource('/modelos', ModelsController::class);

    //Cursos
    Route::resource('/course', CourseController::class);
    Route::get('/courses/user/{user_id}', [CourseController::class, 'indexByUser']);
    Route::get('/indexTypeUserCourse/{id}', [CourseController::class, 'indexTypeUserCourse']);
    Route::post('/courses/{id}/images', [ImageCourseController::class, 'courseImageUpload']);
    Route::delete('/courses/{courseId}/images/{imageId}', [ImageCourseController::class, 'deleteCourseImage']);
    Route::put('/courses/{id}/enable', [CourseController::class, 'enable']);
    Route::put('/courses/{id}/disable', [CourseController::class, 'disable']);

    //Agendación Fecha
    Route::post('/courses/{courseId}/assign-instructor', [EventsScheduleController::class, 'assignInstructor']);
    Route::get('/indexTypeUserAgenda/{id}', [EventsScheduleController::class, 'indexTypeUserAgenda']);
    Route::get('/courseSchedule/TypeUser/{id}', [EventsScheduleController::class, 'index']);
    Route::get('/courseSchedule/Allindex', [EventsScheduleController::class, 'Allindex']);
    Route::post('/courseSchedule/{id}/edit', [EventsScheduleController::class, 'update']);
    Route::get('/indexCount/{id}', [EventsScheduleController::class, 'indexCount']);
    Route::get('/indexTypeUserAgendaCount/{id}', [EventsScheduleController::class, 'indexTypeUserAgendaCount']);
    Route::post('/storeByAdmin', [EventsScheduleController::class, 'storeByAdmin']);
    Route::resource('/courseSchedule', EventsScheduleController::class);

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

    //Favoritos
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{course}', [FavoriteController::class, 'destroy']);

    //Estados y municipios
    Route::get('/states/municipalities/{state_id}', [StateController::class, 'getMunicipalitiesByState']);
    Route::get('/states', [StateController::class, 'index']);

    //Graficas
    Route::get('/countCategories', [GraphicsController::class, 'countCategories']);
    Route::get('/countCourse', [GraphicsController::class, 'countCourse']);
    Route::get('/countReservation', [GraphicsController::class, 'countReservation']);
    Route::get('/countUser', [GraphicsController::class, 'countUser']);
});

//Usuario autenticado
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = $request->user();

    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'first_last_name' => $user->first_last_name,
        'second_last_name' => $user->second_last_name,
        'email' => $user->email,
        'type_user' => $user->type_user,
        'phone' => $user->phone_number,
        'type_person' => $user->type_person,
    ]);
});
