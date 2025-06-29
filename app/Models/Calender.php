<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calender extends Model
{
    use HasFactory;

    protected $table = 'calenders';
    protected $primaryKey = 'id_calender';

    // Jika tidak menggunakan UUID/increment default
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'tanggal_penuh',
    ];

    // Relasi jika nanti dibutuhkan (misal user_workouts)
    public function userWorkouts()
    {
        return $this->hasMany(UserWorkout::class, 'calender_id', 'id_calender');
    }
}
