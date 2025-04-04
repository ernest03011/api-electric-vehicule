<?php

require_once "vendor/autoload.php";

use Illuminate\Database\Capsule\Manager as Capsule;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$capsule = new Capsule;
$capsule->addConnection([
  "driver" => $_ENV['DB_DRIVER'] ?? "mysql",
  "host" => $_ENV['DB_HOST'] ?? "localhost",
  "database" => $_ENV['DB_NAME'] ?? "",
  "username" => $_ENV['DB_USERNAME'] ?? "root",
  "password" => $_ENV['DB_PASSWORD'] ?? "",
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
