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
  <div class="row div-100h">
    <div class="col-sm-2 ml-0 pl-0 border border-bottom-0 border-left-0 border-top-0 ml-0 pl-0 pr-0">
      <nav class="nav flex-column nav-30-px" id="truck-details-nav-pane" role="tablist">
        <div class="p-1">
          <h4>Operations</h4>
        </div>
        <a class="nav-link dash side-panel active" id="nb-en-route-tab" data-toggle="tab" role="tab" aria-selected="true" aria-controls="nb-en-route" href="#nb-en-route-pane">
          <span class="badge badge-danger nb-count float-right"></span>
          Northbound En Route
        </a>
        <a class="nav-link dash side-panel" id="pending-return-trip-tab" data-toggle="tab" role="tab" aria-selected="false" aria-controls="pending-return-trip-dash" href="#pending-return-trip-pane">
          <span class="badge badge-danger pr-count float-right"></span>
          Pending Return Trip
        </a>
        <a class="nav-link dash side-panel" id="sb-en-route-tab" data-toggle="tab" role="tab" aria-selected="false" aria-controls="sb-en-route-dash" href="#sb-en-route-pane">
          <span class="badge badge-danger sb-count float-right"></span>
          Soutbound En Route
        </a>
        <a class="nav-link dash side-panel" id="pending-delivery-tab" data-toggle="tab" role="tab" aria-selected="false" aria-controls="pending-delivery-tab" href="#pending-delivery-pane">
          <span class="badge badge-danger pd-count float-right"></span>
          Pending Delivery
        </a>
        <div class="p-1">
          <h4>Administration</h4>
        </div>
        <a class="nav-link dash side-panel" id="pending-invoice-tab" data-toggle="tab" role="tab" aria-selected="true" aria-controls="pending-invoice-dash" href="#pending-invoice-pane">
          Pending Invoice
          <span class="badge badge-danger pi-count float-right"></span>
          <span class="pi-amount"></span>
        </a>
        <a class="nav-link dash side-panel" id="pending-payment-tab" data-toggle="tab" role="tab" aria-selected="false" aria-controls="pending-payment-dash" href="#pending-payment-pane">
          Pending Payment
          <span class="badge badge-danger pp-count float-right"></span>
          <span class="pp-amount"></span>
        </a>
      </nav>
    </div>
    <div class="col-sm-10 tab-info" style="overflow: hidden">
      <div class="tab-content p-2" id="dashboard-content">
        <div class="tab-pane fade" id="pending-invoice-pane" role="tabpanel" aria-labelledby="pending-invoice-tab">
          <section class="">
            <button type="button" class="btn btn-outline-secondary float-right this-week-toggle" action="pi" name="button">
              [<span>-</span>] This Week
            </button>
            <h6>Pending Invoice: <span class="text-secondary pi-count"></span></h6>
          </section>
          <div class="table-container">
            <table class="table table-striped table-sm dash-table" id="pending-invoice-trips">
              <thead>
                <tr>
                  <th style="width: 80px">Linehaul</th>
                  <th style="width: 160px">Trailer</th>
                  <th>Delivery Date</th>
                  <th>Amount</th>
                  <th>Client</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
        <div class="tab-pane fade" id="pending-payment-pane" role="tabpanel" aria-labelledby="pending-invoice-tab">
          <section class="">
            <h6>Pending Payment: <span class="text-secondary pp-count"></span></h6>
            <div class="table-container">
              <table class="table table-striped table-sm dash-table" id="pending-payment-trips">
                <thead>
                  <tr>
                    <th style="width: 80px">Linehaul</th>
                    <th style="width: 80px">Invoice</th>
                    <th>Client</th>
                    <th>Payment Due</th>
                    <th style="width: 80px">Amount</th>
                    <th style="width: 120px">Reference</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </section>
        </div>
        <div class="tab-pane fade show active" id="nb-en-route-pane">
          <section class="">
            <h6 class="mb-0">Northbound En Route: <span class="text-secondary nb-count"></span></h6>
            <div class="h-100">
              <table class="table table-striped table-sm dash-table" id="northbound-trips">
                <thead>
                  <tr>
                    <th style="width: 80px">Linehaul</th>
                    <th style="width: 80px">Truck</th>
                    <th style="width: 160px">Trailer</th>
                    <th>Driver</th>
                    <th>Destination</th>
                    <th>Broker</th>
                    <th>Appointment</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </section>
        </div>
        <div class="tab-pane fade" id="sb-en-route-pane">
          <section class="">
            <h6 class="mb-0">Southbound En Route: <span class="text-secondary sb-count"></span></h6>
            <div class="table-container">
              <table class="table table-striped table-sm dash-table" id="southbound-trips">
                <thead>
                  <tr>
                    <th style="width: 80px">Linehaul</th>
                    <th style="width: 80px">Truck</th>
                    <th style="width: 160px">Trailer</th>
                    <th>Driver</th>
                    <th>Origin</th>
                    <th>Broker</th>
                    <th>Start Date</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </section>
        </div>
        <div class="tab-pane fade" id="pending-return-trip-pane">
          <section class="">
            <h6 class="mb-0">Pending Return Trip: <span class="text-secondary pr-count"></span></h6>
            <div class="table-container">
              <table class="table table-striped table-sm dash-table" id="pending-return-trips">
                <thead>
                  <tr>
                    <th style="width: 80px">Trip</th>
                    <th style="width: 80px">Truck</th>
                    <th style="width: 160px">Trailer</th>
                    <th>Driver</th>
                    <th>Appointment Date</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </section>
        </div>
        <div class="tab-pane fade" id="pending-delivery-pane">
          <section class="">
            <h6 class="mb-0">Pending Delivery: <span class="text-secondary pd-count"></span></h6>
            <div class="table-container">
              <table class="table table-striped table-sm dash-table" id="pending-delivery-trips">
                <thead>
                  <tr>
                    <th class="" style="width: 80px">Linehaul</th>
                    <th class="" style="">Conveyance</th>
                    <th class="" style="">Origin</th>
                    <th class="">Appointment</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </section>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- <div class="main-details-container">
  <ul class="nav nav-pills pt-3" id="dash-select-division" role="tablist">
    <li class="nav-item">
      <a class="nav-link" id="tab-div-4" data-toggle="tab" href="#division4-pane" role="tab" aria-controls="div4" aria-selected="true">Division 4</a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" id="tab-div-3" data-toggle="tab" href="#division3-pane" role="tab" aria-controls="div3" aria-selected="false">Division 3</a>
    </li>
  </ul>
  <div class="tab-content p-2" id="dashboard-tab-content">
    <div class="tab-pane fade show active" id="division3-pane" role="tabpanel" aria-labelledby="div3-tab">
      <div class="d-flex justify-content-around">
        <div class="border p-2 mb-2 border mr-1 w-50" style="height: 325px">
          <section class="">
            <button type="button" class="btn btn-outline-secondary float-right this-week-toggle" action="pi" name="button">
              [<span>-</span>] This Week
            </button>
            <h6>Pending Invoice: <span class="text-secondary" id="pi-count"></span></h6>
          </section>
          <table class="table table-striped table-sm dash-table" id="pending-invoice-trips">
            <thead>
              <tr>
                <th>Linehaul</th>
                <th>Trailer</th>
                <th>Closed Date</th>
                <th>Amount</th>
                <th>Client</th>
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
                <th>Invoice</th>
                <th>Client</th>
                <th>Payment Due</th>
                <th>Amount</th>
                <th>Reference</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="tab-pane fade" id="division4-pane" role="tabpanel" aria-labelledby="div4-tab">
      <div class="d-flex justify-content-around">



        <div class="border p-2 mb-2 border mr-1 w-50" style="height: 325px">
          <h6 class="mb-0">Northbound En Route: <span class="text-secondary" id="nb-count"></span></h6>
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
          <h6 class="mb-0">Southbound En Route: <span class="text-secondary" id="sb-count"></span></h6>
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
          <h6 class="mb-0">Pending Return Trip: <span class="text-secondary" id="pr-count"></span></h6>
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
      <div class="d-flex">
        <div class="border p-2">
          <h6 class="mb-0">Pending Delivery: <span class="text-secondary" id="pd-count"></span></h6>
          <table class="table table-striped table-sm dash-table" id="pending-delivery-trips" style="height: 235px">
            <thead>
              <tr>
                <th class="">Linehaul</th>
                <th class="">Trailer</th>
                <th class="">Truck</th>
                <th class="">Broker</th>
                <th>Appointment</th>
              </tr>
            </thead>
            <tbody style="height: 200px">
            </tbody>
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
