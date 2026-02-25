<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Trabalho extends Model
{
    protected $fillable = [
        'user_id',
        'title','slug','company','location','type','level',
        'excerpt','description','requirements',
        'apply_link','apply_email',
        'is_active','is_featured','is_sponsored',
        'published_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_sponsored' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_active', true)->whereNotNull('published_at');
    }
}
