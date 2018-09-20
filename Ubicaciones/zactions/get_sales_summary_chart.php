<?php


$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

function numberify($number){
  return number_format($number, 2);
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

// switch ($data['category']) {
//   case 'driver':
//     $and_where = "AND d.pkid_driver = ?";
//     $group_by = ",d.pkid_driver";
//     $params .= "s";
//     $bind_params[] =& $data['dbid'];
//     $x_tag_index = 'driver_name';
//     break;
//
//   case 'truck':
//     $and_where = "AND trk.pkid_truck = ?";
//     $group_by = ",trk.pkid_truck";
//     $params .= "s";
//     $bind_params[] =& $data['dbid'];
//     $x_tag_index = 'tractor';
//     break;
//
//   case 'trailer':
//     $and_where = "AND trl.pkid_trailer = ?";
//     $group_by = ",trl.pkid_trailer";
//     $params .= "s";
//     $bind_params[] =& $data['dbid'];
//     $x_tag_index = 'trailer';
//     break;
//
//   case 'broker':
//     $and_where = "AND b.pkid_broker = ?";
//     $group_by = ",b.pkid_broker";
//     $params .= "s";
//     $bind_params[] =& $data['dbid'];
//     $x_tag_index = "broker";
//     break;
// }



// if (($data['dbid'] == "" && $params == "sss") OR true) {
//   $params = "ss";
//   array_pop($bind_params);
// }

$and_where = '';
$group_by = '';
$x_tag_index = 'fecha';

$query = "SELECT tl.date_begin lh_date , $period(tl.date_begin) date_grouping, sum(tl.trip_rate) rate FROM ct_trip t LEFT JOIN ct_trip_linehaul tl ON t.pkid_trip = tl.fk_idtrip  WHERE tl.date_begin BETWEEN ? AND ? AND tl.linehaul_status <> 'Cancelled' $and_where GROUP BY date_grouping $group_by";


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
  $results[$row['date_grouping']]['date'] = $row['lh_date'];
  $results[$row['date_grouping']]['rate'] += $row['rate'];
}

// foreach ($results as $id => $result) {
//   $results[$id]['rate'] = "$" . number_format($result['rate'], 2);
// }

$chart_data = array(['x'],['Sales']);

// foreach ($results as $date_group => $result) {
//   array_push($chart_data[0], $date_group);
//   array_push($chart_data[1], $result['rate']);
// }

switch ($data['period']) {
  case 0:
    foreach ($results as $date_group => $result) {
      array_push($chart_data[0], $date_group);
      array_push($chart_data[1], $result['rate']);
    }
    break;

  case 1:
    foreach ($results as $date_grouping => $result) {
      $year = date('Y', strtotime($result['date']));
      $week_day = date('Y-m-d', strtotime(sprintf("%d-W%02d-%d", $year, $date_grouping, 1)));
      array_push($chart_data[0], $week_day);
      array_push($chart_data[1], $result['rate']);
    }
    break;

  case 2:
  foreach ($results as $date_grouping => $result) {
    $month = date('Y-m-01', strtotime($result['date']));
    array_push($chart_data[0], $month);
    array_push($chart_data[1], $result['rate']);
  }
    break;
}



$system_callback['code'] = 1;
$system_callback['query'] = $query;
$system_callback['data'] = $data;
$system_callback['to_chart'] = $chart_data;
exit_script($system_callback);

 ?>
