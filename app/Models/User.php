<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Set primary key sesuai dengan migrasi
    protected $primaryKey = 'id_nama';

    protected $fillable = [
        'username',
        'password', // Menggunakan password standar Laravel
        'email',
        'phone_number',
        'is_admin'
    ];

    protected $hidden = [
        'password', // Menggunakan password standar Laravel
    ];
}