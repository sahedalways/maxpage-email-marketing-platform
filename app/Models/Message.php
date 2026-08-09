<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gateway_id',
        'content',
        'subject',
        'receiver_email',
        'receiver_phone_no',
        'schedule_at',
        'type',
        'is_guest',
        'company_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function gateway()
    {
        return $this->belongsTo(MessageGateway::class, 'gateway_id');
    }


    public function messageHistories()
    {
        return $this->hasMany(MessageHistory::class, 'message_id');
    }

    protected static function booted()
    {
        static::addGlobalScope('filterByRole', function (Builder $builder) {
            if (auth()->check()) {
                if (auth()->user()->role === 'guest') {
                    $builder->where('messages.is_guest', true)
                        ->where('messages.company_id', auth()->user()->id);
                } else if (auth()->user()->role === 'company') {
                    $builder->where('messages.company_id', auth()->user()->id);
                } else {
                    $builder->where('messages.is_guest', false);
                }
            }
        });
    }
}
