<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Like extends Model
{
    use HasFactory;

    protected $table = 'likes';

    protected $fillable = [
        'post_id',
        'id_nama',
    ];

    // Relasi: like dimiliki oleh satu post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Relasi: like dimiliki oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_nama', 'id_nama');
    }
}
