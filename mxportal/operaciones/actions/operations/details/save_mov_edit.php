<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$id = $_POST['pk_mx_carta_porte'];

extract($_POST);

//Find trailer information to update on movement record.
$get_trailer = "SELECT pkid_trailer, trailerNumber, trailerPlates FROM ct_trailer WHERE pkid_trailer = ?";

$get_trailer = $db->prepare($get_trailer);
if (!($get_trailer)) {
  $system_callback['code'] = "500";
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$get_trailer->bind_param('s', $fk_trailer);
if (!($get_trailer)) {
  $system_callback['code'] = "500";
  $system_callback['message'] = "Error during variables binding [$get_trailer->errno]: $get_trailer->error";
  exit_script($system_callback);
}

if (!($get_trailer->execute())) {
  $system_callback['code'] = "500";
  $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($system_callback);
}

$trailer = $get_trailer->get_result();

if ($trailer->num_rows == 0) {
  $system_callback['code'] = 2;
  $system_callback['message'] = "No se pudo obtener información del remolque, por favor informe a soporte técnico.";
  exit_script($system_callback);
}

$trailer = $trailer->fetch_assoc();


$system_callback = [];
$update_movement = "UPDATE mx_carta_porte SET (movement_type = ?, fk_mx_place_origin = ?, fk_mx_place_destination = ?, fk_trailer = ?, trailer_number = ?, trailer_plates = ?, movement_class = ?) WHERE pk_mx_carta_porte = ?";

$update_movement = $db->prepare($update_movement);
if (!($update_movement)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$update_movement->errno]: $update_movement->error";
  exit_script($system_callback);
}

$update_movement->bind_param('ssssssss', $movement_type, $fk_mx_place_origin, $fk_mx_place_destination, $fk_trailer, $trailer['trailerNumber'], $trailer['trailerPlates'], $movement_class, $pk_mx_carta_porte);
if (!($update_movement)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during variables binding [$update_movement->errno]: $update_movement->error";
  exit_script($system_callback);
}

if (!($update_movement->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($system_callback);
}

$rslt = $update_movement->get_result();

if ($rslt->affected_rows == 0) {
  $system_callback['code'] = 2;
  $system_callback['message'] = "No se detecto ningún cambio al registro.";
  exit_script($system_callback);
}

$system_callback['code'] = 1;
$system_callback['message'] = "Script called successfully!";
exit_script($system_callback);




 ?>
