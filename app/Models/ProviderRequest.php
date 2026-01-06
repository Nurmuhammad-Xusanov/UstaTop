<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderRequest extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'phone',
        'city',
        'service_ids',
    ];

    protected $casts = [
        'service_ids' => 'array',
    ];

    //to fijnd user with id while compacting in controller
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->belongsToMany(Category::class, 'provider_request_category', 'provider_request_id', 'category_id');
    }

    public function categories()
    {
        return Category::whereIn('id', $this->service_ids ?? [])->get();
    }
}
