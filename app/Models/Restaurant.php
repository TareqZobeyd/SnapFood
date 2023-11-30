<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'address',
        'latitude',
        'longitude',
        'phone',
        'bank_account',
        'user_id',
        'is_open',
        'delivery_cost',
        'working_hours'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'category_id');
    }

    public function food()
    {
        return $this->hasMany(Food::class, 'restaurant_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
