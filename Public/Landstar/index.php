<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';
require $root . '/plsuite/Resources/vendor/autoload.php';

$today = date("Y-m-d");
$sevenDays = date("Y-m-d", strtotime("today -7 days"));

function encrypt($string){
 $cipher = "AES-256-CBC";
 $key =hash('sha256', "ewgdhfjjluo3pip4l");
 $iv = substr(hash('sha256', "sdfkljsadf567890saf"), 0, 16);
 $token = openssl_encrypt($string, $cipher, $key, 0, $iv);
 $token = base64_encode($token);

 return $token;
 // $token = openssl_decrypt(base64_decode("UmhaN284bEUxeStZWXF0eTJ3ODhNQT09"),$cipher, $key, 0, $iv);
}

$tripHandle = new Trip();

$pos = $tripHandle->getOpenPOs();

if (!$pos) {
  $error = $tripHandle->last_error;
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" href="/plsuite/Resources/images/icono.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/plsuite/Resources/Bootstrap_4_1_1/css/bootstrap.min.css">
    <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/CSS/main.css">
    <link rel="stylesheet" href="/plsuite/Resources/alertify/css/alertify.min.css">
    <link rel="stylesheet" href="/plsuite/Resources/alertify/css/themes/bootstrap.min.css">
    <script src="/plsuite/Resources/fa_5/js/fontawesome-all.min.js" data-auto-replace-svg="nest" charset="utf-8"></script>
    <title>Landstar Trips</title>
  </head>
  <body class="h-100 d-flex flex-column">
    <header> <!-- This header appears for the trip information -->
      <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand d-flex" href="#">
          <img src="/plsuite/Resources/images/icono.png" style="max-height: 35px" class="d-inline-block align-top float-left" alt="">
          <div class="ml-4 align-self-center">
            <h5 class="mb-0">
              Landstar Trip Tracker
            </h5>
          </div>
        </a>
      </nav>
    </header>
    <div class="d-flex container-fluid justify-content-between my-2 align-content-center">
      <div class="btn-group btn-group-sm" id="status-filter-btns" role="group" aria-label="Trip Status">
        <button type="button" class="btn btn-outline-dark active" data-status-filter="Open">Open</button>
        <button type="button" class="btn btn-outline-dark" data-status-filter="Closed">Closed</button>
        <button type="button" class="btn btn-outline-dark" data-status-filter="All">All</button>
      </div>
      <div class="input-group input-group-sm w-auto">
        <div class="input-group-prepend" id="date_selection">
          <button class="btn btn-outline-dark active" type="button" data-date-filter="week">7 Days</button>
          <button class="btn btn-outline-dark" type="button" data-date-filter="month">Month</button>
        </div>
        <input type="date" class="form-control border-dark" id="date-filter-from" placeholder="" value="<?php echo $sevenDays ?>" aria-label="date-from">
        <input type="date" class="form-control border-dark" id="date-filter-to" placeholder="" value="<?php echo $today ?>" aria-label="date-to">
      </div>
      <div class="d-flex">
        <div class="input-group input-group-sm mx-2">
          <input type="text" class="form-control border-dark form-control-sm h-100 table-filter" data-target-table="#POTable" placeholder="" aria-label="search field" aria-describedby="basic-addon2">
          <div class="input-group-append">
            <span class="input-group-text border-dark" id="basic-addon2"><i class="fas fa-filter"></i></span>
          </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-dark" data-toggle="modal" data-target="#addPoNumbersModal" name="button">Add POs</button>
      </div>
    </div>
    <div class="container-fluid flex-grow-1 mb-3 d-flex flex-column">
      <table class="table table-striped table-sm">
        <thead>
          <tr>
            <th>PO</th>
            <th>Appointment</th>
            <th>Trip Number</th>
            <th>Trailer</th>
            <th>Tractor</th>
            <th>Driver</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="POTable">
          <?php if ($pos): ?>
            <?php foreach ($pos as $key => $po): ?>
              <tr>
                <td><?php echo $po['po_number'] ?></td>
                <td>
                  <?php echo date("m/d H:i", strtotime($po['po_pickup_date'] . " " . $po['po_pickup_time'])) ?>
                </td>
                <td><?php echo $po['lhNumber'] ?></td>
                <td><?php echo $po['trailer'] ?></td>
                <td><?php echo $po['tractor'] ?></td>
                <td><?php echo $po['driver'] ?></td>
                <td><?php echo $po['status'] ?></td>
                <td>
                  <?php if ($po['idLinehaul']): ?>
                    <a href="/plsuite/public/PlScope/plscope.php?lh_reference=<?php echo encrypt($po['idLinehaul']) ?>" target="_blank" class="btn btn-outline-dark btn-sm"><i class="fas fa-map-marked-alt"></i></a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <td colspan="7"><?php echo $tripHandle->last_error ?></td>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </body>
</html>

<?php require 'modal/addPOs.php' ?>

<script src="/plsuite/Resources/JQuery/jquery-3.2.1.min.js" charset="utf-8"></script>
<script src="/plsuite/Resources/swal/swal.min.js" charset="utf-8"></script>
<script src="/plsuite/Resources/alertify/alertify.min.js" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="/plsuite/Resources/Bootstrap_4_1_1/js/bootstrap.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuBCFwHZCWMgyeTJ1MI32sXlGnJtIIsUA" defer></script>
<script src="/plsuite/Resources/gmapslibs/markerclusterer/src/markerclusterer.js" defer></script>
<script src="/plsuite/Resources/jquery_ui_1_12_1/jquery-ui.min.js" charset="utf-8"></script>

<script src="js/cna.js" charset="utf-8"></script>
