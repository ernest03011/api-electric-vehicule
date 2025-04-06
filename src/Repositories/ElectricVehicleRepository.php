<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ElectricVehicle;

class ElectricVehicleRepository
{

  public function findAll(int $limit = 10, int $offset = 0)
  {
    return ElectricVehicle::select(
      "VIN",
      "Make",
      "Model",
      "Model_Year",
      "Electric_Vehicle_Type",
      "CAFV_Eligibility"
    )->offset($offset)->limit($limit)->get();
  }
  public function find($vin)
  {
    $vehicle = ElectricVehicle::select(
      "VIN",
      "Make",
      "Model",
      "Model_Year",
      "Electric_Vehicle_Type",
      "CAFV_Eligibility"
    )->where("VIN", $vin)->first();

    return $vehicle ? $vehicle->toArray() : [];
  }
  public function delete($vin)
  {
    $vehicle = ElectricVehicle::find($vin);
    $vehicle->delete();
    $vehicle->refresh();
    return "Vehicle with Vin: {$vin} has been deleted!";
  }
  public function update($vin, $data)
  {

    $vehicle = ElectricVehicle::find($vin);
    $vehicle->Make = $data["Make"];
    $vehicle->Model = $data["Model"];
    $vehicle->Model_Year = $data["Model_Year"];
    $vehicle->Electric_Vehicle_Type = $data["Electric_Vehicle_Type"];
    $vehicle->CAFV_Eligibility = $data["CAFV_Eligibility"];

    $vehicle->save();
    $vehicle->refresh();

    return $vehicle;
  }
  public function create($data)
  {
    $vehicle = new ElectricVehicle;
    $vehicle->fill($data);
    $vehicle->save();
    return $vehicle;
  }
}
