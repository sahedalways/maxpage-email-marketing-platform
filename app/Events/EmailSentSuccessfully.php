<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class EmailSentSuccessfully
{
    use SerializesModels;

    public $email;
    public $status;
    public $errorMessage;

    /**
     * Create a new event instance.
     *
     * @param string $email
     * @param string $status
     * @param string|null $errorMessage
     */
    public function __construct($email, $status, $errorMessage = null)
    {
        $this->email = $email;
        $this->status = $status;
        $this->errorMessage = $errorMessage;
    }
}
