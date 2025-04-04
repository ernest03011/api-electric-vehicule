<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ElectricVehicleRepository;

class ElectricVehicleController
{
  public function __construct(
    protected ElectricVehicleRepository $electricVehicleRepository
  ) {}

  public function processRequest(string $requestMethod, ?string $id)
  {
    $testingData = [
      'method' => $requestMethod,
      'id' => $id
    ];
    echo json_encode(["message" => $testingData]);
    exit;
  }
}
