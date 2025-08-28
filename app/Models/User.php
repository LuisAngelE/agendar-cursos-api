<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const Admin = 1;
    const Instructor = 2;
    const Student = 3;

    protected $fillable = [
        'name',
        'email',
        'password',
        'type_user',
        'last_name',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
