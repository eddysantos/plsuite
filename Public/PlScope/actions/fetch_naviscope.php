<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';
$system_callback['data'] = array();
$system_callback['location'] = array();

function get_time_difference($from, $to = NULL){
  if (!$to) {
    $to = new DateTime();
  }

  $start_date = new DateTime($from);
  $since_start = $to->diff($start_date);

  // var_dump($start_date);
  // var_dump($to);
  // var_dump($since_start);
  // die();

  if ($since_start->days >= 1) {
    return $since_start->days . " day(s) ago";
  }

  if ($since_start->h >= 1) {
    return $since_start->h . " hour(s) ago";
  }

  if ($since_start->i >= 1) {
    return $since_start->i . " minute(s) ago";
  }

  return $since_start->s . " second(s) ago";
}
function parse_activity($activity){
  //1=Off Duty, 2=Sleeper Berth, 3=Driving, 4=On Duty, Not Driving, 5=Off Duty Driving
  switch ($activity) {
    case '1':
      return "Off Duty";
      break;
    case '2':
      return "Sleeper Berth";
      break;
    case '3':
      return "Driving";
      break;
    case '4':
      return "On Duty, Not Driving";
      break;
    case '5':
      return "Off Duty, Driving";
      break;

    default:
      return "Error";
      break;
  }
}

$data = $_POST;

$valor1 = $_POST['linehaul'];
$cipher = "AES-256-CBC";
$key =hash('sha256', "ewgdhfjjluo3pip4l");
$iv = substr(hash('sha256', "sdfkljsadf567890saf"), 0, 16);
$lh_id = openssl_decrypt(base64_decode($valor1),$cipher, $key, 0, $iv);

$get_trip_info = "SELECT tl.lh_number lh_number , b.brokerName broker_name, tl.linehaul_status lh_status, broker_reference broker_reference, destination_zip dzip, destination_city dcity, destination_state dstate, date_arrival, date_delivery FROM ct_trip_linehaul tl LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker WHERE tl.pk_idlinehaul = ?";

$get_trip_info = $db->prepare($get_trip_info);
if (!($get_trip_info)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}


if (!($get_trip_info->bind_param('s', $lh_id))) {
  $system_callback['code'] = "500";
  $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($system_callback);
}

if (!($get_trip_info->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($system_callback);
}

$trip_info = $get_trip_info->get_result();
$trip_info = $trip_info->fetch_assoc();

foreach ($trip_info as $key => $value) {
  $system_callback['data'][$key] = $value;
}

if ($trip_info['lh_status'] == 'Pending') {
  $system_callback['code'] = 2;
  $system_callback['message'] = "The truck has not yet departed from shipper's. Please contact dispatch for more information.";
  exit_script($system_callback);
}

if ($trip_info['lh_status'] == 'Cancelled') {
  $system_callback['code'] = 2;
  $system_callback['message'] = "This trip has been cancelled. Please contact dispatch for more information.";
  exit_script($system_callback);
}

if ($trip_info['lh_status'] == 'Closed') {
  $system_callback['code'] = 2;
  $system_callback['message'] = "This trip has already been closed. Please contact dispatch for more information.";
  exit_script($system_callback);
}

if ($trip_info['date_arrival'] != "" && $trip_info['date_delivery'] == "") {
  $system_callback['code'] = 2;
  $system_callback['message'] = "Your load has already arrived to $trip_info[dcity] $trip_info[dstate], but there's still no delivery information. Please contact dispatch for more details.";
  exit_script($system_callback);
}

if ($trip_info['date_delivery'] != "") {
  $system_callback['code'] = 2;
  $system_callback['message'] = "Your load was delivered on $trip_info[date_delivery].";
  exit_script($system_callback);
}

$get_conveyance = "SELECT t.truckNumber truck_number , concat(d.nameFirst , ' ' , d.nameLast) driver_name, d.omni_login omni_login FROM ct_trip_linehaul_movement tlm LEFT JOIN ct_truck t ON tlm.fkid_tractor = t.pkid_truck LEFT JOIN ct_drivers d ON tlm.fkid_driver = d.pkid_driver WHERE tlm.fkid_linehaul = ? ORDER BY tlm.pk_movement_number DESC LIMIT 1";

$get_conveyance = $db->prepare($get_conveyance);
if (!($get_conveyance)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}


if (!($get_conveyance->bind_param('s', $lh_id))) {
  $system_callback['code'] = "500";
  $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($system_callback);
}

if (!($get_conveyance->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($system_callback);
}

$conveyance_info = $get_conveyance->get_result();
$conveyance_info = $conveyance_info->fetch_assoc();

foreach ($conveyance_info as $key => $value) {
  $system_callback['data'][$key] = $value;
}

// $get_location = "SELECT lat, lon, heading, speed, tran_ts FROM omni_pos_log WHERE tractor = ? ORDER BY tran_ts DESC LIMIT 1";
//
// $get_location = $db->prepare($get_location);
// if (!($get_location)) {
//   $system_callback['code'] = "500";
//   $system_callback['query'] = $query;
//   $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
//   exit_script($system_callback);
// }
//
//
// if (!($get_location->bind_param('s', $system_callback['data']['truck_number']))) {
//   $system_callback['code'] = "500";
//   $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
//   exit_script($system_callback);
// }
//
// if (!($get_location->execute())) {
//   $system_callback['code'] = "500";
//   $system_callback['query'] = $query;
//   $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
//   exit_script($system_callback);
// }
//
// $location_info = $get_location->get_result();
// $location_info = $location_info->fetch_assoc();
//
// foreach ($location_info as $key => $value) {
//   $system_callback['location'][$key] = $value;
// }



require 'get_vehicle_location.php';

$system_callback['location']['lat'] = $gps_response->latitude;
$system_callback['location']['lon'] = $gps_response->longitude;
$system_callback['location']['speed'] = 0;
$system_callback['location']['tran_ts'] = $gps_response->timePositionReport;
$system_callback['location']['NDrivers'] = $gps_response->NDrivers;
// $system_callback['gps'] = $gps_response;

require 'get_driver_clock.php';
$system_callback['clock'] = $driver_clock;
$system_callback['clock']->v_status = parse_activity($driver_clock->Activity);
// if ($system_callback['data']['omni_login'] == "") {
// } else {
//   $system_callback['clock'] = "Unavailable";
// }



$system_callback['location']['tran_ts'] = get_time_difference($system_callback['location']['tran_ts']);
$system_callback['lh_id_decrypted'] = $lh_id_decrypted;
$system_callback['lh_id_encrypted'] = $valor1;
$system_callback['datos'] = $data;
$system_callback['code'] = "1";
exit_script($system_callback);
 ?>
