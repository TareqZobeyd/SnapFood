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
        'phone',
        'bank_account'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function foods()
    {
        return $this->hasMany(Food::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}
