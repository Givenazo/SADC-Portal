<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'status',
        'payment_status',
        'start_date',
        'end_date',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
