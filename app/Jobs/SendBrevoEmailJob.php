<?php

namespace App\Jobs;

use App\Models\MessageHistory;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendBrevoEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $toEmail;
    protected $subject;
    protected $htmlContent;
    protected $messageId;
    protected $defaultGateway;
    protected $role;

    /**
     * Create a new job instance.
     */
    public function __construct($toEmail, $subject, $htmlContent, $messageId, $defaultGateway, $role)
    {
        $this->toEmail = $toEmail;
        $this->subject = $subject;
        $this->htmlContent = $htmlContent;
        $this->messageId = $messageId;
        $this->defaultGateway = $defaultGateway;
        $this->role = $role;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $client = new Client();

        try {
            $response = $client->post('https://api.brevo.com/v3/smtp/email', [
                'headers' => [
                    'api-key' => $this->defaultGateway->mail_api_key,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'sender' => [
                        'name' => config('mail.from.name'),
                        'email' => config('mail.from.address'),
                    ],
                    'to' => [
                        ['email' => $this->toEmail],
                    ],
                    'subject' => $this->subject,
                    'htmlContent' => $this->htmlContent,
                ],
            ]);

            if ($this->role == 'company') {
                MessageHistory::where('message_id', $this->messageId)->update(['status' => 'sent', 'company_id' => $this->defaultGateway->company_id]);
            } else {
                MessageHistory::where('message_id', $this->messageId)->update(['status' => 'sent']);
            }

            return Log::info('Email Sent Successfully', ['response' => $response->getBody()->getContents()]);
        } catch (\Exception $e) {
            if ($this->role == 'company') {
                MessageHistory::where('message_id', $this->messageId)->update(['status' => 'failed', 'company_id' => $this->defaultGateway->company_id, 'error_message' => $e->getMessage()]);
            } else {
                MessageHistory::where('message_id', $this->messageId)->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            }
            return Log::error('Email Sending Failed', ['error' => $e->getMessage()]);
        }
    }
}
