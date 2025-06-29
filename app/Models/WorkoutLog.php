<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutLog extends Model
{
    use HasFactory;

    protected $table = 'workout_logs';

    protected $fillable = [
        'user_id',
        'program_id',
        'tanggal_workout',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_nama');
    }

    // Relasi ke Program
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id_program');
    }
}
