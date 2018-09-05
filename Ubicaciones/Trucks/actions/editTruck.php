<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];


$owner = (int)$_POST['owner'];
$vin = $_POST['vin'];
$brand = $_POST['brand'];
$year = $_POST['year'];
$plates = $_POST['plates'];
$number = $_POST['number'];
$ppm = $_POST['ppm'];
$as = $_POST['as'];
$tId = $_POST['truck_id'];

$query = "UPDATE ct_truck SET truckOwnedBy = ?, truckVIN = ?, truckBrand = ?, truckYear = ?, truckNumber = ?, truckPlates = ?, pay_per_mile = ?, apply_surcharge = ? WHERE pkid_truck = ?";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}


$stmt->bind_param('sssssssss', $owner, $vin, $brand, $year, $number, $plates, $ppm, $as, $tId);
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
  $system_callback['message'] = "No data was added to database [$stmt->errno]: $stmt->error.";
  exit_script($system_callback);
}
