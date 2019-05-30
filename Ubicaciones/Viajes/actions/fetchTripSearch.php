<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$data = array(
  'input_f' => date('Y-m-d', strtotime('last week monday')),
  'input_t' => date('Y-m-d', strtotime('last week sunday')),
  'from' => date('Y-m-d', strtotime('last week monday')) . " 00:00",
  'to' => date('Y-m-d', strtotime('last week sunday')) . " 23:59",
  'txt' => ""
);

$bind_values = array();

if ($_POST['cTripsTxt'] != "") {
  $data = array(
    'input_f'=>"",
    'input_t'=>"",
    'from' => "",
    'to' => "",
    'txt' => "%" . $_POST['cTripsTxt'] . "%"
  );
  $where = '(b.brokerName LIKE ? OR t.trailer_number LIKE ? OR tl.lh_number LIKE ? OR t.pkid_trip LIKE ? OR tl.broker_reference LIKE ? OR tr.truckNumber LIKE ?)';
  $params = "ssssss";
  $bind_values[] =& $params;
  for ($i=0; $i <= 5; $i++) {
    $bind_values[] =& $data['txt'];
  }
} else {
  $data = array(
    'input_f' => date('Y-m-d', strtotime('last week monday')),
    'input_t' => date('Y-m-d', strtotime('last week sunday')),
    'from' => date('Y-m-d', strtotime($_POST['cTripsFrom'])) . " 00:00",
    'to' => date('Y-m-d', strtotime($_POST['cTripsTo'])) . " 23:59",
    'txt' => ""
  );
  $where = '(tl.date_arrival BETWEEN ? AND ?)';
  $params = "ss";
  $bind_values[] =& $params;
  $bind_values[] =& $data['from'];
  $bind_values[] =& $data['to'];
}



$query = "SELECT t.trip_year AS TripYear , t.pkid_trip AS idTrip , t.trip_status AS status , t.date_open AS DateOpen , t.trailer_number AS TrailerNumber , tl.lh_number AS linehaul_number , tl.origin_city AS OriginCity , tl.origin_state AS OriginState , tl.destination_city AS DestinationCity , tl.destination_state AS DestinationState , b.brokerName AS broker , tl.linehaul_status AS lh_status , t.pkid_trip AS tripid , tl.pk_idlinehaul AS linehaulid , max(tlm.pkid_movement) AS idMovement , tl.date_departure date_departure , tl.date_arrival date_arrival , tl.date_delivery date_delivery , SUM( CASE tlm.movement_type WHEN 'E' THEN tlm.miles_google ELSE 0 END) AS empty_miles , SUM( CASE tlm.movement_type WHEN 'L' THEN tlm.miles_google ELSE 0 END) AS loaded_miles ,( SELECT CONCAT(d.nameFirst , ' ' , d.nameLast) FROM ct_trip_linehaul_movement tlm LEFT JOIN ct_drivers d ON tlm.fkid_driver = d.pkid_driver WHERE tlm.fkid_linehaul = tl.pk_idlinehaul ORDER BY tlm.pkid_movement DESC LIMIT 1) last_driver FROM ct_trip t LEFT JOIN ct_trip_linehaul tl ON t.pkid_trip = tl.fk_idtrip LEFT JOIN ct_trip_linehaul_movement tlm ON tlm.fkid_linehaul = tl.pk_idlinehaul LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker LEFT JOIN ct_truck tr ON tr.pkid_truck = tlm.fkid_tractor WHERE $where GROUP BY t.trip_year , t.pkid_trip , tl.pk_idlinehaul";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during trip query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

// $data['txt'] = "%" . $data['txt'] . "%";

call_user_func_array(array($stmt, 'bind_param'), $bind_values);
// $stmt->bind_param('ssssss',
//   $data['from'],
//   $data['to'],
//   $data['txt'],
//   $data['txt'],
//   $data['txt'],
//   $data['txt']
// );
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['message'] = "Error during trip query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();

if ($rslt->num_rows == 0) {
  $system_callback['code'] = 2;
  $system_callback['message'] = "Script called successfully but there are no rows to display. For trip query.";
  //exit_script($system_callback);
} else {
    $system_callback['code'] = 1;
  while ($row = $rslt->fetch_assoc()) {
    //$system_callback['trips'][] = $row;
    foreach ($row as $key => $value) {
      $system_callback['rows'][$row['idTrip']][$row['linehaulid']][$key] = $value;
      $system_callback['rows'][$row['idTrip']]['trailer_number'] = $row['TrailerNumber'];
      $system_callback['rows'][$row['idTrip']]['TripYear'] = $row['TripYear'];
      $system_callback['rows'][$row['idTrip']]['idTrip'] = $row['idTrip'];
      $system_callback['rows'][$row['idTrip']]['status'] = $row['status'];
    }
  }
}

if ($system_callback['code'] == 2) {
  $system_callback['data'] = "<tr class='inline-table-row'>
  <td style='width: 40px'></td>
  <td>No trips found</td>
  <td class='text-right'></td>
  </tr>";
} else {
  foreach ($system_callback['rows'] as $trip) {
    $trp_number = $trip['TripYear'] . str_pad($trip['idTrip'], 4, 0, STR_PAD_LEFT);

    $system_callback['data'] .= "<tr class='inline-table-row' role='button' ty='$trip[TripYear]' db-id='$trip[idTrip]'>
      <td style='width: 40px'><p class='text-right $trip[status] trip'> <i class='fa fa-circle'></i> </p></td>
      <td>
        <p class='font-weight-bold'>$trip[linehaul_number]<span class='font-weight-light'> | $trip[trailer_number]</span> </p>";
    foreach ($trip as $t_key => $t_value) {
      if ($t_key == 'trailer_number'||$t_key == 'TripYear'||$t_key == 'idTrip'||$t_key == 'status') {
        continue;
      }
      if ($t_value['lh_status'] == 'Cancelled') {
        $status_button_color = "<span style='font-size: 70%'><i class='mr-1 far fa-circle $t_value[lh_status]'></i></span>";
      } else {
        $status_button_color = "<span style='font-size: 70%'><i class='mr-1 fas fa-circle $t_value[lh_status]'></i></span>";
      }
      $system_callback['data'] .= "
        <div class='mb-1'>
          <div class='row'>
            <div class='col-6'>
              $status_button_color
              $t_value[linehaul_number] | $t_value[OriginCity], $t_value[OriginState] - $t_value[DestinationCity], $t_value[DestinationState] <span class='small maroon-font'>($t_value[broker])</span>
            </div>
            <div class='col-6 text-right'>
              $t_value[last_driver]
            </div>
          </div>
        </div>";
    }
  }
}

exit_script($system_callback);

 ?>
