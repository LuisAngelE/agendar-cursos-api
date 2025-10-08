<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventsSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'instructor_id',
        'course_id',
        'event_type',
        'reference_id',
        'start_date',
        'end_date',
        'location',
        'state_id',
        'municipality_id',
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'updated_at',
        'deleted_at',
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
