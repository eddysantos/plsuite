<!DOCTYPE html>
<body>

<footer class="d-flex justify-content-between align-items-center">
  <div class="">
    Welcome <?php echo $_SESSION['user_info']['Nombre']?>!
  </div>
  <div class="">
    <button type="button" class="btn btn-outline-primary" id="toggleFleetMap" data-custom-toggle="modal" name="button">
      <i class="fas fa-map-marked-alt"></i>
    </button>
    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#rpmCalculator" name="button">
      <i class="fa fa-calculator"></i>
    </button>
    <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#bugReportModal" role="button" name="button">
      <i class="fa fa-bug"></i>
    </button>
  </div>
</footer>

<?php

require $root . "/plsuite/Resources/PHP/modales/confirmationModal.php";
require $root . "/plsuite/Resources/PHP/modales/fleetViewMap.php";
require $root . "/plsuite/Resources/PHP/modales/rpmCalculator.php";
require $root . "/plsuite/Resources/PHP/modales/modalCerrarSesion.php";
require $root . "/plsuite/Resources/PHP/modales/bugReport.php";

 ?>

  <script src="/plsuite/Resources/JQuery/jquery-3.2.1.min.js" charset="utf-8"></script>
  <script src="/plsuite/Resources/swal/swal.min.js" charset="utf-8"></script>
  <script src="/plsuite/Resources/alertify/alertify.min.js" charset="utf-8"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
  <script src="/plsuite/Resources/Bootstrap_4_1_1/js/bootstrap.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuBCFwHZCWMgyeTJ1MI32sXlGnJtIIsUA&libraries=places"></script>
  <script src="/plsuite/Resources/gmapslibs/markerclusterer/src/markerclusterer.js" async defer></script>
  <script src="/plsuite/Resources/JS/main.js"></script>
  <script src="/plsuite/Resources/JS/bugs.js"></script>
</body>
