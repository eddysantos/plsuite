<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];

extract($_POST);

$query = "INSERT INTO mx_places(fk_mx_client, place_name, place_alias, address_street, address_ext_number, address_int_number, address_locality, address_city, address_state, address_zip_code, place_contact_name, place_contact_email, place_contact_other, place_contact_phone, receiving_hours_from, receiving_hours_to) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}


$stmt->bind_param('ssssssssssssssss', $fk_mx_client, $place_name, $place_alias, $place_street_name, $place_street_ext_number, $place_street_int_number, $place_locality, $place_city, $place_state, $place_zip_code, $place_contact_name, $place_contact_email, $place_contact_other, $place_contact_phone, $receiving_hours_from, $receiving_hours_to);
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
  $system_callback['code'] = 1;
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
