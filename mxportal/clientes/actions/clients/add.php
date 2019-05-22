<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];

foreach ($_POST as $key => $value) {
  $system_callback['data'] = $_POST;
  if ($key == "client_street_int_number") {
    continue;
  }

  if ($value == "") {
    $system_callback['code'] = 3;
    $system_callback['message'] = "All fields must be filled out before continuing.";
    exit_script($system_callback);
  }
}

extract($_POST);

$query = "INSERT INTO mx_clients(client_name, tax_id, client_alias, address_street, address_ext_number, address_int_number, address_locality, address_city, address_state, address_zip_code) VALUES (?,?,?,?,?,?,?,?,? ,?)";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}


$stmt->bind_param('ssssssssss', $client_razonsocial, $client_rfc, $client_alias, $client_street_name, $client_street_ext_number, $client_street_int_number, $client_locality, $client_city, $client_state, $client_zip_code);
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
