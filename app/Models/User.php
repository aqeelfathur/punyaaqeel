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
        'is_admin',
        'profile_image' // Konsisten dengan nama kolom di database
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

    /**
     * Get the profile image URL attribute
     */
    public function getProfileImageUrlAttribute()
    {
        if ($this->profil_image) {
            return asset('storage/' . $this->profil_image);
        }
        
        // Return default avatar jika tidak ada gambar
        return 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face';
    }

    /**
     * Check if user has profile image
     */
    public function hasProfileImage()
    {
        return !empty($this->profil_image);
    }

    // User bisa punya banyak post (komunitas)
    public function posts()
    {
        return $this->hasMany(Post::class, 'id_nama', 'id_nama');
    }

    // User bisa punya banyak like
    public function likes()
    {
        return $this->hasMany(Like::class, 'id_nama', 'id_nama');
    }

    // User bisa punya banyak komentar
    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_nama', 'id_nama');
    }

}