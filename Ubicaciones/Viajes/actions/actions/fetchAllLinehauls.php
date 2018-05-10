<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];
$system_callback['data'] = $data = $_POST;

$query = "SELECT tl.pk_idlinehaul AS pk_idlinehaul , tl.fk_tripyear AS trip_year, tl.fk_idtrip AS trip_number, tl.linehaul_status AS status , tl.origin_city AS origin_city , tl.origin_state AS origin_state , tl.origin_zip AS origin_zip , tl.destination_city AS destination_city , tl.destination_state AS destination_state , tl.destination_zip AS destination_zip , tl.trip_rate AS trip_rate , b.brokerName AS trip_brokerName , tl.rpm AS rpm , sum(tlm.miles_google) AS total_miles , SUM( CASE tlm.movement_type WHEN 'E' THEN tlm.miles_google ELSE 0 END) AS empty_miles , SUM( CASE tlm.movement_type WHEN 'L' THEN tlm.miles_google ELSE 0 END) AS loaded_miles , date_departure AS departure , date_arrival AS arrival , pk_linehaul_number AS lh_number ,( SELECT concat(nameFirst , ' ' , nameLast) FROM ct_drivers WHERE pkid_driver = tlm.fkid_driver) driver ,( SELECT concat(truckNumber) FROM ct_truck WHERE pkid_truck = tlm.fkid_tractor) tractor FROM ct_trip_linehaul tl LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker LEFT JOIN ct_trip_linehaul_movement tlm ON tlm.fkid_linehaul = tl.pk_idlinehaul WHERE tl.fk_idtrip = ? AND tl.fk_tripyear = ? GROUP BY pk_idlinehaul";
// $query = "SELECT tl.pk_idlinehaul AS pk_idlinehaul, tl.fk_tripyear AS trip_year, tl.fk_idtrip AS trip_number, tl.pk_linehaul_number AS linehaul_number, tl.linehaul_status AS status, tl.origin_city AS origin_city , tl.origin_state AS origin_state , tl.origin_zip AS origin_zip , tl.destination_city AS destination_city , tl.destination_state AS destination_state , tl.destination_zip AS destination_zip , tl.trip_rate AS trip_rate , b.brokerName AS trip_brokerName , tl.rpm AS rpm , sum(tlm.miles_google) AS total_miles , SUM( CASE tlm.movement_type WHEN 'E' THEN tlm.miles_google ELSE 0 END) AS empty_miles , SUM( CASE tlm.movement_type WHEN 'L' THEN tlm.miles_google ELSE 0 END) AS loaded_miles FROM ct_trip_linehaul tl LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker LEFT JOIN ct_trip_linehaul_movement tlm ON tlm.fkid_linehaul = tl.pk_idlinehaul WHERE tl.fk_idtrip = ? AND tl.fk_tripyear = ? GROUP BY pk_idlinehaul";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during TRIP_LINEHAUL query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('ss', $data['tripid'], $data['tripyear']);
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

foreach ($linehauls as $lh) {
  global $system_callback;
  $rpm = round($lh['trip_rate'] / ($lh['loaded_miles'] + $lh['empty_miles']), 2);
  $system_callback['data'] .= "<tr role='button' db-id='$lh[pk_idlinehaul]' tripnumber='$lh[trip_number]' lh-number='$lh[linehaul_number]' tripyear='$lh[trip_year]'>
  <td style='width: 40px'><i class='fa fa-circle $lh[status]'></i></td>
  <td>
  <div class=''><span class='font-weight-bold'>" . $lh['trip_year'] .  str_pad($lh['trip_number'], 4, 0, STR_PAD_LEFT) . $lh['lh_number'] . "</span> | $lh[driver] $lh[tractor]</div>
  <div class=''>$lh[origin_city] $lh[origin_state], $lh[origin_zip]</div>
  <div class=''>$lh[destination_city] $lh[destination_state], $lh[destination_zip]</div>
  <td class='text-right'>
    <div class='row'>
      <div class='offset-6 col-2 p-0'>L Miles:</div><div class='col-3 pr-0'>$lh[loaded_miles]</div>
    </div>
    <div class='row'>
      <div class='offset-6 col-2 p-0'>E Miles:</div><div class='col-3 pr-0'>$lh[empty_miles]</div>
    </div>
    <div class='row'>
      <div class='offset-6 col-2 p-0'>Rate:</div><div class='col-3 pr-0'>$" . number_format($lh['trip_rate']) . "</div>
    </div>
    <div class='row'>
      <div class='offset-6 col-2 p-0'>RPM:</div><div class='col-3 pr-0'>$ " . number_format(round($lh[trip_rate] / ($lh[loaded_miles] + $lh[empty_miles]), 2), 2) . "</div>
    </div>
  </td>
  </tr>";
}

$system_callback['query']['code'] = 1;
exit_script($system_callback);

?>
