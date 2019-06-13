<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';

$contabilidad_active = "active";

echo "<link rel='stylesheet' href='/plsuite/Resources/CSS/trips.css'>";
require $root . '/plsuite/Resources/PHP/Utilities/header_mexico.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';


$date_from = date('Y-m-d', strtotime('today -7 days'));
$date_to = date('Y-m-d', strtotime('today'));


 ?>
<div class="container-fluid align-items-right d-flex justify-content-between align-content-center mb-3 position-sticky" style="margin-top: 65px">
  <div class="btn-group btn-group-sm" id="status-filter-btns" role="group" aria-label="Trip Status">
    <button type="button" class="btn btn-outline-primary active" data-status-filter="Pendiente">Pendientes</button>
    <button type="button" class="btn btn-outline-primary" data-status-filter="Abierto">Vigentes</button>
    <button type="button" class="btn btn-outline-primary" data-status-filter="Terminado">Vencidas</button>
    <button type="button" class="btn btn-outline-primary" data-status-filter="Cerrado">Todas</button>
  </div>
  <div class="input-group input-group-sm w-auto">
    <div class="input-group-prepend" id="date_selection">
      <button class="btn btn-outline-primary active" type="button" data-date-filter="week">7 dias</button>
      <button class="btn btn-outline-primary" type="button" data-date-filter="month">Este Mes</button>
    </div>
    <input type="date" class="form-control border-primary" id="date-filter-from" placeholder="" value="<?php echo $date_from ?>" aria-label="date-from">
    <input type="date" class="form-control border-primary" id="date-filter-to" placeholder="" value="<?php echo $date_to ?>" aria-label="date-to">
  </div>
  <div class="d-flex">
    <div class="input-group mx-2">
      <input type="text" class="form-control form-control-sm h-100" id="tripSearch_box" placeholder="" aria-label="search field" aria-describedby="basic-addon2">
      <div class="input-group-append">
        <span class="input-group-text" id="basic-addon2"><i class="fas fa-search"></i></span>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid" style="overflow-y: scroll; max-height: 80vh">
  <table class="table table-striped table-sm">
    <thead>
      <tr>
        <th>Carta Porte</th>
        <th>Cliente</th>
        <th>Inicio</th>
        <th>Fin</th>
        <th>Ruta</th>
        <th>Remolque</th>
        <th>Tipo</th>
        <th>Clase</th>
        <th></th>
      </tr>
    </thead>
    <tbody id="table_mx_cartas_porte">
      <tr>
        <td>MX1900011</td>
        <td>CH Robinson</td>
        <td>26/12/1990</td>
        <td>27/12/1990</td>
        <td>Patio PLAA - Motores Planta 1</td>
        <td>NONZ977976</td>
        <td>Viaje</td>
        <td>Cargado</td>
        <td data-toggle="slide-panel" data-target="#cpDetail_slidePanel"><i class="fas fa-chevron-right"></i></td>
      </tr>
      <!-- <tr>

      </tr>
      <tr>
        <td>
          No se encontraron cartas porte
        </td>
      </tr> -->
    </tbody>
  </table>
</div>

<?php

require 'modales/cpDetail.php';
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>

<script src="/plsuite/mxportal/resources/js/client_popup.js" charset="utf-8"></script>
<script src="js/contabilidad.js" charset="utf-8"></script>
