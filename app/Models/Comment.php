<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id','author_name','author_email','body','is_approved',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
