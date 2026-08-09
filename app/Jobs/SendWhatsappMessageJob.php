<?php

namespace App\Jobs;

use Exception;
use Twilio\Rest\Client as TwilioClient;
use App\Models\MessageHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsAppMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $recipientPhoneNo;
    protected $content;
    protected $defaultGateway;

    public function __construct($recipientPhoneNo, $content, $defaultGateway)
    {
        $this->recipientPhoneNo = $recipientPhoneNo;
        $this->content = $content;
        $this->defaultGateway = $defaultGateway;
    }

    public function handle()
    {
        try {
            if ($this->defaultGateway->is_gateway_type == 'twilio') {
                $accountSid = $this->defaultGateway->twilio_account_sid;
                $authToken = $this->defaultGateway->twilio_auth_token;
                $twilioNumber = $this->defaultGateway->twilio_phone_number;

                $client = new TwilioClient($accountSid, $authToken);
                $message = $client->messages->create(
                    "whatsapp:{$this->recipientPhoneNo}",
                    [
                        'from' => "whatsapp:{$twilioNumber}",
                        'body' => $this->content,
                    ]
                );
            } else {
                $url = 'https://graph.facebook.com/v22.0/' . $this->defaultGateway->whatsapp_account_id . '/messages';

                $response = Http::withToken($this->defaultGateway->whatsapp_access_token)
                    ->post($url, [
                        'messaging_product' => 'whatsapp',
                        'to' => $this->recipientPhoneNo,
                        'type' => 'text',
                        'text' => [
                            'body' => $this->content,
                        ],
                    ]);
            }

            if (auth()->user()->role == 'company') {
                MessageHistory::where('message_id', $message->id)->update(['status' => 'sent', 'company_id' => $this->defaultGateway->company_id]);
            } else {
                MessageHistory::where('message_id', $message->id)->update(['status' => 'sent']);
            }
        } catch (Exception $e) {
            Log::error("WhatsApp Message sending failed: " . $e->getMessage());
            if (auth()->user()->role == 'company') {
                MessageHistory::where('message_id', $message->id)->update(['status' => 'failed', 'company_id' => $this->defaultGateway->company_id, 'error_message' => $e->getMessage()]);
            } else {
                MessageHistory::where('message_id', $message->id)->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            }
        }
    }
}
