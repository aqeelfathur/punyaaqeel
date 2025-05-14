<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserWorkout extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'program_id', 'calender_id'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id_nama');
    }

    public function program() {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function calender() {
        return $this->belongsTo(Calender::class, 'calender_id');
    }
}

