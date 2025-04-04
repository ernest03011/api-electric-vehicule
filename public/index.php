<?php

declare(strict_types=1);

namespace App;

use App\Controllers\ElectricVehicleController;
use App\Repositories\ElectricVehicleRepository;

require "../vendor/autoload.php";
require "../bootstrap.php";


$parts = explode("/", $_SERVER["REQUEST_URI"]);

$endpoint = $parts[1];

if ($endpoint !== "electric-vehicules") {
  http_response_code(404);
  echo json_encode(["message" => "Path not found"]);
  exit;
}

$electricVehicleRepository = new ElectricVehicleRepository();

$requestMethod = $_SERVER['REQUEST_METHOD'];
$id = $parts[2] ?? null;

(new ElectricVehicleController($electricVehicleRepository))->processRequest($requestMethod, $id);
