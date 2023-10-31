<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'food_id',
        'discount_amount'
    ];

    public function food()
    {
        return $this->belongsTo(Food::class, 'food_id');
    }
}
