<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodDiscount extends Model
{
    protected $fillable = [
        'restaurant_id',
        'discount_percentage',
        'food_party',
    ];
    public function foods()
    {
        return $this->hasMany(Food::class);
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

}
