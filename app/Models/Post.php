<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'id_nama',
        'isi',
    ];

    // Relasi: satu post dimiliki oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_nama', 'id_nama');
    }

    // Relasi: satu post punya banyak like
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Relasi: satu post punya banyak komentar
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
