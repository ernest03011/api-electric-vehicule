<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ElectricVehicleRepository;

class ElectricVehicleController
{
  public function __construct(
    protected ElectricVehicleRepository $electricVehicleRepository
  ) {}

  public function processRequest(string $requestMethod, ?string $vin)
  {

    if ($vin) {
      $response = $this->processResourceRequest($requestMethod, $vin);
      echo json_encode(["message" => $response]);
      return;
    } else {
      $response = $this->processCollectionRequest($requestMethod);
      echo json_encode(["message" => $response]);
    }
  }

  public function processResourceRequest(string $requestMethod, ?string $vin)
  {

    $vin = strtoupper($vin);
    $requestMethod = strtolower($requestMethod);

    if ($requestMethod === "patch") {
      $data = (array) json_decode(file_get_contents("php://input"), true);
    }

    return match ($requestMethod) {
      'get' => $this->electricVehicleRepository->find($vin),
      'delete' => $this->electricVehicleRepository->delete($vin),
      'patch' => $this->electricVehicleRepository->update($vin, $data),
    };
  }

  public function processCollectionRequest(string $requestMethod)
  {
    $requestMethod = strtolower($requestMethod);

    if ($requestMethod === "post") {
      $data = (array) json_decode(file_get_contents("php://input"), true);
    }

    return match ($requestMethod) {
      'get' => $this->electricVehicleRepository->findAll(),
      'post' => $this->electricVehicleRepository->create($data),
    };
  }
}
