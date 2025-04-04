<?php

declare(strict_types=1);

namespace App;

require "../vendor/autoload.php";

$parts = explode("/", $_SERVER["REQUEST_URI"]);

$endpoint = $parts[1];

if ($endpoint !== "electric-vehicules") {
  http_response_code(404);
  echo json_encode(["message" => "Path not found"]);
  exit;
}

echo json_encode(["message" => "Manuel, keep going. You are doing great!"]);
