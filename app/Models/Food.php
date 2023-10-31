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
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(FoodCategory::class, 'category_id');
    }

    public function discounts()
    {
        return $this->hasMany(FoodDiscount::class);
    }

    public function parties()
    {
        return $this->belongsToMany(FoodParty::class, 'food_party_food');
    }
}
