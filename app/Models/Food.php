<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'category_id',
        'food_discount_id',
        'restaurant_id',
        'custom_discount',
        'discounted_price'
    ];
    protected $visible = ['discounted_price', 'price', 'count', 'pivot_count'];


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function food_discount()
    {
        return $this->belongsTo(FoodDiscount::class, 'food_discount_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('count');
    }

    public static function calculateDiscountedPrice($originalPrice, $foodDiscountId)
    {
        if ($foodDiscountId !== null) {
            $foodDiscount = FoodDiscount::find($foodDiscountId);
            if ($foodDiscount) {
                $discount = $foodDiscount->discount_percentage;
                return $originalPrice - ($originalPrice * ($discount / 100));
            }
        }

        return $originalPrice;
    }
}
