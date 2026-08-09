<?php

namespace App\Jobs;

use Exception;
use Twilio\Rest\Client; // Use correct Twilio Client
use App\Models\MessageHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $recipient;
    protected $content;
    protected $messageId;
    protected $defaultGateway;
    protected $role;

    public function __construct($recipient, $content, $messageId, $defaultGateway, $role)
    {
        $this->recipient = $recipient;
        $this->content = $content;
        $this->messageId = $messageId;
        $this->defaultGateway = $defaultGateway;
        $this->role = $role;
    }

    public function handle()
    {
        $sid    = $this->defaultGateway->twilio_account_sid;
        $token  = $this->defaultGateway->twilio_auth_token;
        $twilioNumber = $this->defaultGateway->twilio_phone_number;

        $twilio = new Client($sid, $token);

        try {
            $message = $twilio->messages->create(
                $this->recipient,
                [
                    'from' => $twilioNumber,
                    'body' => $this->content,
                ]
            );

            if ($this->role == 'company') {
                MessageHistory::where('message_id', $this->messageId)->update(['status' => 'sent', 'company_id' => $this->defaultGateway->company_id]);
            } else {
                MessageHistory::where('message_id', $this->messageId)->update(['status' => 'sent']);
            }


            Log::info("SMS sent successfully to {$this->recipient}, SID: {$message->sid}");
        } catch (\Twilio\Exceptions\RestException $e) {
            Log::error("Twilio Error: " . $e->getMessage());

            if ($this->role == 'company') {
                MessageHistory::where('message_id', $this->messageId)->update([
                    'status' => 'failed',
                    'company_id' => $this->defaultGateway->company_id,
                    'error_message' => $e->getMessage(),
                ]);
            } else {
                MessageHistory::where('message_id', $this->messageId)->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }

            throw $e;
        } catch (Exception $e) {
            Log::error("General SMS sending failed: " . $e->getMessage());
            if ($this->role == 'company') {
                MessageHistory::where('message_id', $this->messageId)->update(['status' => 'failed', 'company_id' => $this->defaultGateway->company_id]);
            } else {
                MessageHistory::where('message_id', $this->messageId)->update(['status' => 'failed']);
            }
        }
    }
}
