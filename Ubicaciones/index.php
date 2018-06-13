<link rel="stylesheet" href="/plsuite/Resources/jquery_ui_1_12_1/jquery-ui.min.css">
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
        <input type="text" style="padding: 0; line-height: 0" readonly class="form-control-plaintext text-secondary" role="button" name="" value="<?php echo $today?>" id="dash-date">
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
<script src="zjs/index.js" charset="utf-8"></script>
