<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];
$data = $_POST;

$query = "UPDATE ct_trip_linehaul_movement SET origin_city = ?, origin_state = ?, origin_zip = ?, destination_city = ?, destination_state = ?, destination_zip = ?, miles_google = ?, movement_type = ?, extra_stop = ?, eal = ?, fkid_tractor = ?, fkid_driver = ?, fkid_driver_team = ? WHERE pkid_movement = ? ";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  // $system_callback['query'] = $query;
  $system_callback['query']['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('ssssssssssssss',
  $data['ocity'],
  $data['ostate'],
  $data['ozip'],
  $data['dcity'],
  $data['dstate'],
  $data['dzip'],
  $data['miles'],
  $data['type'],
  $data['extra_stop'],
  $data['eal'],
  $data['truck'],
  $data['driver'],
  $data['team'],
  $data['mvid']
);

if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query']['detail'] = $query;
  $system_callback['query']['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query']['detail'] = $query;
  $system_callback['query']['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($system_callback);
}

$affected = $stmt->affected_rows;

if ($affected == 0) {
  $system_callback['query']['code'] = 2;
  $system_callback['query']['message'] = "No data was changed.";
  $system_callback['data'] = $data;
  exit_script($system_callback);
}

$system_callback['query']['code'] = 1;
$system_callback['query']['message'] = "Script called successfully!";
exit_script($system_callback);

?>
