<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];
$system_callback['inputs'] = $data = $_POST;

$query = "SELECT tlm.pkid_movement AS idmv, tlm.origin_city AS ocity , tlm.origin_state AS ostate , tlm.destination_city AS dcity , tlm.destination_state AS dstate , tlm.date_movement_start AS date_start , tlm.extra_stop AS extra_stop , tlm.eal AS eal , tlm.miles_google AS miles , tlm.movement_type AS type, t.truckNumber AS truck_number , CONCAT(d.nameFirst , ' ' , d.nameLast) AS driver , CONCAT(td.nameFirst , ' ' , td.nameLast) AS team_driver, t.pkid_truck AS idtruck, d.pkid_driver AS iddriver FROM ct_trip_linehaul_movement tlm LEFT JOIN ct_truck t ON tlm.fkid_tractor = t.pkid_truck LEFT JOIN ct_drivers d ON tlm.fkid_driver = d.pkid_driver LEFT JOIN ct_drivers td ON tlm.fkid_driver_team = td.pkid_driver WHERE tlm.fkid_linehaul = ?";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during TRIP_LINEHAUL query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('s', $data['lhid']);
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

$system_callback['info'] = $rslt->num_rows;

if ($rslt->num_rows == 0) {
  $system_callback['query']['code'] = 1;
  $system_callback['query']['message'] = "Script called successfully but there are no rows to display. For trailer query.";
  $system_callback['data'] .= "<tr>
  <td>
  <div class=''>No movmements were found for this linehaul.</div>
  </td>
  <td class='text-right'>
  </td>
  </tr>";
  exit_script($system_callback);
} else {
  while ($row = $rslt->fetch_assoc()) {
    $movements[] = $row;
  }
}

foreach ($movements as $mv) {
  global $system_callback;
  $team = $mv['team_driver'] == "" ? "" : "/ $mv[team_driver]";
  $es = $mv['extra_stop'] == "0" ? "" : "<div class=''>Extra Stop</div>";
  $eal = $mv['eal'] == "0" ? "" : "<div class=''>Empty as Loaded</div>";
  if ($mv['type'] == "E") {
    $mov_type = "Empty";
  } elseif ($mv['type'] == "L") {
    $mov_type = "Loaded";
  }
  $system_callback['data'] .= "<tr role='button' db-id='$mv[idmv]'>
  <td>
  <div class=''>$mv[ocity], $mv[ostate] -> $mv[dcity], $mv[dstate] [$mv[miles] $mov_type Miles]</div>
  $es
  $eal
  </td>
  <td class='text-right'>
  <div class='conveyance' db-id-truck='$mv[idtruck]' db-id-driver='$mv[iddriver]'>$mv[truck_number] - $mv[driver] $team</div>
  </td>
  </tr>";
}

$system_callback['query']['code'] = 1;
exit_script($system_callback);

?>
