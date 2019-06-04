<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$year = date('y', strtotime('today'));

$system_callback = [];
extract($_POST); //Should contain general_data and movements from addOperation_modal.


//Query to fetch tractor database
$tractor_query = "SELECT pkid_truck id, truckNumber, truckPlates FROM ct_truck WHERE portal_assignment = 'mx' AND pkid_truck = ?";

$tractor_query = $db->prepare($tractor_query);
if (!($tractor_query)) {
  $system_callback['code'] = "500";
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$tractor_query->bind_param('s', $generales['tractor']);
if (!($tractor_query)) {
  $system_callback['code'] = "500";
  $system_callback['message'] = "Error during trip variables binding [$insert_trip->errno]: $insert_trip->error";
  exit_script($system_callback);
}

if (!($tractor_query->execute())) {
  $system_callback['code'] = "500";
  $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($system_callback);
}

$rslt = $tractor_query->get_result();

if ($rslt->num_rows == 0) {
  $system_callback['code'] = 2;
  $system_callback['message'] = "Script called successfully but there are no rows to display.";
  exit_script($system_callback);
}
$tractor = $rslt->fetch_assoc();



$db->query('LOCK TABLES mx_trips, mx_carta_porte WRITE;');
$db->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;");

try {

  $db->begin_transaction();

  //Check trips to assign new trip number.
  $check_trips = $db->query("SELECT count(pk_mx_trip) trips FROM mx_trips WHERE pk_mx_trip_year = $year");
  $trip_number_i = $check_trips->fetch_assoc();
  $trip_number_i = $trip_number_i['trips'] + 1;

  $tripno = "MX" . $year . str_pad($trip_number_i, 4, 0, STR_PAD_LEFT);


  $insert_trip = "INSERT INTO mx_trips(pk_mx_trip_number, pk_mx_trip_year, tractor_number, tractor_plates, fk_driver, fk_mx_client) VALUES (?,?,?,?,?,?)";

  $insert_trip = $db->prepare($insert_trip);
  if (!($insert_trip)) {
    $system_callback['code'] = "500";
    $system_callback['message'] = "Error during trip query prepare [$db->errno]: $db->error";
    exit_script($system_callback);
  }


  $insert_trip->bind_param('ssssss', $tripno, $year, $tractor['truckNumber'], $tractor['truckPlates'], $generales['operador'], $generales['cliente']);
  if (!($insert_trip)) {
    $system_callback['code'] = "500";
    $system_callback['message'] = "Error during trip variables binding [$insert_trip->errno]: $insert_trip->error";
    exit_script($system_callback);
  }

  if (!($insert_trip->execute())) {
    $system_callback['code'] = "500";
    $system_callback['message'] = "Error during trip query execution [$insert_trip->errno]: $insert_trip->error";
    exit_script($system_callback);
  }

  if ($insert_trip->affected_rows > 0) {
    $system_callback['code'] = 1;
    $system_callback['message'] = "Viaje agregado exitosamente.";
  } else {
    $system_callback['code'] = "600";
    $system_callback['message'] = "No se pudo agregar el viaje correctamente [$db->errno]: $db->error.";
    exit_script($system_callback);
  }



  $pk_mx_trip = $db->insert_id;


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

  foreach ($movimientos as $k => $movimiento) {

    //Identify the next CP number based on the amount of cps on record.
    $check_cp = $db->query("SELECT count(pk_carta_porte) cps FROM mx_carta_porte WHERE fk_mx_trip = $pk_mx_trip");
    $cp_number_i = $check_cp->fetch_assoc();
    $cp_number_i = $cp_number_i['cps'] + 1;

    $cp_number = $tripno . $cp_number_i;

    if ($movimiento['movimiento_remolque'] != "") {
      $get_trailer->bind_param('s', $movimiento['movimiento_remolque']);
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


    $insert_movement->bind_param('sssssssss', $cp_number, $pk_mx_trip, $movimiento['movimiento_origen'], $movimiento['movimiento_destino'], $trailer['id'], $trailer['number'], $trailer['plates'], $movimiento['movimiento_tipo'], $movimiento['movimiento_clase']);
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
  }

  $system_callback['code'] = 1;
  $system_callback['tripid'] = $pk_mx_trip;
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
