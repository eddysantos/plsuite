<?php

/* Calculate next movement id number */

$query = "SELECT count(pkid_movement) count FROM ct_trip_linehaul_movement WHERE fkid_linehaul = ?";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during MOVEMENT DETERMINATION query prepare [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$stmt->bind_param('s',
$pk_idlinehaul
);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during MOVEMENT DETERMINATION variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during MOVEMENT DETERMINATION query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();

$row = $rslt->fetch_assoc();

$new_mid = $row['count'];

$new_mid += 1;


// Insert movment into database:


$query = "INSERT INTO ct_trip_linehaul_movement(fkid_linehaul, origin_city, origin_state, origin_zip, destination_city, destination_state, destination_zip, miles_google, movement_type, fkid_tractor, tractor_plates, fkid_driver, fkid_driver_team, pk_movement_number, added_by) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

$stmt = $db->prepare($query);
$main_driver = $trip['linehaul']['drivers'][0]['id'];
$team_driver = "";

if (array_key_exists(1, $trip['linehaul']['drivers'])) {
  $team_driver = $trip['linehaul']['drivers'][1]['id'];
}

if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['message'] = "Error during INSERT TRIP_LINEHAUL_MOVEMENT query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$system_callback['code'] = "500";
$sytem_callback['message'] = "Did not access movements!";

$i = 0;
foreach ($trip['linehaul']['routes'] as $movement) {
  $system_callback['code'] = "501";
  $system_callback['message'] = "";

  if (
    !(
      $stmt->bind_param('sssssssssssssss',
      $pk_idlinehaul,
      $movement['ocity'],
      $movement['ostate'],
      $movement['ozip'],
      $movement['dcity'],
      $movement['dstate'],
      $movement['dzip'],
      $movement['miles'],
      $movement['type'],
      $trip['truck']['id'],
      $trip['truck']['plates'],
      $main_driver,
      $team_driver,
      $new_mid,
      $user)
      )
    ) {
      $system_callback['code'] = "500";
      $system_callback['data'] = $system_callback;
      $system_callback['message'] = "Error during INSERT TRIP_LINEHAUL_MOVEMENT variables binding [$stmt->errno]: $stmt->error";
    }

    if (!($stmt->execute())) {
      $system_callback['code'] = "500";
      $system_callback['query'] = $query;
      $system_callback['message'] = "Error during INSERT TRIP_LINEHAUL_MOVEMENT query execution [$stmt->errno]: $stmt->error";
    }

    if ($i == 0) {
        $first_movement = $db->insert_id;
    }

    $last_movement = $db->insert_id;
    $i++;
}

if ($system_callback['code'] == "500") {
  exit_script($system_callback);
}

?>
