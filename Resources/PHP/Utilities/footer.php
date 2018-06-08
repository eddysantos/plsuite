<!DOCTYPE html>
<body>

<footer class="d-flex justify-content-between align-items-center">
  <div class="">
    Welcome <?php echo $_SESSION['user_info']['Nombre']?>!
  </div>
  <div class="">
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
require $root . "/plsuite/Resources/PHP/modales/rpmCalculator.php";
require $root . "/plsuite/Resources/PHP/modales/modalCerrarSesion.php";
require $root . "/plsuite/Resources/PHP/modales/bugReport.php";

 ?>

  <script src="/plsuite/Resources/JQuery/jquery-3.2.1.min.js" charset="utf-8"></script>
  <script src="/plsuite/Resources/swal/swal.min.js" charset="utf-8"></script>
  <script src="/plsuite/Resources/alertify/alertify.min.js" charset="utf-8"></script>
  <!--script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
  <!--script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script-->
  <script src="/plsuite/Resources/Bootstrap4/js/bootstrap.min.js"></script>
  <script src="/plsuite/Resources/JS/main.js"></script>
  <script src="/plsuite/Resources/JS/bugs.js"></script>
</body>
