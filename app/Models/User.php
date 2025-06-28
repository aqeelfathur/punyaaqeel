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
        'password',
        'email',
        'phone_number', 
        'is_admin'  // Konsisten dengan nama kolom
    ];

    protected $hidden = [
        'password',
    ];

    // Tambahkan casting untuk is_admin
    protected $casts = [
        'is_admin' => 'boolean',
    ];

    public function createdPrograms() {
        return $this->hasMany(Program::class, 'created_by', 'id_nama');
    }

    // Jika ada relasi dengan Gerakan juga
    public function createdGerakans() {
        return $this->hasMany(Gerakan::class, 'created_by', 'id_nama');
    }
}