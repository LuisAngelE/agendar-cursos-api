<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    public function states()
    {
        return $this->belongsTo(State::class);
    }
}
