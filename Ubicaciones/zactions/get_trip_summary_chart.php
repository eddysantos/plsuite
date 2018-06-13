<?php


$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

function numberify($number){
  return number_format($number, 2);
}

$system_callback = [];
$data = $_POST;
$chart_data = array(['x'],['RPM']);

$b_date = date('Y-m-d H:i:s', strtotime($data['date_from'] . " 00:00"));
$e_date = date('Y-m-d H:i:s', strtotime($data['date_to'] . " 23:59"));
$date_group = "";

switch ($_POST['date_group']) {
  case 0:
    $date_group = 'DATE';
    break;

  case 1:
    $date_group = "WEEK";
    break;

  case 2:
    $date_group = "MONTH";
    break;
  default:
    $date_group = "DATE";
    break;
}

$query = "SELECT tl.lh_number linehaul , date(tl.date_begin) date , $date_group(tl.date_begin) date_group , tl.trip_rate trip_rate ,( SELECT SUM(tlm.miles_google) FROM ct_trip_linehaul_movement tlm WHERE tlm.fkid_linehaul = tl.pk_idlinehaul) miles FROM ct_trip_linehaul tl WHERE tl.date_begin BETWEEN ? AND ? AND tl.linehaul_status <> 'Cancelled' ORDER BY tl.date_begin ASC";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['message'] = "Error during TRIP_SUMMARY_CHART query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('ss', $b_date, $e_date);
if (!($stmt)) {
  $sc['code'] = "500";
  $sc['message'] = "Error during TRIP_SUMMARY_CHART variables binding [$stmt->errno]: $stmt->error";
  exit_script($sc);
}

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['message'] = "Error during TRIP_SUMMARY_CHART query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();
$result = array();

if ($rslt->num_rows == 0) {
  $system_callback['code'] = "2";
  $system_callback['message'] = "There was no rows to display!";
}

while ($row = $rslt->fetch_assoc()) {
  $results[$row['date_group']]['rate'] += $row['trip_rate'];
  $results[$row['date_group']]['miles'] += $row['miles'];
  $results[$row['date_group']]['rpm'] = $results[$row['date_group']]['rate'] / $results[$row['date_group']]['miles'];
}

foreach ($results as $date_group => $result) {
  array_push($chart_data[0], $date_group);
  array_push($chart_data[1], $result['rpm']);
}

$system_callback['code'] = 1;
$system_callback['data'] = $results;
$system_callback['to_chart'] = $chart_data;
exit_script($system_callback);

 ?>
