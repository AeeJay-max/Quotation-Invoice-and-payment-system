<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'title',
        'organization',
        'position',
        'email',
        'phone',
        'bio',
        'photo_path',
        'social_links',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];

    public function getFullNameAttribute(): string
    {
        return trim("{$this->title} {$this->first_name} {$this->last_name}");
    }

    public function sessions()
    {
        return $this->belongsToMany(EventSession::class, 'session_speakers', 'speaker_id', 'event_session_id')
            ->withPivot('role');
    }
}
