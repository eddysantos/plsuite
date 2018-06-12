<?php


$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

function numberify($number){
  return number_format($number, 2);
}

$system_callback = [];
$data = $_POST;

$b_date = date('Y-m-d H:i:s', strtotime($data['date'] . " 00:00"));
$e_date = date('Y-m-d H:i:s', strtotime($data['date'] . " 23:59"));

$query = "SELECT tl.trip_rate trip_rate_total ,( SELECT sum(tlm.miles_google) FROM ct_trip_linehaul_movement tlm WHERE tlm.fkid_linehaul = tl.pk_idlinehaul) total_miles FROM ct_trip_linehaul tl WHERE tl.date_begin BETWEEN ? AND ? AND tl.status <> 'Cancelled'";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during RPM_DASH query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('ss', $b_date, $e_date);
if (!($stmt)) {
  $sc['code'] = "500";
  $sc['message'] = "Error during RPM_DASH variables binding [$stmt->errno]: $stmt->error";
  exit_script($sc);
}

if (!($stmt->execute())) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during RPM_DASH query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();
// $result = $rslt->fetch_assoc();
$result = array();
while ($row = $rslt->fetch_assoc()) {
  $result['trip_rate_total'] += $row['trip_rate_total'];
  $result['miles_total']+= $row['total_miles'];
}

if ($result['miles_total'] == 0) {
  $system_callback['data']['all_trips']['miles'] = "0";
  $system_callback['data']['all_trips']['rate'] = "$0.00";
  $system_callback['data']['all_trips']['rpm'] = "$0.00";
} else {
  $system_callback['data']['all_trips']['miles'] = $result['miles_total'];
  $system_callback['data']['all_trips']['rate'] = $result['trip_rate_total'];

  $system_callback['data']['all_trips']['rpm'] = $system_callback['data']['all_trips']['rate']/$system_callback['data']['all_trips']['miles'];
  $system_callback['data']['all_trips']['rate'] = "$" . numberify($system_callback['data']['all_trips']['rate']);
  $system_callback['data']['all_trips']['rpm'] = "$" . numberify($system_callback['data']['all_trips']['rpm']);
}


$query = "SELECT tl.trip_rate trip_rate_total ,( SELECT sum(tlm.miles_google) FROM ct_trip_linehaul_movement tlm WHERE tlm.fkid_linehaul = tl.pk_idlinehaul) total_miles FROM ct_trip_linehaul tl WHERE tl.date_begin BETWEEN ? AND ? AND tl.origin_zip <> '78041' AND tl.status <> 'Cancelled'";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during RPM_DASH_2 query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('ss', $b_date, $e_date);
if (!($stmt)) {
  $sc['code'] = "500";
  $sc['message'] = "Error during RPM_DASH_2 variables binding [$stmt->errno]: $stmt->error";
  exit_script($sc);
}

if (!($stmt->execute())) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during RPM_DASH_2 query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}
$rslt = $stmt->get_result();
// $result = $rslt->fetch_assoc();
$result = array();
while ($row = $rslt->fetch_assoc()) {
  $result['trip_rate_total'] += $row['trip_rate_total'];
  $result['miles_total']+= $row['total_miles'];
}
if ($result['miles_total'] == 0) {
  $system_callback['data']['sb_trips']['miles'] = "0";
  $system_callback['data']['sb_trips']['rate'] = "$0.00";
  $system_callback['data']['sb_trips']['rpm'] = "$0.00";
} else {
  $system_callback['data']['sb_trips']['miles'] = $result['miles_total'];
  $system_callback['data']['sb_trips']['rate'] = $result['trip_rate_total'];

  $system_callback['data']['sb_trips']['rpm'] = $system_callback['data']['sb_trips']['rate']/$system_callback['data']['sb_trips']['miles'];
  $system_callback['data']['sb_trips']['rate'] = "$" . numberify($system_callback['data']['sb_trips']['rate']);
  $system_callback['data']['sb_trips']['rpm'] = "$" . numberify($system_callback['data']['sb_trips']['rpm']);

}

$system_callback['code'] = 1;
exit_script($system_callback);

 ?>
