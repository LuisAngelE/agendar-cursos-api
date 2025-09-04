<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_id',
        'title',
        'description',
        'modality',
        'capacity',
    ];

    // Relación inversa uno a muchos con User: el curso pertenece a un instructor
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
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
}
