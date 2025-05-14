<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Komentar extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_komentar';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_komentar', 'isi_komentar', 'jumlah_like_komentar', 'jumlah_balasan_komentar', 'id_parent', 'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id_nama');
    }

    public function parent() {
        return $this->belongsTo(Komentar::class, 'id_parent');
    }

    public function replies() {
        return $this->hasMany(Komentar::class, 'id_parent');
    }
}
