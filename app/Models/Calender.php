<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Calender extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_calender';

    protected $fillable = [
        'tanggal_calender', 'hari_calender', 'tahun_calender', 'status_calender'
    ];

    public function userWorkouts() {
        return $this->hasMany(UserWorkout::class, 'calender_id', 'id_calender');
    }
    
    // Relasi ke model Program (melalui UserWorkout)
    public function programs()
    {
        return $this->hasManyThrough(
            Program::class,
            UserWorkout::class,
            'calender_id',  // Kunci asing pada tabel UserWorkout
            'id_program',   // Kunci primer pada tabel Program
            'id_calender',  // Kunci primer pada tabel Calender
            'program_id'    // Kunci asing pada tabel UserWorkout yang menunjuk ke Program
        );
    }
}