<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegularHoliday extends Model
{
    use HasFactory;

     /**
     * The restaurants that belong to the regular holiday.
     */
    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'regular_holiday_restaurant');
    }
}
