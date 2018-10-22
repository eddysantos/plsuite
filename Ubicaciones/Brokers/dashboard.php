<?php

$dash_active = "";
$viajes_active = "";
$operadores_active = "";
$tractores_active = "";
$cajas_active = "";
$brokers_active = "active";

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';
require $root . '/plsuite/Resources/PHP/Utilities/header.php';
 ?>
<div class="container-fluid align-items-center justify-content-between d-flex mb-3" style="margin-top: 65px">
  <h1 class="nb-id d-inline">Brokers</h1>
  <button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#addBrokerModal" name="button">Add Broker</button>
</div>

<div class="container-fluid" style="overflow-y: scroll; height: calc(100vh - 190px)">
  <table class="table table-hover">
    <thead class="nb-id">
      <th>Broker ID</th>
      <th>Name</th>
      <th>Main Contact</th>
      <th>Phone</th>
      <th>Extension</th>
      <th>Cell Phone</th>
    </thead>
    <tbody id="brokersDash">
    </tbody>
    <tbody>

    </tbody>
  </table>
</div>
<?php
require 'modales/addBroker.php';
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>
 <script src="js/brokers.js" charset="utf-8"></script>
