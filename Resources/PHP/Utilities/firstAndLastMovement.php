<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';


$system_callback = [];
$pk_trip = $_POST['pk_trip'];

$query = "SELECT first_movement first, last_movement last FROM ct_trip WHERE pkid_trip = ?";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('s', $pk_trip);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();
$row = $rslt->fetch_assoc();

if ($rslt->num_rows == 0) {
  $system_callback['code'] = 2;
  $system_callback['message'] = "Script called successfully but there are no rows to display.";
  exit_script($system_callback);
}

$movements = [
  'first'=>[
    'id'=>$row['first'],
    'origin'=>['city'=>'', 'state'=>'', 'zip'=>''],
    'destination'=>['city'=>'', 'state'=>'', 'zip'=>''],
    'driver'=>['id'=>'', 'name'=>''],
    'truck'=>['id'=>'', 'number'=>'']
  ],
  'last'=>[
    'id'=>$row['last'],
    'origin'=>['city'=>'', 'state'=>'', 'zip'=>''],
    'destination'=>['city'=>'', 'state'=>'', 'zip'=>''],
    'driver'=>['id'=>'', 'name'=>''],
    'truck'=>['id'=>'', 'number'=>'', 'plates'=>'']
  ]
];

$query = "SELECT tlm.origin_city ocity, tlm.origin_state ostate, tlm.origin_zip ozip, tlm.destination_city dcity, tlm.destination_state dstate, tlm.destination_zip dzip, t.pkid_truck pkid_truck, t.truckNumber truck_number, t.truckPlates truck_plates, d.nameFirst driver_first, d.nameLast driver_last, d.pkid_driver pkid_driver FROM ct_trip_linehaul_movement tlm LEFT JOIN ct_truck t ON tlm.fkid_tractor = t.pkid_truck LEFT JOIN ct_drivers d ON tlm.fkid_driver = d.pkid_driver WHERE tlm.pkid_movement = ?";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

foreach ($movements as $movement => $value) {
  $stmt->bind_param('s', $value['id']);
  if (!($stmt)) {
    $system_callback['code'] = "500";
    $system_callback['query'] = $query;
    $system_callback['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
    exit_script($system_callback);
  }

  if (!($stmt->execute())) {
    $system_callback['code'] = "500";
    $system_callback['query'] = $query;
    $system_callback['message'] = "Error during query execution [$stmt->errno]: $stmt->error";
    exit_script($system_callback);
  }

  $rslt = $stmt->get_result();
  while ($row = $rslt->fetch_assoc()) {
    $movements[$movement]['origin']['city'] = $row['ocity'];
    $movements[$movement]['origin']['state'] = $row['ostate'];
    $movements[$movement]['origin']['zip'] = $row['ozip'];
    $movements[$movement]['destination']['city'] = $row['dcity'];
    $movements[$movement]['destination']['state'] = $row['dstate'];
    $movements[$movement]['destination']['zip'] = $row['dzip'];
    $movements[$movement]['driver']['id'] = $row['pkid_driver'];
    $movements[$movement]['driver']['name'] = "$row[driver_first] $row[driver_last]";
    $movements[$movement]['truck']['id'] = $row['pkid_truck'];
    $movements[$movement]['truck']['number'] = "$row[truck_number]";
    $movements[$movement]['truck']['plates'] = "$row[truck_plates]";
  }
}


$system_callback['data'] = $movements;
$system_callback['code'] = 1;
$system_callback['message'] = "Script called successfully!";
exit_script($system_callback);
 ?>
