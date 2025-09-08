<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_id',
        'category_id',
        'title',
        'description',
        'modality',
        'duration',
        'syllabus_pdf',
    ];

    // Relación inversa uno a muchos con User: el curso pertenece a un instructor
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    // Relación inversa uno a muchos con Category: el curso pertenece a una categoría
    public function category()
    {
        return $this->belongsTo(categories::class, 'category_id');
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

    // Relación polimórfica uno a muchos con Image: un curso puede tener muchas imágenes
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
