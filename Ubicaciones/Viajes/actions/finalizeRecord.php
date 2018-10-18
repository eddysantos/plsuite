<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];
$data = $_POST;

$query = "";

$date_close = date('Y-m-d H:i:s', strtotime('today'));

if ($data['record_to_edit'] == "trip") {
  $query = "UPDATE ct_trip SET trip_status = ?, date_close = ? WHERE pkid_trip = ?";
} else if ($data['record_to_edit'] == "linehaul") {
  $query = "UPDATE ct_trip_linehaul SET linehaul_status = ?, date_end = ? WHERE pk_idlinehaul = ?";
}

if ($query == "") {
  $system_callback['code'] = "501";
  $system_callback['message'] = "Type of record to close was not specified.";
  exit_script($system_callback);
}

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('sss',
  $data['action'],
  $date_close,
  $data['id']
);

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

$affected = $stmt->affected_rows;

if ($affected == 0) {
  $system_callback['code'] = 2;
  $system_callback['message'] = "No data was changed.";
  $system_callback['data'] = $data;
  exit_script($system_callback);
}

$system_callback['code'] = 1;
$system_callback['message'] = "Script called successfully!";
exit_script($system_callback);

?>
