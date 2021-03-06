<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$id = $_POST['pk_mx_carta_porte'];
$status = "Cancelada";

extract($_POST);

$system_callback = [];
$update_movement = "UPDATE mx_carta_porte SET cp_status = ? WHERE pk_carta_porte = ?";

$update_movement = $db->prepare($update_movement);
if (!($update_movement)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$update_movement->bind_param('ss', $status, $pk_mx_carta_porte);
if (!($update_movement)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during variables binding [$update_movement->errno]: $update_movement->error";
  exit_script($system_callback);
}

if (!($update_movement->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query execution [$update_movement->errno]: $update_movement->error";
  exit_script($system_callback);
}

if ($db->affected_rows == 0) {
  $system_callback['code'] = 2;
  $system_callback['rslt'] = $rslt;
  $system_callback['message'] = "No se detecto ningún cambio al registro.";
  exit_script($system_callback);
}


$system_callback['code'] = 1;
$system_callback['message'] = "Script called successfully!";
exit_script($system_callback);




 ?>
