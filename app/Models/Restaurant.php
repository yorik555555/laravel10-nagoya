<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;  // Sortableのuse宣言を追加

class Restaurant extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [
        'name',
        'image',
        'description',
        'lowest_price',
        'highest_price',
        'postal_code',
        'address',
        'opening_time',
        'closing_time',
        'seating_capacity',
    ];

    public $timestamps = false; // created_at と updated_at を無効化
    
    /**
     * The categories that belong to the restaurant.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_restaurant');
    }

    /**
     * The regular holidays that belong to the restaurant.
     */
    public function regular_holidays()
    {
        return $this->belongsToMany(RegularHoliday::class, 'regular_holiday_restaurant');
    }

    /**
     * Get the reviews for the restaurant.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function ratingSortable($query, $direction)
    {
        return $query->withAvg('reviews', 'score')->orderBy('reviews_avg_score', $direction);
    }

    /**
     * Get the reservations for the restaurant.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Define the many-to-many relationship with users.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'restaurant_user')
                    ->withTimestamps(); // Optional: if you want to maintain created_at and updated_at in the pivot table
    }

    /**
     * Sort restaurants by the number of reservations.
     */
    public function scopePopularSortable($query, $direction)
    {
        return $query->withCount('reservations')->orderBy('reservations_count', $direction);
    }
}
