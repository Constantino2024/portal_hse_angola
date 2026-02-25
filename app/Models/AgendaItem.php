<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AgendaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'excerpt',
        'description',
        'image_path',
        'starts_at',
        'ends_at',
        'location',
        'is_online',
        'registration_enabled',
        'capacity',
        'external_registration_url',
        'is_active',
        'published_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_online' => 'boolean',
        'registration_enabled' => 'boolean',
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function registrations()
    {
        return $this->hasMany(AgendaRegistration::class);
    }

    public function getAvailableSpotsAttribute(): ?int
    {
        if ($this->capacity === null) return null;
        $used = $this->registrations()->count();
        return max(0, (int)$this->capacity - (int)$used);
    }

    protected static function booted(): void
    {
        static::creating(function (AgendaItem $item) {
            if (empty($item->slug)) {
                $item->slug = Str::slug($item->title) . '-' . time();
            }
            if (empty($item->published_at)) {
                $item->published_at = now();
            }
        });

        static::updating(function (AgendaItem $item) {
            if ($item->isDirty('title') && empty($item->slug)) {
                $item->slug = Str::slug($item->title) . '-' . time();
            }
        });
    }
}
