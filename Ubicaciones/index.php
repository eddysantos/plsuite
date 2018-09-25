<link rel="stylesheet" href="/plsuite/Resources/jquery_ui_1_12_1/jquery-ui.min.css">
<link rel="stylesheet" href="/plsuite/Resources/c3_charts/c3.min.css">
<link rel="stylesheet" href="/plsuite/Resources/CSS/index.css">
<?php
$home_active = "active custom";
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

<div class="main-details-container">
  <div class="row">
    <div class="col-sm-2 ml-0 pl-0 border border-bottom-0 border-left-0 border-top-0 ml-0 pl-0 pr-0">
      <nav class="nav flex-column" id="dashboard-nav-pane" role="tablist">
        <a class="nav-link dash active" id="div3-tab" data-toggle="tab" role="tab" aria-selected="true" aria-controls="div3" href="#division3-pane">Division 3</a>
        <a class="nav-link dash" id="div4-tab" data-toggle="tab" role="tab" aria-selected="false" aria-controls="div4" href="#division4-pane">Division 4</a>
      </nav>
    </div>
    <div class="col-sm-10 tab-info">
      <div class="tab-content p-2" id="dashboard-tab-content">
        <div class="tab pane show active" id="division3-pane" role="tabpanel" aria-labelledby="div3-tab">
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
    </div>
  </div>
</div>
<!-- <div class="container-fluid pt-3" style="margin-top: 50px; height: calc(100vh - 100px)">
  <div class="row">
    <div class="col">
      <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Division 3</a>
        <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Division 4</a>
      </div>
    </div>
    <div class="col-10">
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
  </div>
</div> -->

<?php
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>

<script src="/plsuite/Resources/jquery_ui_1_12_1/jquery-ui.min.js" charset="utf-8"></script>
<script src="/plsuite/Resources/c3_charts/c3.min.js" charset="utf-8"></script>
<script src="/plsuite/Resources/c3_charts/d3.v5.min.js" charset="utf-8"></script>
<script src="zjs/index.js" charset="utf-8"></script>
