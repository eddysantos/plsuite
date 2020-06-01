<?php

$status_change = [];
$query = "SELECT cp.date_start date_start , cp.date_end date_end FROM mx_carta_porte cp WHERE pk_carta_porte = ?";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $status_change['code'] = "500";
  $status_change['message'] = "Error during query prepare [$db->errno]: $db->error";
}

$stmt->bind_param('s', $pk_carta_porte);
if (!($stmt)) {
  $status_change['code'] = "500";
  $status_change['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
}

if (!($stmt->execute())) {
  $status_change['code'] = "500";
  $status_change['message'] = "Error during query execution [$db->errno]: $db->error";
}

$rslt = $stmt->get_result();

if ($rslt->num_rows == 0) {
  $status_change['code'] = 2;
  $status_change['message'] = "No se encontraron movimientos para esta operación.";
}

while ($row = $rslt->fetch_assoc()) {
  $info_cp  = $row;
}

if ($info_cp['date_start'] != "") {
  $status = "Abierto";
}

if ($info_cp['date_end'] != "") {
  $status = "Terminado";
}


$update_movement = "UPDATE mx_carta_porte SET cp_status = ? WHERE pk_carta_porte = ?";

$update_movement = $db->prepare($update_movement);
if (!($update_movement)) {
  $status_change['code'] = "500";
  $status_change['message'] = "Error during query prepare [$db->errno]: $db->error";
}

$update_movement->bind_param('ss', $status, $pk_carta_porte);
if (!($update_movement)) {
  $status_change['code'] = "500";
  $status_change['message'] = "Error during variables binding [$update_movement->errno]: $update_movement->error";
}

if (!($update_movement->execute())) {
  $status_change['code'] = "500";
  $status_change['message'] = "Error during query execution [$db->errno]: $db->error";
}

if ($db->affected_rows == 0) {
  $status_change['code'] = 2;
  $status_change['rslt'] = $rslt;
  $status_change['message'] = "No se detecto ningún cambio al registro.";
}

?>
