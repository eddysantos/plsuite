<?php

$query = "INSERT INTO ct_trip(fkid_trailer, trailer_number, trailer_plates, trip_year, trip_number, added_by) VALUES(?,?,?,?,?,?)";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during INSERT TRIP query prepare [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$stmt->bind_param('ssssss',
$system_callback['trailer']['data']['id'],
$system_callback['trailer']['data']['trailerNumber'],
$system_callback['trailer']['data']['trailerPlates'],
$thisYear,
$tripno,
$user_name
);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during INSERT TRIP variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during INSERT TRIP query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if ($db->affected_rows == 0) {
  $system_callback['query']['code'] = $db->error;
  $system_callback['query']['message'] = "Something happened, no data was added to the database during TRIP INSERT query.";
  $system_callback['query']['data'] .= $row;
  exit_script($system_callback);
}

$trip_id = $db->insert_id;


?>
