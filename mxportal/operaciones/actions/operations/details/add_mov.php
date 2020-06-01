<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$year = date('y', strtotime('today'));

$system_callback = [];
extract($_POST); //Should contain general_data and movements from addOperation_modal.

$trailer = array(
  'id'=>NULL,
  'number'=>NULL,
  'plates'=>NULL
);



$db->query('LOCK TABLES mx_trips, mx_carta_porte WRITE;');
$db->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;");

try {

  $db->begin_transaction();


  $insert_movement = "INSERT INTO mx_carta_porte(pk_carta_porte_number, fk_mx_trip, fk_mx_place_origin, fk_mx_place_destination, fk_trailer, trailer_number, trailer_plates, movement_type, movement_class) VALUES (?,?,?,?,?,?,?,?,?)";

  //Query to get trailer details for plates and econ number.


  $insert_movement = $db->prepare($insert_movement);
  if (!($insert_movement)) {
    $system_callback['code'] = "500";
    $system_callback['message'] = "Error during movement query prepare [$db->errno]: $db->error";
    exit_script($system_callback);
  }

  if ($fk_trailer != "") {
    $get_trailer = "SELECT pkid_trailer id, trailerNumber number, trailerPlates plates FROM ct_trailer WHERE pkid_trailer = ?";
    $get_trailer = $db->prepare($get_trailer);
    if (!($insert_movement)) {
      $system_callback['code'] = "500";
      $system_callback['message'] = "Error during trailer query prepare [$db->errno]: $db->error";
      exit_script($system_callback);
    }
  }



  //Identify the next CP number based on the amount of cps on record.
  $check_cp = $db->query("SELECT count(pk_carta_porte) cps FROM mx_carta_porte WHERE fk_mx_trip = $pk_mx_trip");
  $cp_number_i = $check_cp->fetch_assoc();
  $cp_number_i = $cp_number_i['cps'] + 1;

  $tripno = $db->query("SELECT pk_mx_trip_number tripno FROM mx_trips WHERE pk_mx_trip = $pk_mx_trip");
  $tripno = $tripno->fetch_assoc();
  $tripno = $tripno['tripno'];

  $cp_number = $tripno . $cp_number_i;

  if ($fk_trailer != "") {
    $get_trailer->bind_param('s', $fk_trailer);
    if (!($get_trailer)) {
      $system_callback['code'] = "500";
      $system_callback['message'] = "Error during variables binding [$get_trailer->errno]: $get_trailer->error";
      exit_script($system_callback);
    }
    if (!($get_trailer->execute())) {
      $system_callback['code'] = "500";
      $system_callback['message'] = "Error during query execution [$get_trailer->errno]: $get_trailer->error";
      exit_script($system_callback);
    }
    $trailer = $get_trailer->get_result();
    if ($trailer->num_rows > 0) {
      $system_callback['code'] = 1;
      $system_callback['message'] = "Info del trailer extraida exitosamente";
    } else {
      $system_callback['code'] = "600";
      $system_callback['message'] = "No se encontraron los datos de la caja en el movimiento $k:[$db->errno]: $db->error.";
      exit_script($system_callback);
    }
    $trailer = $trailer->fetch_assoc();
  }

  //Query to add movement data.
  $insert_movement->bind_param('sssssssss', $cp_number, $pk_mx_trip, $fk_mx_place_origin, $fk_mx_place_destination, $trailer['id'], $trailer['number'], $trailer['plates'], $movement_type, $movement_class);
  if (!($insert_movement)) {
    $system_callback['code'] = "500";
    $system_callback['message'] = "Error during variables binding [$insert_movement->errno]: $insert_movement->error";
    exit_script($system_callback);
  }

  if (!($insert_movement->execute())) {
    $system_callback['code'] = "500";
    $system_callback['message'] = "Error during query execution [$insert_movement->errno]: $insert_movement->error";
    exit_script($system_callback);
  }

  if ($insert_movement->affected_rows > 0) {
    $system_callback['code'] = 1;
    $system_callback['message'] = "CP agregada exitosamente.";
  } else {
    $system_callback['code'] = "600";
    $system_callback['message'] = "No se pudo agregar el viaje correctamente [$db->errno]: $db->error.";
    exit_script($system_callback);
  }

  $system_callback['code'] = 1;
  $system_callback['message'] = "Viaje agregado exitosamente.";
  $db->commit();
  $db->query('UNLOCK TABLES;');
  exit_script($system_callback);

} catch (\Exception $e) {
  $db->rollback();
  $system_callback['code'] = "2";
  $system_callback['message'] = "Hubo un problema ejecutando el quuery, reportar a soporte técnico: [$db->errno]: $db->error";
  $db->query('UNLOCK TABLES;');
  exit_script($system_callback);
}







 ?>
