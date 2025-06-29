<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $fillable = [
        'post_id',
        'id_nama',
        'isi_komentar',
    ];

    // Relasi: komentar milik satu post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Relasi: komentar dibuat oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_nama', 'id_nama');
    }
}
