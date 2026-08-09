<?php

namespace App\Helpers;

use App\Jobs\SendSmsJob;



class SmsHelper
{
  public static function sendSms($recipient, $content, $messageId, $defaultGateway, $role)
  {
    dispatch(new SendSmsJob($recipient, $content, $messageId, $defaultGateway, $role));
  }
}
