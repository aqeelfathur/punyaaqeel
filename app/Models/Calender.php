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
        return $this->hasMany(UserWorkout::class, 'calender_id');
    }
}

