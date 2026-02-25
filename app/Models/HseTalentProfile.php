<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HseTalentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name', 'email', 'phone', 'birth_date', 'nationality',
        'marital_status', 'address', 'education', 'certifications',
        'languages', 'work_experience', 'skills', 'profile_image',
        'current_position', 'years_experience', 'expected_salary',
        'preferred_location', 'drivers_license', 'preferred_areas',
        'preferred_company_types',
        'level', 'area', 'availability', 'province',
        'headline', 'bio', 'cv_path', 'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'education' => 'array',
        'certifications' => 'array',
        'languages' => 'array',
        'work_experience' => 'array',
        'skills' => 'array',
        'preferred_areas' => 'array',
        'preferred_company_types' => 'array',
        'birth_date' => 'date',
        'expected_salary' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->user_id) && auth()->check()) {
                $model->user_id = auth()->id();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Acessor para imagem de perfil
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }
        return asset('assets/images/default-avatar.jpg');
    }

    // Acessor para idade
    public function getAgeAttribute()
    {
        if ($this->birth_date) {
            return now()->diffInYears($this->birth_date);
        }
        return null;
    }

    // Acessor para dados de array vazios
    public function getEducationAttribute($value)
    {
        $data = json_decode($value, true);
        return is_array($data) ? $data : [];
    }

    public function getCertificationsAttribute($value)
    {
        $data = json_decode($value, true);
        return is_array($data) ? $data : [];
    }

    public function getWorkExperienceAttribute($value)
    {
        $data = json_decode($value, true);
        return is_array($data) ? $data : [];
    }

    public function getSkillsAttribute($value)
    {
        $data = json_decode($value, true);
        return is_array($data) ? $data : [];
    }
}