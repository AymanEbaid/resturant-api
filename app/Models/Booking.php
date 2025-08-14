<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'reservation_date',
        'reservation_time',
        'number_of_guests',
        'status',
        'name',
        'contact_number',


    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
