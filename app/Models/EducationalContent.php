<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EducationalContent extends Model
{
    protected $fillable = [
        'title','slug','level','topic','excerpt','body',
        'cover_image','is_active','is_featured','is_premium','published_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_premium' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_active', true)->whereNotNull('published_at');
    }
}
