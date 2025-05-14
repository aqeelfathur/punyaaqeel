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
        'id_gerakan', 'nama_gerakan', 'jumlah_repetisi'
    ];

    public function details() {
        return $this->hasMany(DetailProgram::class, 'id_gerakan');
    }
}

