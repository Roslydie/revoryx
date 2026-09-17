<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['nom', 'prenom', 'message', 'published'];
    protected $casts = [
        'published' => 'boolean',
    ];
}
