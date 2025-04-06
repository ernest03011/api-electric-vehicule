<?php

declare(strict_types=1);

namespace App;

use App\Controllers\ElectricVehicleController;
use App\Repositories\ElectricVehicleRepository;
use App\Services\HttpMessageService;

require "../vendor/autoload.php";
require "../bootstrap.php";


$parts = explode("/", $_SERVER["REQUEST_URI"]);

$endpoint = $parts[1];

if ($endpoint !== "electric-vehicles") {
  HttpMessageService::response(
    ["message" => "Path not found"],
    404
  );
  exit;
}

$electricVehicleRepository = new ElectricVehicleRepository();

$requestMethod = $_SERVER['REQUEST_METHOD'];
$id = $parts[2] ?? null;

(new ElectricVehicleController($electricVehicleRepository))->processRequest($requestMethod, $id);
