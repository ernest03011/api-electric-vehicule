<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ElectricVehicleRepository;
use App\Services\HttpMessageService;

class ElectricVehicleController
{
  public function __construct(
    protected ElectricVehicleRepository $electricVehicleRepository
  ) {}

  public function processRequest(string $requestMethod, ?string $vin)
  {

    if ($vin) {
      $response = $this->processResourceRequest($requestMethod, $vin);
      HttpMessageService::response(["message" => $response]);
      return;
    } else {
      $response = $this->processCollectionRequest($requestMethod);
      HttpMessageService::response(["message" => $response]);
      return;
    }
  }

  public function processResourceRequest(string $requestMethod, ?string $vin)
  {

    $vin = strtoupper($vin);
    $requestMethod = strtolower($requestMethod);

    $vehicle = $this->electricVehicleRepository->find($vin);

    if (count($vehicle) <= 0) {
      HttpMessageService::response(
        ["message" => "Vehicle with this VIN {$vin} was not found"],
        404,
      );
      exit;
    }

    if ($requestMethod === "patch") {
      $data = (array) json_decode(file_get_contents("php://input"), true);
    }

    return match ($requestMethod) {
      'get' => $vehicle,
      'delete' => $this->electricVehicleRepository->delete($vin),
      'patch' => $this->electricVehicleRepository->update($vin, $data),
      default => (function () {
        HttpMessageService::response(
          ["message" => "testing"],
          405,
          "Allow: GET, PATCH, DELETE"
        );
        exit;
      })(),
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
      default => (function () {
        HttpMessageService::Response(
          ["message" => "testing2"],
          405,
          "Allow: GET, POST"
        );
        exit;
      })(),
    };
  }
}
