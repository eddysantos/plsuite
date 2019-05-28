<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';

$dash_active = "";
$viajes_active = "active";
$operadores_active = "";
$tractores_active = "";
$cajas_active = "";

echo "<link rel='stylesheet' href='/plsuite/Resources/CSS/trips.css'>";
require $root . '/plsuite/Resources/PHP/Utilities/header_mexico.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';
require $root . '/plsuite/mxportal/resources/php/client_list.php';
require $root . '/plsuite/mxportal/resources/php/driver_list.php';
require $root . '/plsuite/mxportal/resources/php/truck_list.php';
require $root . '/plsuite/mxportal/resources/php/trailer_list.php';


$clientes = client_list(); //Obtained from client_list.php
$operadores = driver_list(); //Obtained from driver_list.php
$tractores = truck_list(); //Obtained from truck_list.php
$remolques = trailer_list(); //Obtained from trailer_list.php


 ?>
<div class="container-fluid align-items-right d-flex justify-content-between align-content-center mb-3 position-sticky" style="margin-top: 65px">
  <ul class="nav nav-pills" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" href="#" data-toggle="tab" role="tab" aria-selected="true">Todos</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#" data-toggle="tab" role="tab" aria-selected="false">Abiertos</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#" data-toggle="tab" role="tab" aria-selected="false">Cerrados</a>
    </li>
  </ul>
  <div class="d-flex">
    <div class="input-group mx-2">
      <input type="text" class="form-control h-100" id="tripSearch_box" placeholder="" aria-label="search field" aria-describedby="basic-addon2">
      <div class="input-group-append">
        <span class="input-group-text" id="basic-addon2"><i class="fas fa-search"></i></span>
      </div>
    </div>
    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#nuevaOperacion_modal" name="button">Nuevo</button>
  </div>
</div>

<div class="container-fluid" style="overflow-y: scroll; max-height: 80vh">
  <table class="table table-striped">
    <tbody id="table_mx_operations">
    </tbody>
  </table>
</div>

<?php
require 'modales/nueva_operacion.php';
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>

<script src="/plsuite/mxportal/resources/js/client_popup.js" charset="utf-8"></script>
<script src="js/operaciones.js" charset="utf-8"></script>
