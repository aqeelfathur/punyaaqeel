<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Program extends Model
{
    use HasFactory;

    protected $table = 'programs';
    protected $primaryKey = 'id_program';
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'id_program',
        'nama_program',
        'deskripsi_program',
        'kategori_program',
        'program_images',
        'created_by'
    ];

    /**
     * Get the URL for the program image
     * Checks if the image file actually exists
     * 
     * @return string
     */
    // Relasi dengan User (creator)
    public function creator() {
        return $this->belongsTo(User::class, 'created_by', 'id_nama');
    }

    // Accessor untuk mendapatkan username creator
    public function getCreatedByUsernameAttribute() {
        return $this->creator ? $this->creator->username : null;
    }

    public function getProgramImageUrlAttribute()
    {
        // Jika kolom program_images ada nilai dan file-nya benar-benar ada
        if ($this->program_images && Storage::disk('public')->exists($this->program_images)) {
            return asset('storage/' . $this->program_images);
        }
       
        return asset('assets/imagesprograms.jpeg');
    }

    // Relasi ke model UserWorkout
    public function userWorkouts()
    {
        return $this->hasMany(UserWorkout::class, 'program_id', 'id_program');
    }

    // Relasi ke model User (melalui UserWorkout)
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_workouts', 'program_id', 'user_id')
                    ->withPivot('calender_id');
    }
}