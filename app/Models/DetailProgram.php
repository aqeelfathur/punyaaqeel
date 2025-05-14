<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_program', 'id_gerakan'
    ];

    public function program() {
        return $this->belongsTo(Program::class, 'id_program');
    }

    public function gerakan() {
        return $this->belongsTo(Gerakan::class, 'id_gerakan');
    }
}

