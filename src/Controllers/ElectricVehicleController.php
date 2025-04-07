<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\HttpMessageService;
use App\Repositories\ElectricVehicleRepository;

class ElectricVehicleController
{
    private int $httpResponseCode = 200;
    private string $httpResponseHeader = "";
    private array $httpResponseMessage = [];

    public function __construct(
        protected ElectricVehicleRepository $electricVehicleRepository
    ) {
    }

    public function processRequest(string $requestMethod, ?string $vin): void
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

    public function processResourceRequest(string $requestMethod, string $vin): void
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

            $errors = $this->getValidationErrors($data, false);

            if (! empty($errors)) {
                HttpMessageService::response(
                    ["errors" => $errors],
                    422,
                    ""
                );
                exit;
            }
        }

        match ($requestMethod) {
            'get' => (function () use ($vehicle) {
                $this->httpResponseMessage = ["message" => $vehicle];
            })(),
            'delete' => (function () use ($vin) {
                $response = $this->electricVehicleRepository->delete($vin);

                if (! $response) {
                    $this->httpResponseCode = 500;
                    $this->httpResponseMessage =    
                    ["message" => "Something went wrong, try again!"];
                    return;
                }

                $this->httpResponseMessage = 
                ["message" => "Vehicle with Vin number {$vin} has been deleted"];
            })(),
            'patch' => (function () use ($vin, $data) {
                $response = $this->electricVehicleRepository->update($vin, $data);

                if (! $response) {
                    $this->httpResponseCode = 500;
                    $this->httpResponseMessage = 
                    ["message" => "Something went wrong, try again!"];
                    return;
                }

                $this->httpResponseMessage = 
                ["message" => "Vehicle with Vin number {$vin} has been updated"];
            })(),
            default => (function () {
                HttpMessageService::response(
                    [],
                    405,
                    "Allow: GET, PATCH, DELETE"
                );
                exit;
            })(),
        };
    }

    public function processCollectionRequest(string $requestMethod): void
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
                    $this->httpResponseMessage = 
                    ["message" => "Something went wrong, try again!"];
                    return;
                }

                $this->httpResponseMessage = ["message" => $response];
            })(),
            'post' => (function () use ($data) {
                $vin = $this->electricVehicleRepository->create($data);

                if (! $vin) {
                    $this->httpResponseCode = 500;
                    $this->httpResponseMessage = 
                    ["message" => "Something went wrong, try again!"];
                    return;
                }

                $this->httpResponseCode = 201;
                $this->httpResponseMessage = 
                ["message" => "Vehicle with Vin number {$vin} has been created"];
            })(),
            default => (function () {
                HttpMessageService::Response(
                    [],
                    405,
                    "Allow: GET, POST"
                );
                exit;
            })(),
        };
    }

    private function getValidationErrors(array $data, ?bool $isItNew = true): array
    {
        $updateArrayExample = [
        'Make' => 'testing34',
        'Model' => 'testing34',
        'Model_Year' => 2021,
        'Electric_Vehicle_Type' => 'testing34',
        'CAFV_Eligibility' => 'testing34',
        ];

        $newArrayExample = [
        "VIN" => "1G1FZ6S05L4109876",
        "County" => "Spokane",
        "City" => "Spokane",
        "State" => "WA",
        "Postal_Code" => "99201",
        "Model_Year" => 2021,
        "Make" => "Chevrolet",
        "Model" => "Bolt EV",
        "Electric_Vehicle_Type" => "Battery Electric Vehicle (BEV)",
        "CAFV_Eligibility" => "Eligible",
        "Electric_Range" => 259,
        "Base_MSRP" => 36620,
        "Legislative_District" => 3,
        "DOL_Vehicle_ID" => 567891234,
        "Vehicle_Location" => "47.6588,-117.4260",
        "Electric_Utility" => "Avista",
        "Census_Tract" => "53063001500"
        ];

        $errors = [];

        $keysFromInputData = array_keys($data);
        sort($keysFromInputData);


        if ($isItNew) {
            $keysFromNewArrayExample = array_keys($newArrayExample);
            sort($keysFromNewArrayExample);

            if ($keysFromInputData !== $keysFromNewArrayExample) {
                $errors[] = "Data is incorrect";
            }

            return $errors;
        }

        if (! $isItNew) {
            $keysFromUpdateArrayExample = array_keys($updateArrayExample);
            sort($keysFromUpdateArrayExample);


            if ($keysFromInputData !== $keysFromUpdateArrayExample) {
                $errors[] = "Data is incorrect";
            }

            return $errors;
        }
    }
}
