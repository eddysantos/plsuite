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



 ?>
<div class="container-fluid align-items-right d-flex justify-content-between align-content-center mb-3 position-sticky" style="margin-top: 65px">
  <ul class="nav nav-pills" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" href="#" data-toggle="tab" role="tab" aria-selected="true">Todos</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#" data-toggle="tab" role="tab" aria-selected="false">Viajes</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#" data-toggle="tab" role="tab" aria-selected="false">Cruces</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#" data-toggle="tab" role="tab" aria-selected="false">Arrastres</a>
    </li>
  </ul>
  <div class="">
    <button type="button" class="btn btn-outline-primary" name="button">Nuevo</button>
  </div>
</div>

<div class="container-fluid" style="overflow-y: scroll; max-height: 80vh">
  <table class="table table-striped">
    <tbody id="table_mx_operations">
    </tbody>
  </table>
</div>

<?php
require 'modales/addTrip.php';
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>

<script src="/plsuite/Ubicaciones/Viajes/js/trips.js" charset="utf-8"></script>
