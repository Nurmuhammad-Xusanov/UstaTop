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
}
