<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$sc = [];
$data = $_POST;

function parseDate($datestamp, $option = 1){
  if ($datestamp == "") {
    return $return;
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

    $return['date'] = date('Y-m-d', strtotime($datestamp));
    $return['time']['hour'] = date('H', strtotime($datestamp));
    $return['time']['minute'] = date('i', strtotime($datestamp));

    return $return;
  }
}
function numberify($number){
  return number_format($number, 2);
}


$query = "SELECT tlm.pkid_movement AS idmv, tlm.origin_city AS ocity , tlm.origin_state AS ostate , tlm.destination_city AS dcity , tlm.destination_state AS dstate , tlm.date_movement_start AS date_start , tlm.extra_stop AS extra_stop , tlm.eal AS eal , tlm.miles_google AS miles , tlm.movement_type AS type, t.truckNumber AS truck_number , CONCAT(d.nameFirst , ' ' , d.nameLast) AS driver , CONCAT(td.nameFirst , ' ' , td.nameLast) AS team_driver, t.pkid_truck AS idtruck, d.pkid_driver AS iddriver FROM ct_trip_linehaul_movement tlm LEFT JOIN ct_truck t ON tlm.fkid_tractor = t.pkid_truck LEFT JOIN ct_drivers d ON tlm.fkid_driver = d.pkid_driver LEFT JOIN ct_drivers td ON tlm.fkid_driver_team = td.pkid_driver WHERE tlm.fkid_linehaul = ?";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $sc['code'] = "500";
  $sc['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($sc);
}

$stmt->bind_param('s', $data['lhid']);
if (!($stmt)) {
  $sc['code'] = "500";
  $sc['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
  exit_script($sc);
}

if (!($stmt->execute())) {
  $sc['code'] = "500";
  $sc['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($sc);
}

$rslt = $stmt->get_result();

if ($rslt->num_rows == 0) {
  $sc['code'] = 2;
  $sc['message'] = "Script called successfully but there are no rows to display.";
  $sc['data'] = $data;
  exit_script($sc);
}

while ($row = $rslt->fetch_assoc()) {
  $sc['data'] .=
  "<tr db-id='$row[idmv]' role='button'>
    <td>
      <div class=''>
        $row[ocity], $row[ostate] -> $row[dcity], $row[dstate]
      </div>
      <div class=''>
        $row[miles] " . ($row['type'] == "L" ? "Loaded" : "Empty") . " Miles
      </div>
    </td>
    <td class='text-right'>
      <div class=''>
        $row[truck_number] - $row[driver]
      </div>
      <div>" . ($row['eal'] == 1 ? "Empty As Loaded" : "")  . "</div>
      <div>" . ($row['extra_stop'] == 1 ? "Extra Stop" : "")  . "</div>
    </td>
  </tr>";
}

$sc['code'] = 1;
$sc['message'] = "Everything ok!";
exit_script($sc);

?>
