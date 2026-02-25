<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relacionamentos
    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function talentProfile()
    {
        return $this->hasOne(HseTalentProfile::class);
    }

    public function trabalhos()
    {
        return $this->hasMany(Trabalho::class);
    }

    public function esgInitiatives()
    {
        return $this->hasMany(EsgInitiative::class);
    }

    public function companyProjects()
    {
        return $this->hasMany(CompanyProject::class);
    }

    public function companyNeeds()
    {
        return $this->hasMany(CompanyNeed::class);
    }

    public function jobPosts()
    {
        return $this->hasMany(JobPost::class);
    }

    // Acessores
    public function getIsAdminAttribute(): bool
    {
        return $this->role === 'admin';
    }

    public function getIsEditorAttribute(): bool
    {
        return in_array($this->role, ['editor', 'admin']);
    }

    public function getIsProfessionalAttribute(): bool
    {
        return $this->role === 'profissional';
    }

    public function getIsCompanyAttribute(): bool
    {
        return $this->role === 'empresa';
    }

    public function getHasCompanyProfileAttribute(): bool
    {
        return $this->isCompany && $this->company()->exists();
    }

    public function getDisplayNameAttribute()
    {
        if ($this->isCompany && $this->company) {
            return $this->company->company_name;
        }
        return $this->name;
    }

    public function getProfileImageAttribute()
    {
        if ($this->isCompany && $this->company) {
            return $this->company->logo_url;
        }
        
        if ($this->isProfessional && $this->talentProfile) {
            return $this->talentProfile->profile_image_url;
        }
        
        return asset('assets/images/default-avatar.jpg');
    }

    // Métodos
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEditor(): bool
    {
        return in_array($this->role, ['editor', 'admin']);
    }

    public function isProfessional(): bool
    {
        return $this->role === 'profissional';
    }

    public function isCompany(): bool
    {
        return $this->role === 'empresa';
    }
}