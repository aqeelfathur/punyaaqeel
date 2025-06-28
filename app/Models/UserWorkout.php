<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWorkout extends Model
{
    use HasFactory;

    protected $table = 'user_workouts';
    
    protected $fillable = [
        'user_id',
        'program_id',
        'calender_id',
    ];

    // Relasi ke model User - pastikan foreign key sesuai
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_nama'); // Spesifikasi foreign key dan local key
    }

    // Relasi ke model Program
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id_program');
    }

    // Relasi ke model Calender
    public function calender()
    {
        return $this->belongsTo(Calender::class, 'calender_id', 'id_calender');
    }
}