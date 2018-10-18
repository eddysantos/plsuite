<?php

/* Calculate next linehaul id number */

$query = "SELECT count(pk_idlinehaul) count FROM ct_trip_linehaul WHERE fk_idtrip = ?";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callbac['code'] = "500";
  $system_callbac['db'] = $db;
  $system_callbac['message'] = "Error during count TRIP LINEHAULS query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('s',
$pk_trip
);
if (!($stmt)) {
  $system_callbac['code'] = "500";
  $system_callbac['query'] = $query;
  $system_callbac['message'] = "Error during count TRIP LINEHAULS variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callbac['code'] = "500";
  $system_callbac['query'] = $query;
  $system_callbac['message'] = "Error during count TRIP LINEHAULS query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();

$row = $rslt->fetch_assoc();

$new_lid = $row['count'];

$new_lid += 1;


/* Insert Linehaul in database */

$query = "INSERT INTO ct_trip_linehaul(fk_idtrip, origin_state, origin_city, origin_zip, destination_state, destination_city, destination_zip, trip_rate, fkid_broker, pk_linehaul_number, broker_reference, lh_number, added_by, date_appointment) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";


$appt = date('Y-m-d H:i', strtotime($trip['linehaul']['appt']['date'] . " " . $trip['linehaul']['appt']['hour'] . ":" . $trip['linehaul']['appt']['minute']));

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callbac['code'] = "500";
  $system_callbac['query'] = $query;
  $system_callbac['message'] = "Error during INSERT TRIP_LINEHAUL query prepare [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$stmt->bind_param('ssssssssssssss',
  $pk_trip,
  $origin['state'],
  $origin['city'],
  $origin['zip'],
  $destination['state'],
  $destination['city'],
  $destination['zip'],
  $trip['linehaul']['rate'],
  $trip['broker']['id'],
  $new_lid,
  $trip['linehaul']['reference'],
  $lh_number = $tripno.$new_lid,
  $user,
  $appt
);
if (!($stmt)) {
  $system_callbac['code'] = "500";
  $system_callbac['query'] = $query;
  $system_callbac['message'] = "Error during INSERT TRIP_LINEHAUL variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callbac['code'] = "500";
  $system_callbac['query'] = $query;
  $system_callbac['message'] = "Error during INSERT TRIP_LINEHAUL query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if ($db->affected_rows == 0) {
  $system_callbac['code'] = $db->error;
  $system_callbac['message'] = "Something happened, no data was added to the database during INSERT TRIP_LINEHAUL query.";
  $system_callbac['data'] .= $row;
  exit_script($system_callback);
}

$pk_idlinehaul = $db->insert_id;

?>
