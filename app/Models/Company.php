<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_name',
        'trading_name',
        'nif',
        'sector',
        'company_size',
        'foundation_year',
        'phone',
        'email',
        'website',
        'contact_person',
        'contact_position',
        'address',
        'city',
        'province',
        'country',
        'logo_path',
        'banner_path',
        'description',
        'mission',
        'vision',
        'values',
        'facebook',
        'linkedin',
        'twitter',
        'instagram',
        'has_hse_department',
        'has_esg_policy',
        'hse_manager_name',
        'hse_manager_contact',
        'is_verified',
        'is_premium',
        'is_active',
        'total_jobs_posted',
        'total_views',
        'total_applications',
    ];

    protected $casts = [
        'has_hse_department' => 'boolean',
        'has_esg_policy' => 'boolean',
        'is_verified' => 'boolean',
        'is_premium' => 'boolean',
        'is_active' => 'boolean',
        'foundation_year' => 'integer',
        'total_jobs_posted' => 'integer',
        'total_views' => 'integer',
        'total_applications' => 'integer',
    ];

    protected $appends = [
        'logo_url',
        'banner_url',
        'sector_name',
        'company_size_name',
        'years_in_operation',
        'social_links',
    ];

    // Relacionamentos
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function Trabalho()
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

    // Acessores
    public function getLogoUrlAttribute()
    {
        if ($this->logo_path) {
            return asset('storage/' . $this->logo_path);
        }
        return asset('assets/images/default-company.png');
    }

    public function getBannerUrlAttribute()
    {
        if ($this->banner_path) {
            return asset('storage/' . $this->banner_path);
        }
        return asset('assets/images/default-banner.jpg');
    }

    public function getSectorNameAttribute()
    {
        $sectors = [
            'oil_gas' => 'Óleo & Gás',
            'mining' => 'Mineração',
            'construction' => 'Construção Civil',
            'industry' => 'Indústria',
            'services' => 'Serviços',
            'consulting' => 'Consultoria',
            'energy' => 'Energia',
            'transport' => 'Transporte',
            'logistics' => 'Logística',
            'health' => 'Saúde',
            'education' => 'Educação',
            'agriculture' => 'Agropecuária',
            'technology' => 'Tecnologia',
            'finance' => 'Finanças',
            'other' => 'Outro',
        ];
        
        return $sectors[$this->sector] ?? $this->sector;
    }

    public function getCompanySizeNameAttribute()
    {
        $sizes = [
            'micro' => 'Micro (1-10)',
            'small' => 'Pequena (11-50)',
            'medium' => 'Média (51-200)',
            'large' => 'Grande (201-500)',
            'enterprise' => 'Empresarial (501+)',
        ];
        
        return $sizes[$this->company_size] ?? $this->company_size;
    }

    public function getYearsInOperationAttribute()
    {
        if ($this->foundation_year) {
            return now()->year - $this->foundation_year;
        }
        return null;
    }

    public function getSocialLinksAttribute()
    {
        return [
            'facebook' => $this->facebook,
            'linkedin' => $this->linkedin,
            'twitter' => $this->twitter,
            'instagram' => $this->instagram,
            'website' => $this->website,
        ];
    }

    public function getFullAddressAttribute()
    {
        $parts = [$this->address, $this->city, $this->province, $this->country];
        return implode(', ', array_filter($parts));
    }

    // Métodos
    public function incrementJobsPosted()
    {
        $this->increment('total_jobs_posted');
    }

    public function incrementViews()
    {
        $this->increment('total_views');
    }

    public function incrementApplications()
    {
        $this->increment('total_applications');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeBySector($query, $sector)
    {
        return $query->where('sector', $sector);
    }

    public function scopeByProvince($query, $province)
    {
        return $query->where('province', $province);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('company_name', 'like', "%{$search}%")
              ->orWhere('trading_name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }
}
