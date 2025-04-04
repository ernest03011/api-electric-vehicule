<?php

declare(strict_types=1);

namespace App;

use Models\ElectricVehicle;

require "../vendor/autoload.php";
require "../bootstrap.php";


$parts = explode("/", $_SERVER["REQUEST_URI"]);

$endpoint = $parts[1];

if ($endpoint !== "electric-vehicules") {
  http_response_code(404);
  echo json_encode(["message" => "Path not found"]);
  exit;
}

$someVehicles = ElectricVehicle::select(
  "VIN",
  "Make",
  "Model",
  "Model_Year",
  "Electric_Vehicle_Type",
  "CAFV_Eligibility"
)->offset(0)->limit(10)->get();

// echo json_encode(["message" => "Manuel, keep going. You are doing great!"]);
echo json_encode(["message" => $someVehicles]);
