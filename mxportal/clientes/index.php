<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';

$dash_active = "";
$viajes_active = "";
$operadores_active = "";
$tractores_active = "";
$cajas_active = "";
$clientes_active = "active";

echo "<link rel='stylesheet' href='/plsuite/Resources/CSS/trips.css'>";
require $root . '/plsuite/Resources/PHP/Utilities/header_mexico.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';



 ?>
<div class="container-fluid align-items-right d-flex justify-content-end mb-2" style="margin-top: 65px">
  <div class="input-group mx-2 w-25">
    <input type="text" class="form-control" id="clientSearch_box" placeholder="" aria-label="search field" aria-describedby="basic-addon2">
    <div class="input-group-append">
      <span class="input-group-text" id="basic-addon2"><i class="fas fa-search"></i></span>
    </div>
  </div>
  <div class="">
    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#agregarCliente_modal" name="button">Nuevo</button>
  </div>
</div>

<div class="container-fluid" style="overflow-y: scroll; max-height: 80vh">
  <table class="table table-striped">
    <tbody id="table_mx_operations">
    </tbody>
  </table>
</div>

<?php
require 'modales/new_client.php';
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>

<script src="js/clientes.js" charset="utf-8"></script>
