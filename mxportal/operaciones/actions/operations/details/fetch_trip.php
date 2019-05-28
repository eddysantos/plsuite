<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function parseDate($datestamp){
  $return = array(
    'date'=>"",
    'time'=>array(
      'hour'=>"",
      'minute'=>""
    )
  );

  if ($datestamp == "") {
    return $return;
  }

  $return['date'] = date('Y-m-d', strtotime($datestamp));
  $return['time']['hour'] = date('H', strtotime($datestamp));
  $return['time']['minute'] = date('i', strtotime($datestamp));

  return $return;
}

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$id = $_POST['mx_trip'];

$system_callback = [];
$query = "SELECT t.tractor_number tractorNumber, t.tractor_plates tractorPlates, t.trip_status tripStatus, t.date_created dateCreated, t.date_closed dateClosed, d.nameFirst firstName, d.nameLast lastName, cl.client_name clientName, cl.pk_mx_client pk_mx_client FROM mx_trips t LEFT JOIN ct_drivers d ON d.pkid_driver = t.fk_driver LEFT JOIN mx_clients cl ON t.fk_mx_client = pk_mx_client WHERE t.pk_mx_trip = ?";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('s', $id);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();

if ($rslt->num_rows == 0) {
  $system_callback['code'] = 2;
  $system_callback['message'] = "No se pudieron encontrar los datos del viaje. Porfavor notifique a sistemas.";
  exit_script($system_callback);
}

while ($row = $rslt->fetch_assoc()) {
  $system_callback['data'] = $row;
}

$dateCreated = parseDate($system_callback['data']['dateCreated']);
$dateClosed = parseDate($system_callback['data']['dateClosed']);

$system_callback['data']['dateCreated'] = $dateCreated['date'];
$system_callback['data']['dateClosed'] = $dateClosed['date'];

$system_callback['code'] = 1;
$system_callback['message'] = "Script called successfully!";
exit_script($system_callback);



 ?>
