<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'model_id',
        'user_id',
        'title',
        'description',
        'modality',
        'duration',
        'syllabus_pdf',
    ];

    // Relación uno a muchos con Category: un curso pertenece a una categoría
    public function category()
    {
        return $this->belongsTo(categories::class, 'category_id');
    }

    // Relación uno a muchos con Category: un curso pertenece a una categoría
    public function models()
    {
        return $this->belongsTo(Models::class, 'model_id');
    }

    // Relación uno a muchos con Users: un curso pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación uno a muchos con CourseSchedule: un curso puede tener muchos horarios
    public function schedules()
    {
        return $this->hasMany(CourseSchedule::class);
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
