<?php

namespace App\Helpers;

use App\Jobs\SendWhatsappMessageJob;

class WhatsappHelper
{
  public static function sendWhatsappMessage($recipientPhoneNo, $content, $defaultGateway)
  {
    dispatch(new SendWhatsappMessageJob($recipientPhoneNo, $content, $defaultGateway));
  }
}
