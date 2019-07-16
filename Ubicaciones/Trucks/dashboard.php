<?php

$dash_active = "";
$viajes_active = "";
$operadores_active = "";
$tractores_active = "active";
$cajas_active = "";

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';
require $root . '/plsuite/Resources/PHP/Utilities/header.php';
 ?>
<div class="container-fluid align-items-center justify-content-between d-flex mb-3" style="margin-top: 70px">
  <h1 class="nb-id d-inline">Trucks</h1>
  <!-- <div class="btn-group" role="group" aria-label="trip-filter-byweek">
    <button class="btn btn-outline-success nb-id" type="button" name="lastWeek" role="button">Last Week</button>
    <button class="btn btn-outline-success nb-id" type="button" name="thisWeek" role="button">This Week</button>
  </div>
  <div class="btn-group" role="group" aria-label="trip-filter-day">
    <button class="btn btn-outline-success nb-id" type="button" name="yesterday" role="button">Yesterday</button>
    <button class="btn btn-outline-success nb-id" type="button" name="today" role="button">Today</button>
    <button class="btn btn-outline-success nb-id" type="button" name="tomorrow" role="button">Tomorrow</button>
  </div>
  <form class="form-inline" id="customTripSearch" onsubmit="return false;">
    <input class="form-control border-success text-success" type="date" name="from" value="">
    <input class="form-control border-success text-success" type="date" name="to" value="">
    <button type="submit" class="btn btn-success ml-1" name="customSearchSubmit">Search</button>
  </form> -->
  <button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#addTruckModal" name="button">Add Truck</button>
</div>

<div class="container-fluid" style="overflow-y: scroll; height: calc(100vh - 190px)">
  <table class="table table-hover">
    <thead class="nb-id">
      <th>Truck #</th>
      <th>VIN</th>
      <th>CO / OOP</th>
      <th>Status</th>
      <th>Date Added</th>
      <th>Plates</th>
      <th></th>
    </thead>
    <tbody id="truckDash">
    </tbody>
  </table>
</div>
<?php
require 'modales/addTruck.php';
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>
 <script src="js/trucks.js" charset="utf-8"></script>
