<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];

extract($_POST); //$id, $status
$system_callback['status'] = (bool)$status;

if ($status) {
  $status = "Activo";
} else {
  $status = "Inactivo";
}

$system_callback['id'] = $id;

$query = "UPDATE Users SET Status = ? WHERE pkIdUsers = ?";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}


$stmt->bind_param('ss', $status, $id);
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
  $system_callback['message'] = "User updated correctly.";
  exit_script($system_callback);
} else {
  $system_callback['code'] = "600";
  $system_callback['query'] = $query;
  $system_callback['message'] = "There was an error modifying the database [$stmt->errno]: $stmt->error.";
  exit_script($system_callback);
}
