<?php

namespace App\Jobs;

use Exception;
use Illuminate\Support\Facades\Http;
use App\Models\MessageHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendBrevoSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $brandName;
    protected $recepientNumber;
    protected $content;
    protected $messageId;
    protected $defaultGateway;
    protected $role;

    public function __construct($brandName, $recepientNumber, $content, $messageId, $defaultGateway, $role)
    {
        $this->brandName = $brandName;
        $this->recepientNumber = $recepientNumber;
        $this->content = $content;
        $this->messageId = $messageId;
        $this->defaultGateway = $defaultGateway;
        $this->role = $role;
    }

    public function handle()
    {

        try {
            $response = Http::withHeaders([
                'api-key' => $this->defaultGateway->brevo_sms_api_key,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post('https://api.brevo.com/v3/transactionalSMS/sms', [
                'sender'    => $this->brandName,
                'recipient' => $this->recepientNumber,
                'content'   => $this->content,
                'type'      => 'transactional'
            ]);

            if ($response->successful()) {
                Log::info("✅ Brevo SMS sent successfully to {$this->recepientNumber}");

                MessageHistory::where('message_id', $this->messageId)->update([
                    'status'        => 'sent',
                    'company_id'    => $this->role == 'company' ? $this->defaultGateway->company_id : null,
                ]);
            } else {
                $errorMsg = $response->json()['message'] ?? 'Unknown Brevo SMS error';
                Log::error("❌ Brevo SMS failed: " . $errorMsg);

                MessageHistory::where('message_id', $this->messageId)->update([
                    'status'        => 'failed',
                    'error_message' => $errorMsg,
                    'company_id'    => $this->role == 'company' ? $this->defaultGateway->company_id : null,
                ]);
            }
        } catch (\Exception $e) {
            Log::error("❌ General SMS sending failed: " . $e->getMessage());

            MessageHistory::where('message_id', $this->messageId)->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
                'company_id'    => $this->role == 'company' ? $this->defaultGateway->company_id : null,
            ]);
        }
    }
}
