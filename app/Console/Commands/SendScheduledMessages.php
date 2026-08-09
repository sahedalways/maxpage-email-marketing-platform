<?php

namespace App\Console\Commands;

use App\Helpers\BrevoHelper;
use App\Helpers\SmsHelper;
use App\Helpers\WhatsappHelper;
use App\Jobs\SendAdminEmailJob;
use App\Jobs\SendBrevoSmsJob;
use App\Jobs\SendEmailJob;
use App\Mail\SendEmail;
use App\Models\MessageGateway;
use Illuminate\Console\Command;
use App\Models\Message;
use App\Models\MessageHistory;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use SendinBlue\Client\Api\TransactionalSMSApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendTransacSms;
use Twilio\Rest\Client as TwilioClient;

class SendScheduledMessages extends Command
{
    protected $signature = 'send:scheduled-messages';
    protected $description = 'Send scheduled messages if their schedule time has passed or matches current time';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $currentTime = Carbon::now();

        $defaultGateway = MessageGateway::where('status', 'default')->where('type', 'email')
            ->first();


        $messagesToSend = Message::where('schedule_at', '<=', $currentTime)
            ->whereHas('messageHistories', function ($query) {
                $query->where('status', 'pending');
            })
            ->get();

        foreach ($messagesToSend as $message) {
            $companyId = $message->company_id ?? 0;

            if ($companyId) {
                $role = 'company';
            } else {
                $role = 'admin';
            }


            if ($message->type == 'email') {
                if ($defaultGateway->is_gateway_type == 'brevo') {
                    BrevoHelper::sendEmail($message->receiver_email, $message->subject, $message->content, $message->id, $defaultGateway, $role);
                } else {
                    if ($role == 'company') {
                        dispatch(new SendEmailJob($message->receiver_email, $message->subject, $message->content, $message->id,  $role, $companyId));
                    } else {
                        dispatch(new SendAdminEmailJob($message->receiver_email, $message->subject, $message->content, $message->id));
                    }
                }

                $this->info("Message to {$message->receiver_email} sent.");
            } else if ($message->type == 'phone') {

                $smsGateway = MessageGateway::where('status', 'default')->where('type', 'sms')
                    ->first();

                $brandName = getApplicationName() ?? 'Maxpage';

                if ($smsGateway && $smsGateway->is_gateway_type == 'twilio') {
                    SmsHelper::sendSms($message->receiver_phone_no, $message->content, $message->id, $smsGateway, $role);
                } else {
                    dispatch(new SendBrevoSmsJob($brandName, $message->receiver_phone_no, $message->content, $message->id, $smsGateway, $role));
                }


                $this->info("Message to {$message->receiver_phone_no} sent.");
            } else {
                $whatsappGateway = MessageGateway::where('status', 'default')->where('type', 'whatsapp')
                    ->first();


                WhatsappHelper::sendWhatsappMessage($message->receiver_phone_no, $message->content, $message->id, $whatsappGateway, $role);



                $this->info("Message to {$message->receiver_phone_no} sent.");
            }
        }

        $this->info('Scheduled messages processed.');
    }
}
