<?php

namespace App\Mail;

use App\Models\Template;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class SendEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subjectLine;
    public $contentBody;
    public $messageId;

    public function __construct($subjectLine, $contentBody, $messageId)
    {
        $this->subjectLine = $subjectLine;
        $this->contentBody = $contentBody;
        $this->messageId = $messageId;
    }

    public function build()
    {

        return $this->subject($this->subjectLine)
            ->view('email.send_custom_email')
            ->with([
                'contentBody' => $this->contentBody,
            ]);
    }
}
