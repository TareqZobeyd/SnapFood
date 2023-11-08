<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'restaurant_id',
        'user_status',
        'seller_status',
        'total_amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function foods()
    {
        return $this->belongsToMany(Food::class)->withPivot('count');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getAdditionalInfoAttribute()
    {
        $restaurant = $this->restaurant;

        $foods = $this->foods->map(function ($food) {
            return [
                'id' => $food->id,
                'title' => $food->name,
                'count' => $food->pivot->count,
                'price' => $food->discounted_price,
            ];
        });
        return [
            'restaurant' => [
                'title' => $restaurant->name,
            ],
            'foods' => $foods,
        ];
    }
}
