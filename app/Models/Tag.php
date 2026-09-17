<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
    ];

    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_tag');
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translatable');
    }
    
    public function translate($locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        return $this->translations->where('locale', $locale)->first();
    }
}