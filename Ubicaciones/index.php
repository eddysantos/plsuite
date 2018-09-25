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
      </div>
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
