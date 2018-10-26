<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];

$data = $_POST;

$query = "UPDATE Users SET Nombre = ?, Apellido = ?, TipoUsuario = ?, Status = ?, email = ?, fkid_broker = ? WHERE pkIdUsers = ?";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}


$stmt->bind_param('sssssss',
  $data['user-first-name'],
  $data['user-last-name'],
  $data['user-type'],
  $data['user-status'],
  $data['user-email'],
  $data['user-broker'],
  $data['user-pkid']
);

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
