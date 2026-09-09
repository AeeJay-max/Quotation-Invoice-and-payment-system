<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'title',
        'category',
        'file_path',
        'mime_type',
        'file_size',
        'is_private',
        'uploaded_by',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
