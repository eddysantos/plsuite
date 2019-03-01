<?php
/**
 * User: EduardoSantos
 * Date: 2019-02-27
 * Time: 11:27
 */

/*
 * This file will show the user the current location of the tractor associated with their trip.
 * */

function encrypt($string){
 $cipher = "AES-256-CBC";
 $key =hash('sha256', "ewgdhfjjluo3pip4l");
 $iv = substr(hash('sha256', "sdfkljsadf567890saf"), 0, 16);
 $token = openssl_encrypt($string, $cipher, $key, 0, $iv);
 $token = base64_encode($token);

 return $token;
 // $token = openssl_decrypt(base64_decode("UmhaN284bEUxeStZWXF0eTJ3ODhNQT09"),$cipher, $key, 0, $iv);
}

$data = $_GET;

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
    <link rel="stylesheet" href="css/plscope.css">
    <link rel="stylesheet" href="/plsuite/Resources/alertify/css/alertify.min.css">
    <link rel="stylesheet" href="/plsuite/Resources/alertify/css/themes/bootstrap.min.css">
    <script src="/plsuite/Resources/fa_5/js/fontawesome-all.min.js" data-auto-replace-svg="nest" charset="utf-8"></script>
    <title>PL Scope</title>
  </head>
  <body class="h-100 d-flex flex-column">
    <header> <!-- This header appears for the trip information -->
      <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand d-flex" href="#">
          <img src="/plsuite/Resources/images/icono.png" style="max-height: 75px" class="d-inline-block align-top float-left" alt="">
          <div class="ml-4">
            <h5 class="mb-0">
              PL Scope
            </h5>
            <div class="mb-0">
              Automated trip monitoring system
            </div>
            <small class="text-secondary">
              <i>Client Reference: <span id="broker_reference"></span></i>
            </small>
          </div>
        </a>
        <div class="d-flex flex-column">
          <div class="d-flex justify-content-between">
            <span class="mr-2">Client: </span>
            <span id="broker_name"></span>
          </div>
          <div class="d-flex justify-content-between">
            <span class="mr-2">Trip #:</span>
            <span id="lh_number"></span>
          </div>
          <div class="d-flex justify-content-between">
            <span class="mr-2">Truck Number:</span>
            <span id="truck_number"></span>
          </div>
          <div class="d-flex justify-content-between">
            <span class="mr-2">Driver Name:</span>
            <span id="driver_name"></span>
          </div>
        </div>
      </nav>
    </header>
    <div class="container-fluid mt-3 flex-grow-1 mb-3 d-flex flex-column">
      <input type="text" id="linehaul_reference" name="" value="<?php echo $data['lh_reference'] ?>" hidden>
      <div class="d-flex justify-content-between mb-1">
        <div class="">
          <div class="">
            Current Location: <span id="current_location" class="text-secondary"></span>
          </div>
          <div class="">
            Destination: <span class="text-secondary"><span id="dcity"></span>, <span id="dstate"></span> <span id="dzip"></span></span>
          </div>
          <div class="">
            ETA: <span class="text-secondary"> <span id="eta_time"></span> (<span id="eta_date"></span>) </span>
          </div>
        </div>
        <div class="">
          <div class="">
            <small>Last Update: <span class="text-secondary" id="location_last_ping"></span></small>
            <button type="button" class="btn btn-outline-primary btn-sm" name="button" id="refresh_map"><i class="fas fa-sync-alt" role="button"></i></button>
          </div>
          <small class="text-secondary">Map will refresh automatically every 5 minutes.</small>
        </div>
      </div>
      <div class="border rounded flex-grow-1 p-1" id="plscope_map">
        <i class="fas fa-globe-americas"></i> - Waiting for GeoLocation information...
      </div>
    </div>
    <div id="status-message-container" class="h-100 w-100 position-fixed">
      <div class="status-message d-flex justify-content-center">
        <div class="status-message-body align-self-center">
          Loading trip data. Please wait.
        </div>
      </div>
    </div>
  </body>
</html>

<script src="/plsuite/Resources/JQuery/jquery-3.2.1.min.js" charset="utf-8"></script>
<script src="/plsuite/Resources/swal/swal.min.js" charset="utf-8"></script>
<script src="/plsuite/Resources/alertify/alertify.min.js" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="/plsuite/Resources/Bootstrap_4_1_1/js/bootstrap.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuBCFwHZCWMgyeTJ1MI32sXlGnJtIIsUA" async defer></script>
<script src="/plsuite/Resources/gmapslibs/markerclusterer/src/markerclusterer.js" async defer></script>
<script src="/plsuite/Resources/jquery_ui_1_12_1/jquery-ui.min.js" charset="utf-8"></script>
<script src="js/plscope.js?2" charset="utf-8"></script>
