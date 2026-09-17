<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    use HasFactory;

    protected $fillable  = [
        'email', 'language'
    ];

    protected function casts(): array
    {
        return [
            'language' => 'string',
        ];
    }
}
