<?php

namespace App\Jobs;

use App\Mail\SendEmail;
use App\Models\MessageGateway;
use App\Models\MessageHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Event;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $recipientEmail;
    public $subject;
    public $content;
    public $messageId;
    public $role;
    public $companyId;


    public function __construct($recipientEmail, $subject, $content, $messageId, $role, $companyId)
    {
        $this->recipientEmail = $recipientEmail;
        $this->subject = $subject;
        $this->content = $content;
        $this->messageId = $messageId;
        $this->role = $role;
        $this->companyId = $companyId;
    }

    public function handle()
    {
        try {
            if ($this->role == 'company') {
                $defaultGateway = MessageGateway::where('type', 'email')
                    ->where('company_id', $this->companyId)
                    ->where('status', 'default')
                    ->first();


                if ($defaultGateway) {

                    config([
                        'mail.mailers.smtp.transport' => 'smtp',
                        'mail.mailers.smtp.host' => $defaultGateway->mail_host,
                        'mail.mailers.smtp.port' => $defaultGateway->mail_port,
                        'mail.mailers.smtp.encryption' => $defaultGateway->mail_encryption,
                        'mail.mailers.smtp.username' => $defaultGateway->mail_username,
                        'mail.mailers.smtp.password' => $defaultGateway->mail_password,
                        'mail.from.address' => $defaultGateway->mail_gateway_email,
                        'mail.from.name' => $defaultGateway->mail_gateway_name,
                    ]);


                    Mail::to($this->recipientEmail)->send(
                        new SendEmail($this->subject, $this->content, $this->messageId)
                    );
                }
            } else {
                Mail::to($this->recipientEmail)->send(new SendEmail($this->subject, $this->content, $this->messageId));
            }


            MessageHistory::where('message_id', $this->messageId)->update(['status' => 'sent']);

            Log::info("✅ Email sent successfully to {$this->recipientEmail}");
        } catch (\Exception $e) {
            MessageHistory::where('message_id', $this->messageId)->update(['status' => 'failed', 'error_message' => $e->getMessage()]);

            Log::error("❌ Email sending failed to {$this->recipientEmail}: " . $e->getMessage());

            throw $e;
        }
    }
}
