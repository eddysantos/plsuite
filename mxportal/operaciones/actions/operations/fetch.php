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
    $status = "AND t.trip_status = 'Pendiente'";
    break;

  case 'Abierto':
    $status = "AND t.trip_status = 'Abierto'";
    break;

  case 'Terminado':
    $status = "AND t.trip_status = 'Terminado'";
    break;

  case 'Cerrado':
    $status = "AND t.trip_status = 'Cerrado'";
    break;

  case 'Todos':
    $status = "";
    break;

  default:
    $status = "";

    break;
}

$date_from = $date_from . " 00:00";
$date_to = $date_to . " 23:59";

$system_callback = [];
$query = "SELECT t.pk_mx_trip id, t.pk_mx_trip_number tripNumber , t.tractor_number tractorNumber , t.tractor_plates tractorPlates , t.trip_status tripStatus , t.date_created dateCreated , t.date_closed dateClosed , d.nameFirst firstName , d.nameLast lastName , cl.client_name clientName , cp.pk_carta_porte_number cp_id , cp.pk_carta_porte_number cp_number, cp.date_start date_start , cp.trailer_number trailer_number , cp.movement_type mov_type , origin.place_alias origin, destin.place_alias destination, cp.cp_status cp_status FROM mx_trips t LEFT JOIN ct_drivers d ON d.pkid_driver = t.fk_driver LEFT JOIN mx_clients cl ON t.fk_mx_client = pk_mx_client LEFT JOIN mx_carta_porte cp ON cp.fk_mx_trip = t.pk_mx_trip LEFT JOIN mx_places origin ON origin.pk_mx_place = cp.fk_mx_place_origin LEFT JOIN mx_places destin ON destin.pk_mx_place = cp.fk_mx_place_destination WHERE t.date_created BETWEEN ? AND ? $status ORDER BY id, cp_id";

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

$rslt = $stmt->get_result();

if ($rslt->num_rows == 0) {
  $system_callback['code'] = 2;
  $system_callback['message'] = "No se pudieron encontrar los datos del viaje. Porfavor notifique a sistemas.";
  exit_script($system_callback);
}

while ($row = $rslt->fetch_assoc()) {
  $trips[$row['id']] = array(
    'trip_number'=>$row['tripNumber'],
    'tractor'=>$row['tractorNumber'],
    'status'=>$row['tripStatus'],
    'driver'=>$row['firstName'] . " " . $row['lastName'],
    'client'=>$row['clientName'],
  );
  $cps[$row['id']][$row['cp_id']] = array(
    'numero'=>$row['cp_number'],
    'date_start'=>$row['date_start'],
    'trailer'=>$row['trailer_number'],
    'type'=>$row['mov_type'],
    'origin'=>$row['origin'],
    'destination'=>$row['destination'],
    'status'=>$row['cp_status']
  );
}

foreach ($trips as $id => $trip) {
  $cartas_porte = "";
  foreach ($cps[$id] as $cp_id => $cp) {
    $cancelado = $cp['status'] == "Cancelada" ? " - Cancelada" : "";
    $cartas_porte .= "
    <div class='align-self-center'>
      <span>$cp[numero]</span> <span class='badge badge-pill badge-secondary $cp[status]'>$cp[type]$cancelado</span>
      <div class='ml-2 text-secondary'><b>$cp[trailer]</b> | <span class='font-weight-light'>$cp[origin] - $cp[destination]</span></div>
    </div>
    ";
  }

  $system_callback['data'] .= "
  <tr class='d-flex' trip-id='$id' role='button'>
    <td class='flex-shrink-1'>
      <i class='fas fa-circle align-self-center $trip[status]'></i>
    </td>
    <td class='flex-grow-1'>
      <div class='d-flex justify-content-between'>
        <span><b class='trip-no'>$trip[trip_number]</b> | <span class='font-weight-light'>$trip[client]</span></span>
        <span><span class='tractor-no'>$trip[tractor]</span> | <span>$trip[driver]</span></span>
      </div>
      <div class='ml-4'>
        $cartas_porte
      </div>
    </td>
  </tr>";
}

$system_callback['trips'] = $trips;
$system_callback['cps'] = $cps;

$system_callback['code'] = 1;
$system_callback['message'] = "Script called successfully!";
exit_script($system_callback);



 ?>
