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

    const Fisica = 4;
    const Moral = 5;

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'type_user',
        'phone',
        'type_person',
        'birth_date',
        'curp',
        'rfc',
        'razon_social',
        'representante_legal',
        'domicilio_fiscal',
    ];

    public function imageProfile()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];
}
