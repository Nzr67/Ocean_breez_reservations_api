<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $primaryKey = 'room_id';

    protected $fillable = [
        'room_name',
        'room_type',
        'description',
        'price_per_night',
        'max_guests',
        'room_status',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'room_id', 'room_id');
    }
}