<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];
$data = $_POST;

function parseDate($datestamp){
  $return = array(
    'date'=>"",
    'time'=>array(
      'hour'=>"",
      'minute'=>""
    )
  );

  if ($datestamp == "") {
    return $return;
  }

  $return['date'] = date('Y-m-d', strtotime($datestamp));
  $return['time']['hour'] = date('H', strtotime($datestamp));
  $return['time']['minute'] = date('i', strtotime($datestamp));

  return $return;
}

$query = "SELECT lh.pk_idlinehaul AS linehaulid, lh.fk_tripyear AS trip_year, lh.linehaul_status AS status, lh.fk_idtrip AS tripid , lh.date_begin AS trip_start , lh.date_end AS trip_end , lh.trip_rate AS rate , lh.fuel_surcharge AS fuel_surcharge , lh.origin_state AS origin_state , lh.origin_city AS origin_city , lh.origin_zip AS origin_zip , lh.destination_state AS destination_state , lh.destination_city AS destination_city , lh.destination_zip AS destination_zip , lh.rpm AS rpm , lh.fkid_broker AS brokerid , b.brokerName AS broker_name , sum(tlm.miles_google) AS total_miles , SUM( CASE tlm.movement_type WHEN 'E' THEN tlm.miles_google ELSE 0 END) AS empty_miles , SUM( CASE tlm.movement_type WHEN 'L' THEN tlm.miles_google ELSE 0 END) AS loaded_miles, date_departure AS departure, date_arrival AS arrival, date_delivery AS delivery, date_appointment AS appointment, broker_reference AS broker_reference, lh.lh_comment AS lh_comment FROM ct_trip_linehaul lh LEFT JOIN ct_brokers b ON lh.fkid_broker = b.pkid_broker LEFT JOIN ct_trip_linehaul_movement tlm ON tlm.fkid_linehaul = lh.pk_idlinehaul WHERE pk_idlinehaul = ? GROUP BY linehaulid";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('s', $data['lhid']);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();

if ($rslt->num_rows == 0) {
  $system_callback['code'] = 2;
  $system_callback['message'] = "Script called successfully but there are no rows to display.";
  $system_callback['data'] = $data;
  exit_script($system_callback);
}

while ($row = $rslt->fetch_assoc()) {
  $system_callback['data'] = $row;
}

$system_callback['data']['arrival'] = parseDate($system_callback['data']['arrival']);
$system_callback['data']['departure'] = parseDate($system_callback['data']['departure']);
$system_callback['data']['delivery'] = parseDate($system_callback['data']['delivery']);
$system_callback['data']['appointment'] = parseDate($system_callback['data']['appointment']);

$system_callback['code'] = 1;
$system_callback['message'] = "Script called successfully!";
exit_script($system_callback);

?>
