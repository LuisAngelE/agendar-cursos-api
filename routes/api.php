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
Route::post('/registerColaborator', [AuthController::class, 'registerColaborator']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/{collaborator_number}', [AuthController::class, 'loginByNumber']);

Route::get('/users', [UsersController::class, 'index']);
Route::delete('/users/{id}', [UsersController::class, 'destroy']);
Route::delete('/deleteCollaborator/{collaborator_number}', [UsersController::class, 'deleteCollaborator']);
Route::get('/usersShow/{id}', [UsersController::class, 'show']);

Route::get('/coursePublic', [CourseController::class, 'index']);

//Endpoind Fechas en Events
Route::get('/course-schedules/dates', [EventsScheduleController::class, 'getDates']);
Route::get('/course-schedules/dates/admin/{userId}', [EventsScheduleController::class, 'getDatesAdmin']);
Route::get('/course-schedules/dates/{userId}', [EventsScheduleController::class, 'getDatesTypeUser']);
Route::post('/storeDemo', [EventsScheduleController::class, 'storeDemo']);

Route::middleware('auth:sanctum')->group(function () {
    //Get para todos los clientes
    Route::get('/users/students', [UsersController::class, 'indexStudents']);
    //Get para instructores por administrador
    Route::get('/instructores', [UsersController::class, 'instructores']);

    //Usuarios Físicas
    Route::get('/users/fisicas', [UsersController::class, 'indexFisicas']);
    //Alta de usuarios físicas
    Route::post('/store/fisicas', [UsersController::class, 'storeFisica']);
    //Actualización de usuarios físicas
    Route::post('/update/fisicas/{id}', [UsersController::class, 'updateFisica']);

    //Usuarios Morales
    Route::get('/users/morales', [UsersController::class, 'indexMorales']);
    //Alta de usuarios morales
    Route::post('/store/morales', [UsersController::class, 'storeMorales']);
    //Actualización de usuarios morales
    Route::post('/update/morales/{id}', [UsersController::class, 'updateMorales']);

    //Categorías por administrador
    Route::get('/categories/user/{user_id}', [CategoriesController::class, 'indexByUser']);
    //Categorías resource
    Route::resource('/categories', CategoriesController::class);

    //Modelos por administrador
    Route::get('/models/user/{user_id}', [ModelsController::class, 'indexByUser']);
    //Modelos resource
    Route::resource('/modelos', ModelsController::class);

    //Cursos resource
    Route::resource('/course', CourseController::class);
    //Cursos por administrador
    Route::get('/courses/user/{user_id}', [CourseController::class, 'indexByUser']);
    //Cursos por instructor asignado
    Route::get('/indexTypeUserCourse/{id}', [CourseController::class, 'indexTypeUserCourse']);
    //Habilitar un curso
    Route::put('/courses/{id}/enable', [CourseController::class, 'enable']);
    //Deshabilitar un curso
    Route::put('/courses/{id}/disable', [CourseController::class, 'disable']);
    //Subir imagenes de curso
    Route::post('/courses/{id}/images', [ImageCourseController::class, 'courseImageUpload']);
    //Eliminar imagenes de curso
    Route::delete('/courses/{courseId}/images/{imageId}', [ImageCourseController::class, 'deleteCourseImage']);

    //Asignar instructor a curso
    Route::post('/courses/{courseId}/assign-instructor', [EventsScheduleController::class, 'assignInstructor']);
    //Reservaciones por instructor y cliente
    Route::get('/indexTypeUserAgenda/{id}', [EventsScheduleController::class, 'indexTypeUserAgenda']);
    //Reservaciones por administrador
    Route::get('/courseSchedule/TypeUser/{id}', [EventsScheduleController::class, 'index']);
    //Todas las reservaciones
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
    Route::get('/countModels', [GraphicsController::class, 'countModels']);
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
