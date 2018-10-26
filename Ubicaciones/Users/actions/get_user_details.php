<?php

$data = $_GET;
$query = "SELECT u.Nombre Nombre, u.Apellido Apellido, u.pkIdUsers pkIdUsers, u.NombreUsuario NombreUsuario, u.Status Status, u.email email, u.fkid_broker fkid_broker, b.brokerName brokerName, u.TipoUsuario TipoUsuario FROM Users u LEFT JOIN ct_brokers b ON u.fkid_broker = b.pkid_broker WHERE pkIdUsers = ?";


$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('s', $data['user_id']);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during TRIP_LINEHAUL variables binding [$stmt->errno]: $stmt->error";
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
  $system_callback['message'] = "Script called successfully but there are no rows to display.";
  //$system_callback['data'] .= $row;
  exit_script($system_callback);
}

while ($row = $rslt->fetch_assoc()) {
  $get_user_details = $row;
}



 ?>
