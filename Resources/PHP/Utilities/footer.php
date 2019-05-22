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
  </div>
</footer>

<?php

require $root . "/plsuite/Resources/PHP/modales/confirmationModal.php";
require $root . "/plsuite/Resources/PHP/modales/fleetViewMap.php";
require $root . "/plsuite/Resources/PHP/modales/rpmCalculator.php";
require $root . "/plsuite/Resources/PHP/modales/modalCerrarSesion.php";

 ?>

  <script src="/plsuite/Resources/JQuery/jquery-3.2.1.min.js" charset="utf-8"></script>
  <script src="/plsuite/Resources/swal/swal.min.js" charset="utf-8"></script>
  <script src="/plsuite/Resources/alertify/alertify.min.js" charset="utf-8"></script>
  <!--script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
  <!--script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script-->
  <script src="/plsuite/Resources/Bootstrap_4_3/js/bootstrap.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuBCFwHZCWMgyeTJ1MI32sXlGnJtIIsUA" async defer></script>
  <script src="/plsuite/Resources/gmapslibs/markerclusterer/src/markerclusterer.js" async defer></script>
  <script src="/plsuite/Resources/JS/main.js"></script>
</body>
