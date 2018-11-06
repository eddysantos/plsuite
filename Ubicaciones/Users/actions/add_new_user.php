<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];

$data = $_POST;

$query = "INSERT INTO Users(Nombre, Apellido, TipoUsuario, NombreUsuario, email, fkid_broker) VALUES(?,?,?,?,?,?)";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}


$stmt->bind_param('ssssss',
  $data['add_user_fname'],
  $data['add_user_lname'],
  $data['add_user_type'],
  $data['add_user_uname'],
  $data['add_user_email'],
  $data['user-broker']
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
  $system_callback['data']['insert_id'] = $db->insert_id;
  $system_callback['message'] = "Query called successfully!";
  exit_script($system_callback);
} else {
  $system_callback['code'] = "600";
  $system_callback['query'] = $query;
  $system_callback['message'] = "No data was added/modified to database [$db->errno]: $db->error.";
  exit_script($system_callback);
}



 ?>
