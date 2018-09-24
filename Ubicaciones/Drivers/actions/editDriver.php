<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];

$fName = $_POST['nameF'];
$lName = $_POST['nameL'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$owner = $_POST['owner'];
$driver = $_POST['driver'];
// $stNumber = $_POST['stNumber'];
// $stName = $_POST['stName'];
// $addrLine2 = $_POST['addrLine2'];
// $city = $_POST['city'];
// $state = $_POST['state'];
// $zip = $_POST['zip'];
// $country = $_POST['country'];
$id = $_POST['id'];
$default_truck = $_POST['default_truck'];
$ppm = $_POST['ppm'];
$status = $_POST['status'];

$query = "UPDATE ct_drivers SET nameFirst = ?, namelast = ?, phoneNumber = ?, email = ?, isOwner = ?, isDriver = ?, default_truck = ?, pay_per_mile = ?, status = ? WHERE pkid_driver = ?";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}


$stmt->bind_param('ssssssssss', $fName, $lName, $phone, $email, $owner, $driver, $default_truck, $ppm, $status, $id);
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
  $system_callback['message'] = "No data was added/modified to database [$db->errno]: $db->error.";
  exit_script($system_callback);
}





 ?>
