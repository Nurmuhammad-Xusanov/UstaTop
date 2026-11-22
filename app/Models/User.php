<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'locale',
        'telegram_locale',
        'sync_telegram_lang',
        'sync_theme',
        'telegram_id',
        'google_id',
        'avatar',
        'theme',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'sync_telegram_lang' => 'boolean',
            'sync_theme' => 'boolean',
        ];
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Get avatar image URL or null.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (!empty($this->avatar)) {
            if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://') || str_starts_with($this->avatar, '/')) {
                return $this->avatar;
            }
            return asset($this->avatar);
        }
        return null;
    }

    /**
     * Get user initials (e.g. 'Z', 'E', 'N').
     */
    public function getInitialsAttribute(): string
    {
        $name = trim($this->name ?? '');
        if (empty($name)) {
            return strtoupper(substr($this->email ?? 'U', 0, 1));
        }
        $parts = preg_split('/\s+/', $name);
        if (count($parts) >= 2 && !empty($parts[0]) && !empty($parts[1])) {
            return mb_strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1));
        }
        return mb_strtoupper(mb_substr($name, 0, 1));
    }
}
