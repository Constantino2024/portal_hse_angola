<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyNeed extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'level',
        'area',
        'availability',
        'province',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
