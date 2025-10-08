<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const Admin = 1;
    const Instructor = 2;
    const Student = 3;

    const Fisica = 4;
    const Moral = 5;

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'type_user',
        'phone',
        'collaborator_number',
        'type_person',
        'birth_date',
        'curp',
        'rfc',
        'razon_social',
        'representante_legal',
        'domicilio_fiscal',
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'password',
        'remember_token',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    // Relación polimórfica uno a uno con Image: un usuario puede tener una imagen de perfil
    public function imageProfile()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    // Relación uno a muchos con Course: un instructor puede tener muchos cursos
    public function courses()
    {
        return $this->hasMany(Course::class, 'instructor_id', 'user_id');
    }

    // Relación uno a muchos con Reservation: un estudiante puede tener muchas reservas
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'student_id');
    }

    public function favoriteCourses()
    {
        return $this->belongsToMany(Course::class, 'courses_users_favorites')
            ->withTimestamps();
    }
}
