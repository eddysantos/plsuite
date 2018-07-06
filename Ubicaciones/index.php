<link rel="stylesheet" href="/plsuite/Resources/jquery_ui_1_12_1/jquery-ui.min.css">
<link rel="stylesheet" href="/plsuite/Resources/c3_charts/c3.min.css">
<link rel="stylesheet" href="/plsuite/Resources/CSS/index.css">
<?php
$home_active = "active";
$dash_active = "";
$viajes_active = "";
$operadores_active = "";
$tractores_active = "";
$cajas_active = "";

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';
require $root . '/plsuite/Resources/PHP/Utilities/header.php';

$sunday = date('m/d/Y', strtotime('last sunday'));
// $today = date('Y-m-d', strtotime('today'));
$today = date('m/d/Y', strtotime('today'));

$sunday_5weeks = date('m/d/Y', strtotime('sunday 5 weeks ago'));
 ?>
<div class="container-fluid pt-3" style="margin-top: 50px; height: calc(100vh - 100px)">

<div class="d-flex justify-content-around">
  <div class="border p-2 mb-2 border mr-1 w-50" style="height: 325px">
    <h6>Northbound En Route: <span class="text-secondary" id="nb-count"></span></h6>
    <table class="table table-striped table-sm dash-table" id="northbound-trips">
      <thead>
        <tr>
          <th class="fit">Linehaul</th>
          <th class="fit">Unit</th>
          <th>Destination</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
  <div class="border p-2 mb-2 border ml-1 w-50">
    <h6>Southbound En Route: <span class="text-secondary" id="sb-count"></span></h6>
    <table class="table table-striped table-sm dash-table" id="southbound-trips">
      <thead>
        <tr>
          <th class="fit">Linehaul</th>
          <th class="fit">Unit</th>
          <th>Origin</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
  <div class="border p-2 mb-2 border ml-1 w-50">
    <h6>Pending Return Trip: <span class="text-secondary" id="pr-count"></span></h6>
    <table class="table table-striped table-sm dash-table" id="pending-return-trips">
      <thead>
        <tr>
          <th class="fit">Trip</th>
          <th class="fit">Trailer</th>
          <th>Appointment Date</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>
<div class="d-flex justify-content-around">
  <div class="border p-2 mb-2 border mr-1 w-50" style="height: 325px">
    <h6>Pending Invoice: <span class="text-secondary" id="pi-count"></span></h6>
    <table class="table table-striped table-sm dash-table" id="pending-invoice-trips">
      <thead>
        <tr>
          <th class="fit">Linehaul</th>
          <th class="fit">Trailer</th>
          <th>Closed Date (Days)</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
  <div class="border p-2 mb-2 border ml-1 w-50">
    <h6>Pending Payment: <span class="text-secondary" id="pp-count"></span></h6>
    <table class="table table-striped table-sm dash-table" id="pending-payment-trips">
      <thead>
        <tr>
          <th class="fit">Trip</th>
          <th class="fit">Trailer</th>
          <th>Payment Due Date</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>



</div>

<?php
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>

<script src="/plsuite/Resources/jquery_ui_1_12_1/jquery-ui.min.js" charset="utf-8"></script>
<script src="/plsuite/Resources/c3_charts/c3.min.js" charset="utf-8"></script>
<script src="/plsuite/Resources/c3_charts/d3.v5.min.js" charset="utf-8"></script>
<script src="zjs/index.js" charset="utf-8"></script>
