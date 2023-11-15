<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'message',
        'score',
        'seller_response',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function getAdditionalInfoAttribute()
    {
        $additionalInfo = [
            'author' => [
                'name' => $this->user->name,
            ],
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'score' => $this->score,
            'content' => $this->message,
        ];
        if ($this->order) {
            $additionalInfo['foods'] = $this->order->foods->pluck('name')->all();
        }
        return $additionalInfo;
    }

}
