<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    protected $fillable = [
        'doctor_id',
        'plan_id',
        'start_date',
        'end_date',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transactions::class);
    }
}
