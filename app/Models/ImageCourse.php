<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'url',
        'type',
    ];

    // Relación: una imagen pertenece a un curso
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
