<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$id = $_POST['id'];

$system_callback = [
  'data' => "<option>Selecciona un remolque</option>"
];
$query = "SELECT * FROM ct_trailer WHERE deletedTrailer IS NULL";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

// $stmt->bind_param('s', $id);
// if (!($stmt)) {
//   $system_callback['code'] = "500";
//   $system_callback['query'] = $query;
//   $system_callback['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
//   exit_script($system_callback);
// }

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();

if ($rslt->num_rows == 0) {
  $system_callback['code'] = 2;
  $system_callback['message'] = "No se pudo obtener ningun remolque valido";
  exit_script($system_callback);
}

while ($row = $rslt->fetch_assoc()) {
  $system_callback['data'] .=
  "<option value='$row[pkid_trailer]'>$row[trailerNumber]</option>";
}

$system_callback['code'] = 1;
$system_callback['message'] = "Script called successfully!";
exit_script($system_callback);



 ?>
