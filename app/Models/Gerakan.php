<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gerakan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_gerakan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_gerakan', 'nama_gerakan', 'created_by'
    ];

    // Relasi dengan DetailProgram
    public function details() {
        return $this->hasMany(DetailProgram::class, 'id_gerakan');
    }

    // Relasi dengan User (creator)
    // Karena primary key User adalah 'id_nama', bukan 'id'
    public function user() {
        return $this->belongsTo(User::class, 'created_by', 'id_nama');
    }

    // Alias untuk relasi user (opsional, untuk konsistensi)
    public function creator() {
        return $this->belongsTo(User::class, 'created_by', 'id_nama');
    }

    // Accessor untuk mendapatkan username creator
    public function getCreatedByUsernameAttribute() {
        return $this->user ? $this->user->username : null;
    }
}