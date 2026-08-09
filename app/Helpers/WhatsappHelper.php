<?php

namespace App\Helpers;

use App\Jobs\SendWhatsappMessageJob;

class WhatsappHelper
{
  public static function sendWhatsappMessage($recipientPhoneNo, $content, $messageId, $defaultGateway, $role)
  {
    dispatch(new SendWhatsappMessageJob($recipientPhoneNo, $content, $messageId, $defaultGateway, $role));
  }
}
