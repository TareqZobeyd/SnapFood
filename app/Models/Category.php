<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'image'
    ];

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }

    public function foods()
    {
        return $this->hasMany(Food::class);
    }
}
