<?php



$query = "INSERT INTO ct_trip(fkid_trailer, trailer_number, trailer_plates, trip_year, trip_number, trip_number_i, added_by) VALUES(?,?,?,?,?,?,?)";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during INSERT TRIP query prepare [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$stmt->bind_param('sssssss',
  $trip['trailer']['id'],
  $trip['trailer']['number'],
  $trip['trailer']['plates'],
  $this_year,
  $tripno,
  $trip_number_i,
  $user
);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during INSERT TRIP variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during INSERT TRIP query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if ($db->affected_rows == 0) {
  $system_callback['code'] = $db->error;
  $system_callback['message'] = "Something happened, no data was added to the database during TRIP INSERT query.";
  exit_script($system_callback);
}

$pk_trip = $db->insert_id;

?>
