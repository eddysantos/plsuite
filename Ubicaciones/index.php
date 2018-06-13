<link rel="stylesheet" href="/plsuite/Resources/jquery_ui_1_12_1/jquery-ui.min.css">
<link rel="stylesheet" href="/plsuite/Resources/c3_charts/c3.min.css">
<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';
require $root . '/plsuite/Resources/PHP/Utilities/header.php';

$today = date('m/d/Y', strtotime('today'));
 ?>
<div class="container-fluid pt-3" style="margin-top: 75px;">

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
  <div class="border p-2">
    <form class="form-inline" onsubmit="return false;">
      Select date range: <input type="text" class="date-selector ml-1 mr-1 form-control form-control-sm text-center" id="ts_chart_date_from" name="" value="">
      - <input type="text" class="date-selector ml-1 mr-1 form-control form-control-sm" id="ts_chart_date_to" name="" value="">
      And periodicity:
      <select class="form-control form-control-sm ml-1 mr-5" id="ts_chart_period" name="">
        <option value="0">Daily</option>
        <option value="1">Weekly</option>
        <option value="2">Monthly</option>
      </select>
      <button type="button" class="btn btn-outline-success btn-sm" id="load_trip_summary_chart" name="button">Load</button>
    </form>
    <div class="" id="test_chart"></div>
  </div>
 <!-- <div class="row">
    <div class="col-lg-5">
      <h5>Open Trips: <span id="amt-open-trips"></span></h5>
      <table class="table table-striped main-dash-table">
        <tbody id="tbody-open-trips" class="border">
          <tr><td class="text-center">Loading <i class="fas fa-circle-notch fa-spin ml-2"></i></td></tr>
        </tbody>
      </table>
    </div>
    <div class="col-lg-5 offset-lg-2">
      <h5>Pending Invoice:<span id="amt-closed-trips"></span></h5>
      <table class="table table-striped main-dash-table">
        <tbody id="tbody-closed-trips">
          <tr><td class="text-center">Loading <i class="fas fa-circle-notch fa-spin ml-2"></i></td></tr>
        </tbody>
      </table>
    </div>
 </div> -->


</div>

<?php
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>

<script src="/plsuite/Resources/jquery_ui_1_12_1/jquery-ui.min.js" charset="utf-8"></script>
<script src="/plsuite/Resources/c3_charts/c3.min.js" charset="utf-8"></script>
<script src="/plsuite/Resources/c3_charts/d3.v5.min.js" charset="utf-8"></script>
<script src="zjs/index.js" charset="utf-8"></script>
