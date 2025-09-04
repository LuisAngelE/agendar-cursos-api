<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'start_date',
        'end_date',
        'location',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    // Relación inversa uno a muchos con Course: el horario pertenece a un curso
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relación uno a muchos con Reservation: un horario puede tener muchas reservas
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'schedule_id');
    }

    // Scope para filtrar horarios futuros
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }
}
