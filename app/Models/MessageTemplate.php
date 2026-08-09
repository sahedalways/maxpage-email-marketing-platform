<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageTemplate extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'content',
        'is_guest',
        'company_id',
    ];

    protected static function booted()
    {
        static::addGlobalScope('filterByRole', function (Builder $builder) {
            if (auth()->check()) {
                if (auth()->user()->role === 'guest') {
                    $builder->where('message_templates.is_guest', true)
                        ->where('message_templates.company_id', auth()->user()->id);
                } else if (auth()->user()->role === 'company') {
                    $builder->where('message_templates.company_id', auth()->user()->id);
                } else {
                    $builder->where('message_templates.is_guest', false)->whereNull('company_id');
                }
            }
        });
    }
}
