<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class categories extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'user_id',
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
