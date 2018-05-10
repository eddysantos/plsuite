<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';
require $root . '/plsuite/Resources/PHP/Utilities/header.php';
 ?>
<div class="container-fluid pt-3" style="margin-top: 75px;">
 <div class="row">
    <div class="col-lg-5">
      <h5>Open Trips:<span id="amt-open-trips"></span></h5>
      <table class="table table-striped main-dash-table">
        <tbody id="tbody-open-trips">
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
    <!-- <div class="col-lg-4">
      <h5>Pending Invoce: <span id="amt-pending-invoice"></span></h5>
    </div> -->
 </div>


</div>

<?php
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>

<script src="zjs/index.js" charset="utf-8"></script>
