<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'image',
        'slug',
        'title',
        'description',
        'content',
        'status',
        'user_id',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_tag');
    }


    public function user()
    {
        return $this->belongsTo(User::class);
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