<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'mail_gateway_name',
        'mail_gateway_email',
        'mail_gateway_type',
        'mail_host',
        'mail_driver',
        'mail_port',
        'mail_encryption',
        'mail_username',
        'mail_password',
        'mail_api_key',

        'whatsapp_business_name',
        'whatsapp_access_token',
        'whatsapp_no_id',
        'whatsapp_account_id',
        'twilio_phone_number',
        'twilio_auth_token',
        'twilio_account_sid',
        'is_gateway_type',
        'brevo_sms_api_key',


        'key_ident',
        'sms_key',
        'sms_sender_name',
        'sms_type',

        'type',
        'status',
        'is_guest',
        'company_id',
    ];



    protected static function booted()
    {
        static::addGlobalScope('filterByRole', function (Builder $builder) {
            if (auth()->check()) {
                if (auth()->user()->role === 'guest') {
                    $builder->where('message_gateways.is_guest', true)
                        ->where('message_gateways.company_id', auth()->user()->id);
                } else if (auth()->user()->role === 'company') {
                    $builder->where('message_gateways.company_id', auth()->user()->id);
                } else {
                    $builder->where('message_gateways.is_guest', false)->whereNull('company_id');
                }
            }
        });
    }
}
