<?php


$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

function numberify($number){
  return number_format($number, 2);
}

function findMonday($d="",$format="Y-m-d") {
    if($d=="") $d=date("Y-m-d");
    $delta = date("w",strtotime($d)) - 1;
    if ($delta <0) $delta = 6;
    return date($format, mktime(0,0,0,date('m'), date('d')-$delta, date('Y') ));
}

$system_callback = [];
$data = $_POST;
// $chart_data = array(['x'],['RPM']);

$b_date = date('Y-m-d H:i:s', strtotime($data['date_from'] . " 00:00"));
$e_date = date('Y-m-d H:i:s', strtotime($data['date_to'] . " 23:59"));
$peroid = "";
$and_where = "";
$group_by = "";
$params = "ss";
$bind_params[] =& $params;
$bind_params[] =& $b_date;
$bind_params[] =& $e_date;

switch ($data['period']) {
  case 0:
    $period = 'DATE';
    break;

  case 1:
    $period = "WEEK";
    break;

  case 2:
    $period = "MONTH";
    break;

  default:
    $period = "DATE";
    break;
}

switch ($data['category']) {
  case 'driver':
    $and_where = "AND d.pkid_driver = ?";
    $group_by = ",d.pkid_driver";
    $params .= "s";
    $bind_params[] =& $data['dbid'];
    $x_tag_index = 'driver_name';
    break;

  case 'truck':
    $and_where = "AND trk.pkid_truck = ?";
    $group_by = ",trk.pkid_truck";
    $params .= "s";
    $bind_params[] =& $data['dbid'];
    $x_tag_index = 'tractor';
    break;

  case 'trailer':
    $and_where = "AND trl.pkid_trailer = ?";
    $group_by = ",trl.pkid_trailer";
    $params .= "s";
    $bind_params[] =& $data['dbid'];
    $x_tag_index = 'trailer';
    break;

  case 'broker':
    $and_where = "AND b.pkid_broker = ?";
    $group_by = ",b.pkid_broker";
    $params .= "s";
    $bind_params[] =& $data['dbid'];
    $x_tag_index = "broker";
    break;
}


if ($data['dbid'] == "" && $params == "sss") {
  $params = "ss";
  array_pop($bind_params);
}

$query = "SELECT tl.date_arrival lh_date , $period(tl.date_arrival) date_grouping , CONCAT(d.nameFirst, ' ', d.nameLast) driver_name, trk.truckNumber tractor , trl.trailerNumber trailer , b.brokerName broker , tlm.movement_type mov_type , sum(tlm.miles_google) miles FROM ct_trip t LEFT JOIN ct_trip_linehaul tl ON t.pkid_trip = tl.fk_idtrip LEFT JOIN ct_trip_linehaul_movement tlm ON tl.pk_idlinehaul = tlm.fkid_linehaul LEFT JOIN ct_trailer trl ON t.fkid_trailer = trl.pkid_trailer LEFT JOIN ct_drivers d ON tlm.fkid_driver = d.pkid_driver LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker LEFT JOIN ct_truck trk ON tlm.fkid_tractor = trk.pkid_truck WHERE tl.date_arrival BETWEEN ? AND ? AND tl.linehaul_status <> 'Cancelled' $and_where GROUP BY date_grouping $group_by";


$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['message'] = "Error during MILES_SUMMARY_CHART query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

call_user_func_array(array($stmt, 'bind_param'), $bind_params);

// $stmt->bind_param('ss', $b_date, $e_date);
// if (!($stmt)) {
//   $sc['code'] = "500";
//   $sc['message'] = "Error during MILES_SUMMARY_CHART variables binding [$stmt->errno]: $stmt->error";
//   exit_script($sc);
// }

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['params'] = $bind_params;
  $system_callback['message'] = "Error during MILES_SUMMARY_CHART query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();
$result = array();

$num_rows = $rslt->num_rows;

if ($rslt->num_rows == 0) {
  $system_callback['code'] = "2";
  $system_callback['message'] = "There was no rows to display!";
  exit_script($system_callback);
}

while ($row = $rslt->fetch_assoc()) {
  $x_tag = $row[$x_tag_index];
  $results[$row['date_grouping']]['date'] = $row['lh_date'];
  $results[$row['date_grouping']]['miles'] += $row['miles'];
}

$chart_data = array(['x'],[$x_tag]);
$test_date = new DateTime();

switch ($data['period']) {
  case 0:
    foreach ($results as $date_group => $result) {
      array_push($chart_data[0], $date_group);
      array_push($chart_data[1], $result['miles']);
    }
    break;


  case 1:
    foreach ($results as $date_grouping => $result) {
      $year = date('Y', strtotime($result['date']));
      // $test_date->setISODate($year, $date_grouping);
      // $week_present = $test_date->format('Y-m-d');
      // error_log($week_present);
      $week_day = date('Y-m-d', strtotime(sprintf("%d-W%02d-%d", $year, $date_grouping, 7)));
      array_push($chart_data[0], $week_day);
      array_push($chart_data[1], $result['miles']);
    }
    break;

  case 2:
  foreach ($results as $date_grouping => $result) {
    $month = date('Y-m-01', strtotime($result['date']));
    array_push($chart_data[0], $month);
    array_push($chart_data[1], $result['miles']);
  }
    break;
}



$system_callback['code'] = 1;
$system_callback['query'] = $query;
$system_callback['data'] = $data;
$system_callback['to_chart'] = $chart_data;
exit_script($system_callback);

 ?>
