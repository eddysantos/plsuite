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
$data = $_POST;

$valor1 = $_POST['linehaul'];
$cipher = "AES-256-CBC";
$key =hash('sha256', "ewgdhfjjluo3pip4l");
$iv = substr(hash('sha256', "sdfkljsadf567890saf"), 0, 16);
$lh_id = openssl_decrypt(base64_decode($valor1),$cipher, $key, 0, $iv);

$get_trip_info = "SELECT tl.lh_number lh_number , b.brokerName broker_name FROM ct_trip_linehaul tl LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker WHERE tl.pk_idlinehaul = ?";

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

$get_conveyance = "SELECT t.truckNumber truck_number , concat(d.nameFirst , ' ' , d.nameLast) driver_name FROM ct_trip_linehaul_movement tlm LEFT JOIN ct_truck t ON tlm.fkid_tractor = t.pkid_truck LEFT JOIN ct_drivers d ON tlm.fkid_driver = d.pkid_driver WHERE tlm.fkid_linehaul = ? ORDER BY tlm.pk_movement_number DESC LIMIT 1";

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

$get_location = "SELECT lat, lon, heading, speed, tran_ts FROM omni_pos_log WHERE tractor = ? ORDER BY tran_ts DESC LIMIT 1";

$get_location = $db->prepare($get_location);
if (!($get_location)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}


if (!($get_location->bind_param('s', $system_callback['data']['truck_number']))) {
  $system_callback['code'] = "500";
  $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($system_callback);
}

if (!($get_location->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($system_callback);
}

$location_info = $get_location->get_result();
$location_info = $location_info->fetch_assoc();

foreach ($location_info as $key => $value) {
  $system_callback['location'][$key] = $value;
}

$system_callback['location']['tran_ts'] = get_time_difference($system_callback['location']['tran_ts']);
$system_callback['lh_id_decrypted'] = $lh_id_decrypted;
$system_callback['lh_id_encrypted'] = $valor1;
$system_callback['datos'] = $data;
$system_callback['code'] = "1";
exit_script($system_callback);
 ?>
