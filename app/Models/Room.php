<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'title',
        'room_code',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function Doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

}
