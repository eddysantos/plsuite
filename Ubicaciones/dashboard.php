<link rel="stylesheet" href="/plsuite/Resources/jquery_ui_1_12_1/jquery-ui.min.css">
<link rel="stylesheet" href="/plsuite/Resources/c3_charts/c3.min.css">
<link rel="stylesheet" href="/plsuite/Resources/CSS/main_dash.css">
<?php
$dash_active = "active";
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
<div class="container-fluid pt-3" style="margin-top: 75px; overflow: scroll; height: calc(100vh - 140px)">

  <div class="border p-2 mb-2">
    <h6>Truck Mileage Summary</h6>
    <form class="form-inline justify-content-between" onsubmit="return false;">
      <div class="date-inputs">
        Select date range: <input type="text" class="date-selector ml-1 mr-1 form-control form-control-sm text-center" id="tms_chart_date_from" name="" value="<?php echo $sunday?>">
        - <input type="text" class="date-selector ml-1 mr-1 form-control text-center form-control-sm" id="tms_chart_date_to" name="" value="<?php echo $today?>">
      </div>
      <button type="button" class="btn btn-outline-success btn-sm float-right" id="load_tms_chart" name="button">Load</button>
    </form>
    <div class="" id="tms-summary-chart"></div>
  </div>
  <div class="d-flex justify-content-between mb-2">
    <table class="table border">
      <thead>
        <th style="width: 140px" class="text-secondary">
          <input type="text" style="padding: 0; line-height: 0" readonly class="form-control-plaintext date-selector text-secondary" role="button" name="" value="<?php echo $today?>" id="dash-date">
        </th>
        <th scope="col">Total Trips</th>
        <th scope="col">Southbound Trips</th>
      </thead>
      <tbody>
        <tr>
          <th scope="row">Total Miles</th>
          <td id="tt_miles"></td>
          <td id="sb_miles"></td>
        </tr>
        <tr>
          <th scope="row">Total Rate</th>
          <td id="tt_rate"></td>
          <td id="sb_rate"></td>
        </tr>
        <tr>
          <th scope="row">RPM</th>
          <td id="tt_rpm"></td>
          <td id="sb_rpm"></td>
        </tr>
      </tbody>
    </table>
    <!-- <div class="border p-2 w-50 ml-1">
      <form class="form-inline justify-content-between" onsubmit="return false;">
        <div class="date-inputs">
          Select date range: <input type="text" class="date-selector ml-1 mr-1 form-control form-control-sm text-center" id="tr_chart_date_from" name="" value="<?php echo $sunday?>">
          - <input type="text" class="date-selector ml-1 mr-1 form-control text-center form-control-sm" id="tr_chart_date_to" name="" value="<?php echo $today?>">
        </div>
        <button type="button" class="btn btn-outline-success btn-sm float-right" id="load_tr_chart" name="button">Load</button>
      </form>
    </div> -->

  </div>
  <div class="border p-2 mb-2">
    <h6>RPM Summary</h6>
    <form class="form-inline justify-content-between" onsubmit="return false;">
      <div class="date-inputs">
        Select date range: <input type="text" class="date-selector ml-1 mr-1 form-control form-control-sm text-center" id="ts_chart_date_from" name="" value="<?php echo $sunday_5weeks?>">
        - <input type="text" class="date-selector ml-1 mr-1 form-control text-center form-control-sm" id="ts_chart_date_to" name="" value="<?php echo $today?>">
        And cycle:
        <select class="form-control form-control-sm ml-1 mr-5" id="ts_chart_period" name="">
          <option value="0">Daily</option>
          <option value="1" selected>Weekly</option>
          <option value="2">Monthly</option>
        </select>
      </div>
      <button type="button" class="btn btn-outline-success btn-sm" id="load_trip_summary_chart" name="button">Load</button>
    </form>
    <div class="" id="rpm-summary-chart"></div>
  </div>
  <div class="border p-2 mb-2">
    <div class="">
      <h6>Miles Summary</h6>
    </div>
    <div class="d-flex justify-content-between">
      <form class="form-inline " onsubmit="return false;">
        Select date range: <input type="text" class="date-selector ml-1 mr-1 form-control form-control-sm text-center" id="ms_chart_date_from" name="" value="<?php echo $sunday_5weeks?>">
        - <input type="text" class="date-selector ml-1 mr-1 form-control text-center form-control-sm" id="ms_chart_date_to" name="" value="<?php echo $today?>">
        And cycle:
        <select class="form-control form-control-sm ml-1 mr-5" id="ms_chart_period" name="">
          <option value="0">Daily</option>
          <option value="1" selected>Weekly</option>
          <option value="2">Monthly</option>
        </select>
      </form>
      <div class="">
        <div class="btn-group" style="z-index: 99999">
          <button type="button" class="btn btn-outline-success btn-sm dropdown-toggle float-right" data-toggle="dropdown">
            Add Graph Item <span class="caret"></span>
          </button>
          <ul class="dropdown-menu miles-summary" style="width: 200%">
            <form>
              <div class="form-group">
                <select class="form-control" id="dash-cat-select" name="">
                  <option value="">Select category</option>
                  <option value="driver">Driver</option>
                  <option value="truck" selected>Truck</option>
                  <option value="trailer">Trailer</option>
                  <option value="broker">Broker</option>
                </select>
              </div>
              <div class="form-group" id="dash-cat-input" style="display: none">
                <input type="text" class="form-control popup-input" id-display="#display-popup" category="truck" autocomplete="new-password" aria-describedy="#assist-ms-chart-loader">
                <div id="display-popup" class="popup-list mt-3" style="display: none; z-index: 9999"></div>
                <small id="assist-ms-chart-loader" class="form-text text-muted">Leave blank to graph the entire category.</small>
                <button type="button" class="btn btn-outline-success btn-sm mt-2 float-right" id="ms-add-graph-item" name="button">Add</button>
              </div>
            </form>
          </ul>
        </div>
        <button type="button" class="btn btn-outline-secondary btn-sm reset-chart" target="ms_chart" name="button">Reset</button>
      </div>
    </div>
    <div class="" id="miles-summary-chart" style="display: none"></div>
  </div>
  <div class="border p-2 mb-2">
    <div class="">
      <h6>Sales Summary</h6>
    </div>
    <div class="d-flex justify-content-between">
      <form class="form-inline " onsubmit="return false;">
        Select date range: <input type="text" class="date-selector ml-1 mr-1 form-control form-control-sm text-center" id="ss_chart_date_from" name="" value="<?php echo $sunday_5weeks?>">
        - <input type="text" class="date-selector ml-1 mr-1 form-control text-center form-control-sm" id="ss_chart_date_to" name="" value="<?php echo $today?>">
        And cycle:
        <select class="form-control form-control-sm ml-1 mr-5" id="ss_chart_period" name="">
          <option value="0">Daily</option>
          <option value="1" selected>Weekly</option>
          <option value="2">Monthly</option>
        </select>
      </form>
      <div class="">
        <div class="btn-group" style="z-index: 99999">
          <button type="button" class="btn btn-outline-success btn-sm dropdown-toggle float-right" data-toggle="dropdown">
            Add Graph Item <span class="caret"></span>
          </button>
          <ul class="dropdown-menu sales-summary" style="width: 200%">
            <form>
              <div class="form-group">
                <select class="form-control" id="dash-cat-select-ss" name="">
                  <option value="">Select category</option>
                  <option value="driver">Driver</option>
                  <option value="truck" selected>Truck</option>
                  <option value="trailer">Trailer</option>
                  <option value="broker">Broker</option>
                </select>
              </div>
              <div class="form-group" id="dash-cat-input-ss" style="display: none">
                <input type="text" class="form-control popup-input" id-display="#display-popup" category="truck" autocomplete="new-password" aria-describedy="#assist-ss-chart-loader">
                <div id="display-popup" class="popup-list mt-3" style="display: none; z-index: 9999"></div>
                <small id="assist-ss-chart-loader" class="form-text text-muted">Leave blank to graph the entire category.</small>
                <button type="button" class="btn btn-outline-success btn-sm mt-2 float-right" id="ss-add-graph-item" name="button">Add</button>
              </div>
            </form>
          </ul>
        </div>
        <button type="button" class="btn btn-outline-secondary btn-sm reset-chart" target="ss_chart" name="button">Reset</button>
      </div>
    </div>
    <div class="" id="sales-summary-chart" style="display: none"></div>
  </div>


</div>

<?php
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>

<script src="/plsuite/Resources/jquery_ui_1_12_1/jquery-ui.min.js" charset="utf-8"></script>
<script src="/plsuite/Resources/c3_charts/c3.min.js" charset="utf-8"></script>
<script src="/plsuite/Resources/c3_charts/d3.v5.min.js" charset="utf-8"></script>
<script src="zjs/main_dash.js" charset="utf-8"></script>
