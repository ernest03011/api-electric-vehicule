<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ElectricVehicle;

class ElectricVehicleRepository
{

  public function findAll()
  {
    return ElectricVehicle::all();
  }

  // $someVehicles = ElectricVehicle::select(
  //   "VIN",
  //   "Make",
  //   "Model",
  //   "Model_Year",
  //   "Electric_Vehicle_Type",
  //   "CAFV_Eligibility"
  // )->offset(0)->limit(10)->get();
}
