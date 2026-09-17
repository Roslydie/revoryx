<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
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