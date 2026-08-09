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

class SendWhatsappMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $recipientPhoneNo;
    protected $content;
    protected $messageId;
    protected $defaultGateway;
    protected $role;

    public function __construct($recipientPhoneNo, $content, $messageId, $defaultGateway, $role)
    {
        $this->recipientPhoneNo = $recipientPhoneNo;
        $this->content = $content;
        $this->messageId = $messageId;
        $this->defaultGateway = $defaultGateway;
        $this->role = $role;
    }

    public function handle()
    {
        try {
            if ($this->defaultGateway->is_gateway_type == 'twilio') {
                $accountSid = $this->defaultGateway->twilio_account_sid;
                $authToken = $this->defaultGateway->twilio_auth_token;
                $twilioNumber = $this->defaultGateway->twilio_phone_number;

                $client = new TwilioClient($accountSid, $authToken);
                $client->messages->create(
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

                if ($response->failed()) {
                    throw new Exception($response->body());
                }
            }

            $this->updateHistory('sent', null);
            Log::info("WhatsApp message sent successfully to {$this->recipientPhoneNo}");
        } catch (Exception $e) {
            Log::error("WhatsApp Message sending failed: " . $e->getMessage());
            $this->updateHistory('failed', $e->getMessage());
        }
    }

    protected function updateHistory($status, $errorMessage)
    {
        $data = ['status' => $status];
        if ($errorMessage !== null) {
            $data['error_message'] = $errorMessage;
        }
        if ($this->role == 'company') {
            $data['company_id'] = $this->defaultGateway->company_id;
        }
        MessageHistory::where('message_id', $this->messageId)->update($data);
    }
}
