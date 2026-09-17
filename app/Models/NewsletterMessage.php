<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterMessage extends Model
{
    protected $fillable = [
        'subject_fr',
        'subject_en',
        'content_fr',
        'content_en',
        'status',
        'recipients_count',
        'sent_at',
        'error',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }
}