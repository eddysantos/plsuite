<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];

$broker = $_POST['name'];
$main_contact = $_POST['main_contact'];
$phone = $_POST['main_contact_phone'];
$ext = $_POST['main_contact_extension'];
$cell = $_POST['main_contact_cellphone'];
$email = $_POST['main_contact_email'];
$bh_from = $_POST['businesshours_from'];
$bh_to = $_POST['businesshours_to'];

$query = "INSERT INTO ct_brokers(brokerName, brokerOpsContactName, brokerOpsContactPhone, brokerOpsContactExt, brokerOpsCellPhone, brokerOpsEmail, businessHoursFrom, businessHoursTo) VALUES (?,?,?,?,?,?,?,?)";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}


$stmt->bind_param('ssssssss', $broker, $main_contact, $phone, $ext, $cell, $email, $bh_from, $bh_to);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  //$system_callback['message'] = $_POST;
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
