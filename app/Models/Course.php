<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    const Activo = 1;
    const Inactivo = 2;

    protected $fillable = [
        'category_id',
        'model_id',
        'user_id',
        'title',
        'description',
        'modality',
        'duration',
        'syllabus_pdf',
        'status',
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relación uno a muchos con Category: un curso pertenece a una categoría
    public function category()
    {
        return $this->belongsTo(categories::class, 'category_id');
    }

    // Relación uno a muchos con Models: un curso pertenece a un modelo
    public function models()
    {
        return $this->belongsTo(Models::class, 'model_id');
    }

    // Relación uno a muchos con Users: un curso pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación uno a muchos con EventsSchedule: un curso puede tener muchos horarios
    public function schedules()
    {
        return $this->hasMany(EventsSchedule::class);
    }

    // Relación uno a muchos con Reservation: un curso puede tener muchas reservas
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Relación uno a muchos con ImageCourse: un curso tiene muchas imágenes
    public function images()
    {
        return $this->hasMany(ImageCourse::class);
    }

    public function usersWhoFavorited()
    {
        return $this->belongsToMany(User::class, 'courses_users_favorites')
            ->withTimestamps();
    }
}
