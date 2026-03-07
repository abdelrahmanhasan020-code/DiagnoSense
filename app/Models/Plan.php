<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stripe\Subscription;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'price',
        'users_limit',
        'duration_days',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
