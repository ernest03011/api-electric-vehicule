# Electric Vehicle Population API

This is a simple RESTful API to manage electric vehicle data imported from a CSV file.

## 📂 About the API

The API uses data from the `Electric_Vehicle_Population_Data.csv` file. This data should be imported into a database table named:

- electric_vehicle_population_data

### 🔧 Setup

1. Import the `Electric_Vehicle_Population_Data.csv` into a database table called `electric_vehicle_population_data`.

2. In the project root, create a `.env` file using the provided `.env-example` as a reference.

3. The default development server runs at `localhost:8080`. If you'd like to change it (e.g., to `vehicle.domain.com/api/`), update the `.htaccess` file in the root directory by replacing `localhost:8080` with your desired domain.

---

## 📡 API Endpoint

Only one endpoint is currently available:

- /electric-vehicles


---

## 📘 Example Requests

### ✅ GET All Vehicles

**Request:**
- GET http://localhost:8080/electric-vehicles/


**Response:**
```json
{
  "message": [
    {
      "VIN": "5YJ3E1EA0K",
      "Make": "TESLA",
      "Model": "MODEL 3",
      "Model_Year": 2019,
      "Electric_Vehicle_Type": "Battery Electric Vehicle (BEV)",
      "CAFV_Eligibility": "Clean Alternative Fuel Vehicle Eligible"
    },
    {
      "VIN": "1N4BZ1DV4N",
      "Make": "NISSAN",
      "Model": "LEAF",
      "Model_Year": 2022,
      "Electric_Vehicle_Type": "Battery Electric Vehicle (BEV)",
      "CAFV_Eligibility": "Eligibility unknown as battery range has not been researched"
    }
  ]
}

```

🔍 GET Vehicle by VIN
Request:

GET http://localhost:8080/electric-vehicles/5YJ3E1EA0K

Response:

```json
{
  "message": {
    "VIN": "5YJ3E1EA0K",
    "Make": "TESLA",
    "Model": "MODEL 3",
    "Model_Year": 2019,
    "Electric_Vehicle_Type": "Battery Electric Vehicle (BEV)",
    "CAFV_Eligibility": "Clean Alternative Fuel Vehicle Eligible"
  }
}
```

📝 POST a New Vehicle
Request:

POST http://localhost:8080/electric-vehicles/

```json
Request Body:
{
  "VIN": "1G1FZ6S05L4109876",
  "County": "Spokane",
  "City": "Spokane",
  "State": "WA",
  "Postal_Code": "99201",
  "Model_Year": 2021,
  "Make": "Chevrolet",
  "Model": "Bolt EV",
  "Electric_Vehicle_Type": "Battery Electric Vehicle (BEV)",
  "CAFV_Eligibility": "Eligible",
  "Electric_Range": 259,
  "Base_MSRP": 36620,
  "Legislative_District": 3,
  "DOL_Vehicle_ID": 567891234,
  "Vehicle_Location": "47.6588,-117.4260",
  "Electric_Utility": "Avista",
  "Census_Tract": "53063001500"
}
```

Response:

```json
{
  "message": "Vehicle with Vin number 1G1FZ6S05L4109876 has been created"
}
```

✏️ PATCH (Update) a Vehicle
Request:

PATCH http://localhost:8080/electric-vehicles/1G1FZ6S05L4109876

Updatable Fields:

- Make
- Model
- Model_Year
- Electric_Vehicle_Type
- CAFV_Eligibility

```json

{
  "Make": "testing34",
  "Model": "testing34",
  "Model_Year": 2021,
  "Electric_Vehicle_Type": "testing34",
  "CAFV_Eligibility": "testing34"
}

```

Response:

```json
{
  "message": "Vehicle with Vin number 1G1FZ6S05L4109876 has been updated"
}

```

❌ DELETE a Vehicle
Request:

- DELETE http://localhost:8080/electric-vehicles/1G1FZ6S05L4109876

Response:

```json
{
  "message": "Vehicle with Vin number 1G1FZ6S05L4109876 has been deleted"
}
```

NOTE

    Ensure your database and environment variables are correctly set up before making requests.

All endpoints assume JSON input and output.

📫 Contact

    For questions or issues, feel free to open an issue or contact the project maintainer.


Let me know if you'd like to add authentication instructions, database schema reference, or error examples.