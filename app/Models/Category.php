<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'name_ru',
        'name_en',
    ];

    /**
     * Return localized name based on app locale.
     */
    public function getNameAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !empty($this->attributes['name_en'])) {
            return $this->attributes['name_en'];
        }
        if ($locale === 'ru' && !empty($this->attributes['name_ru'])) {
            return $this->attributes['name_ru'];
        }
        return $value;
    }

    /**
     * Get category name explicitly for a given locale (uz, ru, en).
     */
    public function getLocalizedName(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        if ($locale === 'en' && !empty($this->name_en)) {
            return $this->name_en;
        }
        if ($locale === 'ru' && !empty($this->name_ru)) {
            return $this->name_ru;
        }
        return $this->attributes['name'] ?? $this->name;
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
