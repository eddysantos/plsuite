<?php

$dash_active = "";
$viajes_active = "";
$operadores_active = "active";
$tractores_active = "";
$cajas_active = "";

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';
require $root . '/plsuite/Resources/PHP/Utilities/header.php';
 ?>
<div class="container-fluid align-items-center justify-content-between d-flex mb-3" style="margin-top: 65px">
  <h1 class="nb-id d-inline">Drivers</h1>
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
  <button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#addDriverModal" name="button">Add Driver</button>
</div>

<div class="container-fluid" style="overflow-y: scroll; max-height: 80vh">
  <table class="table table-hover">
    <thead class="nb-id">
      <th>Driver #</th>
      <th>Name</th>
      <th>Is Owner?</th>
      <th>Drives?</th>
      <th>Date Added</th>
      <th>Default Truck</th>
      <th></th>
    </thead>
    <tbody id="driversDash">
      <!-- <tr role="button">
        <td>1</td>
        <td>Gerardo Larranaga</td>
        <td>Yes</td>
        <td>Yes</td>
        <td>2017-01-15</td>
        <td class="text-right">
          <button type="button" class="btn btn-outline-secondary" z-index=9999 name="button"> <i class="fa fa-pencil"></i> </button>
          <button type="button" class="btn btn-outline-danger" z-index=9999 name="button"> <i class="fa fa-trash-o"></i> </button>
        </td>
      </tr> -->
    </tbody>
    <tbody>

    </tbody>
  </table>
</div>
<?php
require 'modales/addDriver.php';
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>
 <script src="js/drivers.js" charset="utf-8"></script>
