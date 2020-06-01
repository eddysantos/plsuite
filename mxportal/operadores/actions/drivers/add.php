<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];

// foreach ($_POST as $key => $value) {
//   $system_callback['data'] = $_POST;
//   if ($key == "client_street_int_number") {
//     continue;
//   }
//
//   if ($value == "") {
//     $system_callback['code'] = 3;
//     $system_callback['message'] = "Todos los campos deben estar llenos antes de continuar";
//     exit_script($system_callback);
//   }
// }

extract($_POST);

$query = "INSERT INTO ct_drivers(nameFirst, nameSecond, nameLast, nameLastSecond, portal_assignment) VALUES (?,?,?,?,?)";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}


$stmt->bind_param('sssss', $driver_firstName, $driver_secondName, $driver_lastName, $driver_secondLastName, $_SESSION['current_portal']);
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
