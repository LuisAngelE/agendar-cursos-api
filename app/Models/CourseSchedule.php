<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_id',
        'course_id',
        'state_id',
        'municipality_id',
        'start_date',
        'location',
    ];

    // Relación inversa uno a muchos con Course: el horario pertenece a un curso
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    // Relación uno a muchos con Reservation: un horario puede tener muchas reservas
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'schedule_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'municipality_id');
    }
}
