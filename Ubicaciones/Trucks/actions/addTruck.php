<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];

$owner = $_POST['ow'];
$vin = $_POST['vin'];
$brand = $_POST['br'];
$year = $_POST['ye'];
$tnumber = $_POST['tn'];



$query = "INSERT INTO ct_truck(truckOwnedBy, truckVIN, truckBrand, truckYear, truckNumber) VALUES (?,?,?,?,?)";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}


$stmt->bind_param('sssss', $owner, $vin, $brand, $year, $tnumber);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if ($stmt->affected_rows > 0) {
  $system_callback['code'] = "1";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Query called successfully!";
  exit_script($system_callback);
} else {
  $system_callback['code'] = "600";
  $system_callback['query'] = $query;
  $system_callback['message'] = "No data was added to database [$db->errno]: $db->error.";
  exit_script($system_callback);
}





 ?>
