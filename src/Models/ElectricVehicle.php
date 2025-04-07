<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectricVehicle extends Model
{

    protected $table = "electric_vehicle_population_data";

    protected $keyType = 'string';

    protected $primaryKey = 'VIN';

    public $incrementing = false;

    protected $fillable = [
    'VIN',
    'County',
    'City',
    'State',
    'Postal_Code',
    'Model_Year',
    'Make',
    'Model',
    'Electric_Vehicle_Type',
    'CAFV_Eligibility',
    'Electric_Range',
    'Base_MSRP',
    'Legislative_District',
    'DOL_Vehicle_ID',
    'Vehicle_Location',
    'Electric_Utility',
    'Census_Tract'
    ];

    public $timestamps = false;
}
