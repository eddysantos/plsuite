<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];

$data = $_POST;

$campos = "";
$values = [];
$s_fields = '';

$params = [];
foreach ($data as $field => $value) {
  // if ($field == "fkid_user") {
  //   continue;
  // }
  $d_fields .= "?,";
  $s_fields .= "ss";
  $values[] = $value;
  $campos .= "$field,";
  $campos_update .= "$field = ?,";
}

$campos = rtrim($campos, ",");
$d_fields = rtrim($d_fields, ",");
$campos_update = rtrim($campos_update, ",");


// $s_fields .= "s";
$params[] =& $s_fields;
foreach ($values as $i => $value) {
  $params[] =& $values[$i];
}
foreach ($values as $i => $value) {
  $params[] =& $values[$i];
}



// $params[] =& $data['fkid_user'];
// $bind_params =& $params;
$query = "INSERT INTO users_permisos ($campos) VALUES ($d_fields) ON DUPLICATE KEY UPDATE $campos_update";
// $query = "UPDATE users_permisos SET $campos WHERE fkid_user = ?";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

call_user_func_array(array($stmt, 'bind_param'), $params);

// $stmt->bind_param('sssssss',
//   $data['user-first-name'],
//   $data['user-last-name'],
//   $data['user-type'],
//   $data['user-status'],
//   $data['user-email'],
//   $data['user-broker'],
//   $data['user-pkid']
// );

if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['params'] = $params;
  $system_callback['message'] = "Error during query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if ($stmt->affected_rows > 0) {
  $system_callback['code'] = 1;
  $system_callback['query'] = $query;
  $system_callback['message'] = "Query called successfully!";
  exit_script($system_callback);
} else {
  $system_callback['code'] = 2;
  $system_callback['params'] = $params;
  $system_callback['message'] = "No data was added/modified to database [$db->errno]: $db->error.";
  exit_script($system_callback);
}



 ?>
