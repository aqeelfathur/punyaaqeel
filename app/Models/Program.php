<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_program';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_program', 'nama_program', 'deskripsi_program', 'jenis_program'
    ];

    public function details() {
        return $this->hasMany(DetailProgram::class, 'id_program');
    }

    public function userWorkouts() {
        return $this->hasMany(UserWorkout::class, 'program_id');
    }
}

