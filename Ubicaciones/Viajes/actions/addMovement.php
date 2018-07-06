<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback['data'] = $_POST;

$user_name = $_SESSION['user_info']['NombreUsuario'];

/* Calculate next movement id number */

$query = "SELECT count(pkid_movement) count FROM ct_trip_linehaul_movement WHERE fkid_linehaul = ?";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during INSERT TRIP query prepare [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$stmt->bind_param('s',
$data['lhid']
);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during INSERT TRIP variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during INSERT TRIP query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();

$row = $rslt->fetch_assoc();

$new_mid = $row['count'];

$new_mid += 1;



$query = "INSERT INTO ct_trip_linehaul_movement(fkid_linehaul, origin_city, origin_state, origin_zip, destination_city, destination_state, destination_zip, miles_google, movement_type, extra_stop, eal, fkid_tractor, fkid_driver, fkid_driver_team, pk_movement_number, added_by) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

$stmt = $db->prepare($query);
$data = $_POST;

if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during INSERT TRIP_LINEHAUL_MOVEMENT query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

if (
  !(
    $stmt->bind_param('ssssssssssssssss',
    $data['lhid'],
    $data['ocity'],
    $data['ostate'],
    $data['ozip'],
    $data['dcity'],
    $data['dstate'],
    $data['dzip'],
    $data['miles'],
    $data['type'],
    $data['extra_stop'],
    $data['eal'],
    $data['truck'],
    $data['driver'],
    $data['team'],
    $new_mid,
    $user_name
  )
    )
) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['data'] = $system_callback;
  $system_callback['query']['message'] = "Error during INSERT TRIP_LINEHAUL_MOVEMENT variables binding [$stmt->errno]: $stmt->error";
}

// if (!($stmt)) {
//   $system_callback['query']['code'] = "500";
//   $system_callback['query']['query'] = $query;
//   $system_callback['query']['message'] = "Error during INSERT TRIP_LINEHAUL_MOVEMENT_$i variables binding [$db->errno]: $db->error";
//   break;
// }

if (!($stmt->execute())) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during INSERT TRIP_LINEHAUL_MOVEMENT query execution [$stmt->errno]: $stmt->error";
}
if ($system_callback['query']['code'] == "500") {
  exit_script($system_callback);
}

$query = "UPDATE ct_trip_linehaul SET current_tractor = ? WHERE pk_idlinehaul = ?";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during MODIFY CURRENT TRACTOR query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

if (
  !(
    $stmt->bind_param('ss',
    $data['truck'],
    $data['lhid']
  )
    )
) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['data'] = $system_callback;
  $system_callback['query']['message'] = "Error during MODIFY CURRENT TRACTOR variables binding [$stmt->errno]: $stmt->error";
}

if (!($stmt->execute())) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during INSERT TRIP_LINEHAUL_MOVEMENT query execution [$stmt->errno]: $stmt->error";
}
if ($system_callback['query']['code'] == "500") {
  exit_script($system_callback);
}

$system_callback['query']['code'] = "1";
$system_callback['query']['message'] = "Query happened perfectly!";
exit_script($system_callback);


 ?>
