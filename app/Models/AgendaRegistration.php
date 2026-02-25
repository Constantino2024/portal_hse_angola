<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'agenda_item_id',
        'user_id',
        'name',
        'email',
        'phone',
        'company',
        'notes',
        'registered_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
    ];

    public function agendaItem()
    {
        return $this->belongsTo(AgendaItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
