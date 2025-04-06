<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ElectricVehicleRepository;
use App\Services\HttpMessageService;

class ElectricVehicleController
{
  private int $httpResponseCode = 200;
  private string $httpResponseHeader = "";
  private array $httpResponseMessage = [];

  public function __construct(
    protected ElectricVehicleRepository $electricVehicleRepository
  ) {}

  public function processRequest(string $requestMethod, ?string $vin)
  {

    if ($vin) {
      $this->processResourceRequest($requestMethod, $vin);
    } else {
      $this->processCollectionRequest($requestMethod);
    }

    HttpMessageService::response(
      $this->httpResponseMessage,
      $this->httpResponseCode,
      $this->httpResponseHeader
    );
    exit;
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
      return;
    }

    if ($requestMethod === "patch") {
      $data = (array) json_decode(file_get_contents("php://input"), true);
    }

    match ($requestMethod) {
      'get' => (function () use ($vehicle) {
        $this->httpResponseMessage = ["message" => $vehicle];
      })(),
      'delete' => (function () use ($vin) {
        $response = $this->electricVehicleRepository->delete($vin);

        if (! $response) {
          $this->httpResponseCode = 500;
          $this->httpResponseMessage = ["message" => "Something went wrong, try again!"];
          return;
        }

        $this->httpResponseMessage = ["message" => "Vehicle with Vin number {$vin} has been deleted"];
      })(),
      'patch' => (function () use ($vin, $data) {
        $response = $this->electricVehicleRepository->update($vin, $data);

        if (! $response) {
          $this->httpResponseCode = 500;
          $this->httpResponseMessage = ["message" => "Something went wrong, try again!"];
          return;
        }

        $this->httpResponseMessage = ["message" => "Vehicle with Vin number {$vin} has been updated"];
      })(),
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

    match ($requestMethod) {
      'get' => (function () {
        $response = $this->electricVehicleRepository->findAll();

        if (! $response) {
          $this->httpResponseCode = 500;
          $this->httpResponseMessage = ["message" => "Something went wrong, try again!"];
          return;
        }

        $this->httpResponseMessage = ["message" => $response];
      })(),
      'post' => (function () use ($data) {
        $vin = $this->electricVehicleRepository->create($data);

        if (! $vin) {
          $this->httpResponseCode = 500;
          $this->httpResponseMessage = ["message" => "Something went wrong, try again!"];
          return;
        }

        $this->httpResponseCode = 201;
        $this->httpResponseMessage = ["message" => "Vehicle with Vin number {$vin} has been created"];
      })(),
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
