<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ElectricVehicle;

class ElectricVehicleRepository
{
  public function findAll(int $limit = 10, int $offset = 0): iterable | false
  {
    try {
      return ElectricVehicle::select(
        "VIN",
        "Make",
        "Model",
        "Model_Year",
        "Electric_Vehicle_Type",
        "CAFV_Eligibility"
      )->offset($offset)->limit($limit)->get();
    } catch (\Exception $e) {
      return false;
    }
  }
  public function find(string $vin, ?bool $dataIsReturnedInArrayFormat = true): iterable
  {
    try {

      $vehicle = ElectricVehicle::select(
        "VIN",
        "Make",
        "Model",
        "Model_Year",
        "Electric_Vehicle_Type",
        "CAFV_Eligibility"
      )->where("VIN", $vin)->first();

      if ($dataIsReturnedInArrayFormat) {
        return $vehicle ? $vehicle->toArray() : [];
      }

      return $vehicle;
    } catch (\Exception $e) {
      return [];
    }
  }
  public function delete($vin): bool
  {
    try {
      $vehicle = ElectricVehicle::find($vin);
      $vehicle->delete();
      $vehicle->refresh();
      return true;
    } catch (\Exception $e) {
      return false;
    }
  }

  public function update($vin, $data): bool
  {
    try {
      $vehicle = ElectricVehicle::find($vin);
      $vehicle->Make = $data["Make"];
      $vehicle->Model = $data["Model"];
      $vehicle->Model_Year = $data["Model_Year"];
      $vehicle->Electric_Vehicle_Type = $data["Electric_Vehicle_Type"];
      $vehicle->CAFV_Eligibility = $data["CAFV_Eligibility"];

      $vehicle->save();
      $vehicle->refresh();
      return true;
    } catch (\Exception $e) {
      return false;
    }
  }

  public function create($data): string | false
  {
    try {
      $vehicle = new ElectricVehicle;
      $vehicle->fill($data);
      $vehicle->save();
      return $vehicle->VIN;
    } catch (\Exception $e) {
      return false;
    }
  }
}
