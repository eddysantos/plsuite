<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$sc = [];
$data = $_POST;
$user = $_SESSION['user_info']['Nombre'] . " " . $_SESSION['user_info']['Apellido'];

$query = "INSERT INTO collection_notes(created_by, note_text) VALUES(?,?)";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $sc['code'] = "500";
  $sc['message'] = "Error during trailer query prepare [$db->errno]: $db->error";
  exit_script($sc);
}

$stmt->bind_param('ss',
  $data['note_text'],
  $user
);

if (!($stmt)) {
  $sc['code'] = "500";
  $sc['message'] = "Error during trailer variables binding [$stmt->errno]: $stmt->error";
  exit_script($sc);
}

if (!($stmt->execute())) {
  $sc['code'] = "500";
  $sc['message'] = "Error during trailer query execution [$stmt->errno]: $stmt->error";
  exit_script($sc);
}

if ($stmt->affected_rows == 0) {
  $sc['code'] = 2;
  $sc['message'] = "No record was added.";
  exit_script($sc);
} else {
  $sc['code'] = 1;
  $sc['message'] = "Record was updated successfully.";
  exit_script($sc);
}

?>
