<?php


$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

function numberify($number){
  return number_format($number, 2);
}

$system_callback = [];
$data = $_POST;
$chart_data = array(['x'],['Loaded'],['Empty'], ['Incomplete Loaded'], ['Incomplete Empty'], ['Goal']);
$goal_factor = 3000 / 7;

$b_date = date('Y-m-d H:i:s', strtotime($data['date_from'] . " 00:00"));
$e_date = date('Y-m-d H:i:s', strtotime($data['date_to'] . " 23:59"));

$earlier = new DateTime(date('Y-m-d', strtotime($data['date_from'])));
$later = new DateTime(date('Y-m-d', strtotime($data['date_to'])));

$diff = $later->diff($earlier)->format("%a");

$goal = ($diff + 1) * $goal_factor;
$grouping = "";

$query = "SELECT trk.truckNumber tractor , tlm.movement_type mov_type , sum(tlm.miles_google) miles, tl.date_arrival date_arrival FROM ct_trip t LEFT JOIN ct_trip_linehaul tl ON t.pkid_trip = tl.fk_idtrip LEFT JOIN ct_trip_linehaul_movement tlm ON tl.pk_idlinehaul = tlm.fkid_linehaul LEFT JOIN ct_truck trk ON tlm.fkid_tractor = trk.pkid_truck WHERE (tl.date_arrival BETWEEN ? AND ? OR tl.date_arrival IS NULL) AND tl.linehaul_status <> 'Cancelled' AND tl.fk_idtrip <> '' GROUP BY tl.pk_idlinehaul, mov_type , tractor ORDER BY tractor , mov_type";


$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['message'] = "Error during TRUCK_MILES_SUMMARY_CHART query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('ss', $b_date, $e_date);
if (!($stmt)) {
  $sc['code'] = "500";
  $sc['message'] = "Error during TRUCK_MILES_SUMMARY_CHART variables binding [$stmt->errno]: $stmt->error";
  exit_script($sc);
}

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['params'] = $bind_params;
  $system_callback['message'] = "Error during TRUCK_MILES_SUMMARY_CHART query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();
$results = array();
$sort_results = array();

if ($rslt->num_rows == 0) {
  $system_callback['code'] = "2";
  $system_callback['message'] = "There was no rows to display!";
}

while ($row = $rslt->fetch_assoc()) {
  if ($row['date_arrival'] == "") {
    $grouping = "incomplete";
  } else {
    $grouping = "done";
  }

  switch ($row['mov_type']) {
    case 'L':
      $results[$row['tractor']][$grouping]['loaded_miles'] += $row['miles'];
      $results[$row['tractor']][$grouping]['empty_miles'] += 0;
      $results[$row['tractor']]['total_miles'] += $row['miles'];
      break;

    case 'E':
      $results[$row['tractor']][$grouping]['empty_miles'] += $row['miles'];
      $results[$row['tractor']][$grouping]['loaded_miles'] += 0;
      $results[$row['tractor']]['total_miles'] += $row['miles'];
      break;
  }
}


foreach ($results as $tractor => $value) {
  $sort_results[$tractor] = $value['total_miles'];
}

arsort($sort_results);

foreach ($sort_results as $tractor => $data) {
  array_push($chart_data[0], $tractor);
  array_push($chart_data[1], $results[$tractor]['done']['loaded_miles']);
  array_push($chart_data[2], $results[$tractor]['done']['empty_miles']);
  array_push($chart_data[3], $results[$tractor]['incomplete']['loaded_miles']);
  array_push($chart_data[4], $results[$tractor]['incomplete']['empty_miles']);
  array_push($chart_data[5], $goal < 3000 ? 3000 : $goal);
}



$system_callback['code'] = 1;
// $system_callback['query_results'] = $results;
// $system_callback['ordered_results'] = $sort_results;
$system_callback['to_chart'] = $chart_data;
// $system_callback['goal'] = round($diff * $goal_factor);
exit_script($system_callback);

 ?>
