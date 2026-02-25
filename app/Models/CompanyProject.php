<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CompanyProject extends Model
{
    protected $fillable = [
        'user_id',
        'title','slug',
        'client','sector','location',
        'start_date','end_date',
        'excerpt','description',
        'image_path',
        'website',
        'is_active','published_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
        'start_date' => 'date',
        'end_date' => 'date',
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
