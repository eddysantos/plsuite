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

$trips = array();
$cps = array();

extract($_POST);

switch ($status) {
  case 'Pendiente':
    $status = "AND cp.cp_status = 'Terminado'";
    break;

  case 'Vigentes':
    $status = "AND cp.cp_status = 'Terminado'";
    break;

  case 'Vencidas':
    $status = "AND cp.cp_status = 'Terminado'";
    break;

  case 'Todas':
    $status = "";
    break;

  default:
    $status = "";

    break;
}

$date_from = $date_from . " 00:00";
$date_to = $date_to . " 23:59";

$system_callback = [];
$query = "SELECT t.pk_mx_trip id, t.pk_mx_trip_number tripNumber , t.tractor_number tractorNumber , t.tractor_plates tractorPlates , t.trip_status tripStatus , t.date_created dateCreated , t.date_closed dateClosed , d.nameFirst firstName , d.nameLast lastName , cl.client_name clientName , cp.pk_carta_porte_number cp_id , cp.pk_carta_porte_number cp_number, cp.date_start date_start , cp.date_end date_end, cp.trailer_number trailer_number , cp.movement_type mov_type , cp.movement_class mov_class,  origin.place_alias origin, destin.place_alias destination, cp.cp_status cp_status FROM mx_trips t LEFT JOIN ct_drivers d ON d.pkid_driver = t.fk_driver LEFT JOIN mx_clients cl ON t.fk_mx_client = pk_mx_client LEFT JOIN mx_carta_porte cp ON cp.fk_mx_trip = t.pk_mx_trip LEFT JOIN mx_places origin ON origin.pk_mx_place = cp.fk_mx_place_origin LEFT JOIN mx_places destin ON destin.pk_mx_place = cp.fk_mx_place_destination WHERE cp.date_start BETWEEN ? AND ? $status ORDER BY id, cp_id";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('ss', $date_from, $date_to);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($system_callback);
}

$system_callback['status'] = $status;
$rslt = $stmt->get_result();

if ($rslt->num_rows == 0) {
  $system_callback['code'] = 2;
  $system_callback['message'] = "No se pudieron encontrar los datos del viaje. Porfavor notifique a sistemas.";
  exit_script($system_callback);
}

while ($row = $rslt->fetch_assoc()) {
  $system_callback['data'] .= "
  <tr>
    <td><span class='badge badge-pill badge-secondary $row[cp_status]'>$row[cp_status]</span></td>
    <td>$row[cp_number]</td>
    <td>$row[clientName]</td>
    <td>$row[date_start]</td>
    <td>$row[date_end]</td>
    <td>$row[origin] - $row[destination]</td>
    <td>$trailer[trailer_number]</td>
    <td>$row[mov_type]</td>
    <td>$row[mov_class]</td>
    <td data-toggle='slide-panel' data-target='#cpDetail_slidePanel' role='button'><i class='fas fa-chevron-right'></i></td>
  </tr>";
}

$system_callback['code'] = 1;
$system_callback['message'] = "Script called successfully!";
exit_script($system_callback);



 ?>
