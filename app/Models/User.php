<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;
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