<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];
$data = $_POST;

if ($data['departure']['date'] != "") {
  $depart = date('Y-m-d H:i', strtotime($data['departure']['date'] . " " . $data['departure']['time']['hour'] . ":" . $data['departure']['time']['minute']));
} else {
  $depart = NULL;
}

if ($data['arrival']['date'] != "") {
  $arriv = date('Y-m-d H:i', strtotime($data['arrival']['date'] . " " . $data['arrival']['time']['hour'] . ":" . $data['arrival']['time']['minute']));
} else {
  $arriv = NULL;
}

if ($data['delivery']['date'] != "") {
  $delivery = date('Y-m-d H:i', strtotime($data['delivery']['date'] . " " . $data['delivery']['time']['hour'] . ":" . $data['delivery']['time']['minute']));
} else {
  $delivery = NULL;
}

if ($data['appt']['from']['date'] != "") {
  $appt_from = date('Y-m-d H:i', strtotime($data['appt']['from']['date'] . " " . $data['appt']['from']['time']['hour'] . ":" . $data['appt']['from']['time']['minute']));
} else {
  $appt_from = NULL;
}

if ($data['appt']['to']['date'] != "") {
  $appt_to = date('Y-m-d H:i', strtotime($data['appt']['to']['date'] . " " . $data['appt']['to']['time']['hour'] . ":" . $data['appt']['to']['time']['minute']));
} else {
  $appt_to = NULL;
}


$query = "UPDATE ct_trip_linehaul SET fkid_broker = ?, trip_rate = ?, origin_zip = ?, origin_state = ?, origin_city = ?, destination_zip = ?, destination_state = ?, destination_city = ?, date_departure = ?, date_arrival = ?, date_appointment = ?, date_appointment_to = ?, linehaul_status = ?, date_delivery = ?, broker_reference = ?, lh_comment = ? WHERE pk_idlinehaul = ? ";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('sssssssssssssssss',
  $data['broker'],
  floatval(preg_replace('/[^\d.]/', '', $data['triprate'])),
  $data['ozip'],
  $data['ostate'],
  $data['ocity'],
  $data['dzip'],
  $data['dstate'],
  $data['dcity'],
  $depart,
  $arriv,
  $appt_from,
  $appt_to,
  $data['status'],
  $delivery,
  $data['broker_reference'],
  $data['comments'],
  $data['lid']
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
  $system_callback['departure'] = $depart;
  $system_callback['arrival'] = $arriv;
  $system_callback['delivery'] = $delivery;
  $system_callback['appt'] = $appt;
  exit_script($system_callback);
}


$system_callback['code'] = 1;
$system_callback['message'] = "Script called successfully!";
exit_script($system_callback);

?>
