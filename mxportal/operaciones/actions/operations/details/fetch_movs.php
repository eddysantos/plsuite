<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$id = $_POST['mx_trip'];
$closeable = false;
$open = 0;
$system_callback = [];

//Fetch trip info - for buttons purposes.



$query = "SELECT trip_status FROM mx_trips WHERE pk_mx_trip = ?";

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
  $system_callback['message'] = "No se encontraron movimientos para esta operación.";
  exit_script($system_callback);
}

$trip = $rslt->fetch_assoc();


$query = "SELECT cp.pk_carta_porte pkCartaPorte, cp.pk_carta_porte_number cpNumber, cp.cp_status cp_status, cp.fk_mx_trip fk_mx_trip, cp.date_start startDate, cp.date_end endDate, cp.trailer_number trailerNumber, cp.trailer_plates trailerPlates, cp.movement_type type, cp.movement_class class, cp.odometer_start odoStart, cp.odometer_end odoEnd, po.place_alias origin, pd.place_alias destination, cp.comentarios comments FROM mx_carta_porte cp LEFT JOIN mx_places po ON cp.fk_mx_place_origin = po.pk_mx_place LEFT JOIN mx_places pd ON cp.fk_mx_place_destination = pd.pk_mx_place WHERE fk_mx_trip = ? ORDER BY cpNumber ASC";

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
  $system_callback['message'] = "No se encontraron movimientos para esta operación.";
  exit_script($system_callback);
}

while ($row = $rslt->fetch_assoc()) {
  if (!in_array($row['cp_status'],['Terminado', 'Cancelada'])) {
    $open++;
  }
  if ($trip['trip_status'] != "Abierto") {
    $buttons = "
    <div class='btn-group' role='group' aria-label='Basic example'>
    <button data-cp='$row[pkCartaPorte]' data-toggle='document' type='button' class='btn btn-sm btn-outline-dark'>Ver CP</button>
    </div>";
  } else {
    $buttons = "
    <div class='btn-group' role='group' aria-label='Basic example'>
    <button type='button' class='btn btn-sm btn-outline-dark' data-toggle='modal' data-target='#editarMovimiento_modal' data-cp-number='$row[cpNumber]' data-cp-id='$row[pkCartaPorte]' data-action-type='edit'>Editar Movimiento</button>
    <button data-cp='$row[pkCartaPorte]' data-toggle='document' type='button' class='btn btn-sm btn-outline-dark'>Ver CP</button>
    <button data-cp='$row[pkCartaPorte]' type='button' data-toggle='cancel-cp' class='btn btn-sm btn-outline-danger'>Cancelar CP</button>
    </div>";
  }

  if ($row['cp_status'] == "Cancelada") {
    $cancelada = " - Cancelada";
    $details = "";
  } else {
    $cancelada = "";
    $details = "<div class='d-flex justify-content-end mb-1'>
      $buttons
    </div>
    <div class='w-100 border mov-details-box rounded p-3 bg-white'>
      <div class='d-flex justify-content-between mb-1'>
        <div class='justify-content-between'>
          <div class='form-inline justify-content-between'>
            Fecha Inicio:
            <input type='date' class='form-control form-control-sm custom-input ml-1' name='date_start' value='$row[startDate]'>
          </div>
          <div class='form-inline justify-content-between mt-1'>
            Fecha Cierre:
            <input type='date' class='form-control form-control-sm custom-input ml-1' name='date_end' value='$row[endDate]'>
          </div>
        </div>
        <div class='justify-content-between'>
          <div class='form-inline justify-content-between'>
            Odómetro Inicio:
            <input type='number' class='form-control form-control-sm custom-input ml-1' name='odometer_start' value='$row[odoStart]'>
          </div>
          <div class='form-inline justify-content-between mt-1'>
            Odómetro Final:
            <input type='number' class='form-control form-control-sm custom-input ml-1' name='odometer_end' value='$row[odoEnd]'>
          </div>
        </div>
        <button type='button' class='btn btn-sm btn-outline-primary' data-cp-id='$row[pkCartaPorte]' name='saveDetails_btn'>Guardar Detalle</button>
      </div>
      <div class='form-inline'><input type='text' class='form-control form-control-sm w-100 custom-input' value='$row[comments]' name='comments' placeholder='Comentarios'></div>
    </div>";
  }



  $system_callback['data'] .=
  "<tr class='border' data-cp-number='$row[cpNumber]' data-cp-id='$row[pkCartaPorte]'>
    <td>
      <div class='d-flex'>
        <div class='align-self-center'>
          <span class='badge badge-pill badge-primary $row[cp_status]'>$row[type]$cancelada</span>
        </div>
        <div class='flex-grow-1 ml-3'>
          <div class=''>
            <span class='text-secondary'>$row[cpNumber]</span> <span class='text-info'>[$row[class]]</span>
          </div>
          <div class=''>
            $row[origin] - $row[destination]
          </div>
        </div>
        <div class=''>
          <span>$row[trailerNumber]</span>
          <span class='text-secondary'>[$row[trailerPlates]]</span>
        </div>
      </div>
      $details
    </td>
  </tr>";
}

if ($open == 0) {
  $closeable = true;
}

$system_callback['closeable'] = $closeable;
$system_callback['code'] = 1;
$system_callback['message'] = "Script called successfully!";
exit_script($system_callback);



 ?>
