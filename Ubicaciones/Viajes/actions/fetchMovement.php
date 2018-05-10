<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];
$system_callback['inputs'] = $data = $_POST;

$query = "SELECT tlm.pkid_movement AS idmv, tlm.origin_city AS ocity , tlm.origin_state AS ostate, tlm.origin_zip AS ozip,  tlm.destination_city AS dcity , tlm.destination_state AS dstate, tlm.destination_zip AS dzip, tlm.date_movement_start AS date_start , tlm.extra_stop AS extra_stop , tlm.eal AS eal , tlm.miles_google AS miles , tlm.movement_type AS type, t.truckNumber AS truck_number , t.pkid_truck AS truckid, CONCAT(d.nameFirst , ' ' , d.nameLast) AS driver, d.pkid_driver AS driverid, CONCAT(td.nameFirst , ' ' , td.nameLast) AS team_driver, td.pkid_driver AS teamid FROM ct_trip_linehaul_movement tlm LEFT JOIN ct_truck t ON tlm.fkid_tractor = t.pkid_truck LEFT JOIN ct_drivers d ON tlm.fkid_driver = d.pkid_driver LEFT JOIN ct_drivers td ON tlm.fkid_driver_team = td.pkid_driver WHERE tlm.pkid_movement = ?";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during TRIP_LINEHAUL query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('s', $data['mvid']);
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
$movements = array();

if ($rslt->num_rows == 0) {
  $system_callback['query']['code'] = 2;
  $system_callback['query']['message'] = "Script called successfully but there are no rows to display. For trailer query.";
  exit_script($system_callback);
} else {
  while ($row = $rslt->fetch_assoc()) {
    $system_callback['data'] = $row;
  }
}

$system_callback['query']['code'] = 1;
exit_script($system_callback);

?>
