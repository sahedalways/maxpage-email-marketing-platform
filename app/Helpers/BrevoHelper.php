<?php

namespace App\Helpers;

use App\Jobs\SendBrevoEmailJob;

class BrevoHelper
{
  public static function sendEmail($toEmail, $subject, $htmlContent, $messageId, $defaultGateway, $role)
  {
    dispatch(new SendBrevoEmailJob($toEmail, $subject, $htmlContent, $messageId, $defaultGateway, $role));
  }
}
