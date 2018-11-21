<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$sc = [];
$data = $_POST;

function parseDate($datestamp, $option = 1){
  if ($datestamp == "") {
    return false;
  }

  if ($option == 1) {
    $return = date('Y/m/d', strtotime($datestamp));
    return $return;
  } else {
    $return = array(
      'date'=>"",
      'time'=>array(
        'hour'=>"",
        'minute'=>""
      )
    );

    $return['date'] = date('Y/m/d', strtotime($datestamp));
    $return['time']['hour'] = date('H', strtotime($datestamp));
    $return['time']['minute'] = date('i', strtotime($datestamp));

    return $return;
  }
}
function numberify($number){
  return number_format($number, 2);
}


$query = "SELECT t.pkid_trip pkidtrip , t.trip_year tripyear , t.trip_status trip_status , t.trailer_number trailer_number, t.trailer_plates trailer_plates, t.fkid_trailer id_trailer, t.date_open date_open , t.date_close date_close , tl.pk_idlinehaul idlh , sum(( SELECT sum(miles_google) FROM ct_trip_linehaul_movement tlm WHERE tl.pk_idlinehaul = tlm.fkid_linehaul AND tl.linehaul_status <> 'Cancelled')) total_miles , sum(( SELECT sum(miles_google) FROM ct_trip_linehaul_movement tlm WHERE tlm.fkid_linehaul = tl.pk_idlinehaul AND tlm.movement_type = 'L' AND tl.linehaul_status <> 'Cancelled')) loaded_miles , sum(( SELECT sum(miles_google) FROM ct_trip_linehaul_movement tlm WHERE tlm.fkid_linehaul = tl.pk_idlinehaul AND tlm.movement_type = 'E' AND tl.linehaul_status <> 'Cancelled')) empty_miles , SUM( IF( tl.linehaul_status <> 'Cancelled' , tl.trip_rate , 0)) total_rate FROM ct_trip t LEFT JOIN ct_trip_linehaul tl ON t.pkid_trip = tl.fk_idtrip WHERE pkid_trip = ? GROUP BY pkid_trip , trip_year ORDER BY t.pkid_trip DESC";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $sc['code'] = "500";
  $sc['message'] = "Error during trailer query prepare [$db->errno]: $db->error";
  exit_script($sc);
}

$stmt->bind_param('s', $data['id']);
if (!($stmt)) {
  $sc['code'] = "500";
  $sc['message'] = "Error during trailer variables binding [$stmt->errno]: $stmt->error";
  exit_script($sc);
}

if (!($stmt->execute())) {
  $sc['code'] = "500";
  $sc['message'] = "Error during trailer query execution [$stmt->errno]: $stmt->error";
  exit_script($sc);
}

$rslt = $stmt->get_result();

if ($rslt->num_rows == 0) {
  $sc['code'] = 2;
  $sc['message'] = "Script called successfully but there are no rows to display. For trip query.";
  exit_script($sc);
} else {
  $trip = $rslt->fetch_assoc();
}

if ($trip['trip_status'] == 'Closed') {
  $dis_trip = 'disabled';
} else {
  $dis_trip = '';
}

$trip['date_open'] = parseDate($trip['date_open']);
$trip['date_close'] = parseDate($trip['date_close']);
$trip['rpm'] = numberify($trip['total_rate'] / $trip['total_miles']);
$trip['total_rate'] = numberify($trip['total_rate']);
$sc['data']['trip'] = $trip;



$query = "SELECT tl.pk_idlinehaul AS pk_idlinehaul, tl.lh_number lh_number, tl.fk_tripyear AS trip_year , tl.linehaul_status AS status , tl.origin_city AS origin_city , tl.origin_state AS origin_state , tl.origin_zip AS origin_zip , tl.destination_city AS destination_city , tl.destination_state AS destination_state , tl.destination_zip AS destination_zip , tl.trip_rate AS trip_rate, tl.broker_reference AS broker_reference, tl.fkid_broker AS brokerid, b.brokerName AS trip_brokerName , tl.rpm AS rpm , sum(tlm.miles_google) AS total_miles , SUM( CASE tlm.movement_type WHEN 'E' THEN tlm.miles_google ELSE 0 END) AS empty_miles , SUM( CASE tlm.movement_type WHEN 'L' THEN tlm.miles_google ELSE 0 END) AS loaded_miles , date_departure AS departure , date_arrival AS arrival , date_delivery AS delivery, date_appointment AS appointment ,( SELECT concat(nameFirst , ' ' , nameLast) FROM ct_drivers WHERE pkid_driver = tlm.fkid_driver) driver ,( SELECT concat(truckNumber) FROM ct_truck WHERE pkid_truck = tlm.fkid_tractor) tractor FROM ct_trip_linehaul tl LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker LEFT JOIN ct_trip_linehaul_movement tlm ON tlm.fkid_linehaul = tl.pk_idlinehaul WHERE tl.fk_idtrip = ? GROUP BY pk_idlinehaul";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during TRIP_LINEHAUL query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('s', $data['id']);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during TRIP_LINEHAUL variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during TRIP_LINEHAUL query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();
$linehauls = array();

if ($rslt->num_rows == 0) {
  $system_callback['query']['code'] = 2;
  $system_callback['query']['message'] = "Script called successfully but there are no rows to display. For trailer query.";
} else {
  while ($row = $rslt->fetch_assoc()) {
    $linehauls[] = $row;
  }
}

foreach ($linehauls as $key => $value) {
  $linehauls[$key]['departure'] = parseDate($linehauls[$key]['departure'], 2);
  $linehauls[$key]['arrival'] = parseDate($linehauls[$key]['arrival'], 2);
  $linehauls[$key]['appointment'] = parseDate($linehauls[$key]['appointment'], 2);
  $linehauls[$key]['delivery'] = parseDate($linehauls[$key]['delivery'], 2);
  // $linehauls[$key]['trip_rate'] = numberify($linehauls[$key]['trip_rate']);
  // $linehauls[$key]['rpm'] = numberify($linehauls[$key]['trip_rate'] / $linehauls[$key]['total_miles']);
  // $linehauls[$key]['rpm'] = numberify($linehauls[$key]['rpm']);
  // $linehauls[$key]['total_miles'] = numberify($linehauls[$key]['total_miles']);
}

$sc['data']['linehauls'] = "";

foreach ($linehauls as $lh) {
  if ($lh['status'] == 'Cancelled') {
    $status_button = "<i class='far fa-circle $lh[status]'></i>";
  } else {
    $status_button = "<i class='fas fa-circle $lh[status]'></i>";
  }

  $lh_number = $lh['lh_number'];

  $sc['data']['linehauls'] .= "<tr role='button' lhid='$lh[pk_idlinehaul]'>
    <td style='width: 20px'>
      $status_button
    </td>
    <td>
      <div class=''>
        <span class='font-weight-bold'>$lh_number</span>
        <span class='font-weith-light'> | $lh[tractor] $lh[driver]</span>
      </div>
      <div class=''>
        <span class=''>$lh[origin_city], $lh[origin_state] </span>
        <span class=''> - $lh[destination_city], $lh[destination_state]</span>
        <span></span>
      </div>
    </td>
    <td>
      <div class='row'>
        <div class='col-lg-6 text-right'>
          Rate:
        </div>
        <div class='col-lg-4 text-right'>
          $ " . numberify($lh['trip_rate']) . "
        </div>
      </div>
      <div class='row'>
        <div class='col-lg-6 text-right'>
          RPM:
        </div>
        <div class='col-lg-4 text-right'>
          $ " . numberify($lh['trip_rate'] / $lh['total_miles']) . "
        </div>
      </div>
    </td>
  </tr>";
}





$sc['code'] = 1;
$sc['message'] = "Script called successfully!";
exit_script($sc);

?>
