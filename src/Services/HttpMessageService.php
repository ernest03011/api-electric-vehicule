<?php

declare(strict_types=1);

namespace App\Services;

class HttpMessageService
{
  public static function Response(array $body = [], int $code = 200, ?string $headerBody = null)
  {
    if ($headerBody) {
      http_response_code($code);
      echo json_encode($body);
      header($headerBody);
    } else {
      http_response_code($code);
      echo json_encode($body);
    }
  }
}
