<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING = 1;
    const STATUS_CONFIRMED = 2;
    const STATUS_CANCELED = 3;
    const STATUS_SERVED = 4;

    protected $fillable = [
        'student_id',
        'course_id',
        'schedule_id',
        'reserved_at',
        'status',
        'cancellation_reason',
        'proof_path',
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'reserved_at' => 'datetime',
    ];

    // Relación inversa uno a muchos con User: la reserva pertenece a un estudiante
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Relación inversa uno a muchos con Course: la reserva pertenece a un curso
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relación inversa uno a muchos con EventsSchedule: la reserva pertenece a un horario específico
    public function schedule()
    {
        return $this->belongsTo(EventsSchedule::class, 'schedule_id');
    }

    // Scope para filtrar reservas por estado (pendiente, confirmada, servida, cancelada)
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
