<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'slug',
        'category_id',
        'title',
        'client',
        'location',
        'years',
        'images',
        'description',
        'content',
        'status',
        'user_id',
    ];

    protected $casts = [
        'images' => 'array', // ✅ images multiples
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
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
