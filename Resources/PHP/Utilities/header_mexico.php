<?php

if (!$_SESSION['user_info']['cred_mexican_portal']) {
  header("location:/plsuite/access_denied.php");
} else {
  $_SESSION['current_portal'] = "mx";
}


 ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="/plsuite/Resources/images/icono.png">


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/plsuite/Resources/Bootstrap_4_3/css/bootstrap.min.css">
    <link rel="stylesheet" href="/plsuite/Resources/alertify/css/alertify.min.css">
    <link rel="stylesheet" href="/plsuite/Resources/alertify/css/themes/default.min.css">
    <link rel="stylesheet" href="/plsuite/Resources/alertify/css/themes/bootstrap.min.css">
    <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/CSS/main.css">
    <link rel="stylesheet" media="screen and (max-device-width: 700px)" href="/plsuite/Resources/CSS/mainMobile.css">
    <script src="/plsuite/Resources/JQuery/jquery-3.2.1.min.js" charset="utf-8"></script>
    <script src="/plsuite/Resources/fa_5/js/fontawesome-all.min.js" data-auto-replace-svg="nest" charset="utf-8"></script>

    <!-- <link href="https://fonts.googleapis.com/css?family=Sansita" rel="stylesheet"> -->
    <title>Prolog Transportation Inc</title>
  </head>
  <header class="">
    <nav class="navbar navbar-expand-lg fixed-top navbar-light bg-light">
      <a class="navbar-brand <?php echo $home_active?>" href="/plsuite/Ubicaciones/">
        <i style="font-size: 160%" class="fa fa-home"></i>
        <!-- <img src="/plsuite/Resources/images/Logo.png" style="max-height: 50px" alt="PLTI"> -->
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="contenidoenblanco">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link custom <?php echo $viajes_active?>" href="/plsuite/mxportal/operaciones">Operaciones</a>
          </li>
          <li class="nav-item">
            <a class="nav-link custom <?php echo $operadores_active?>" href="/plsuite/mxportal/operadores/">Operadores</a>
          </li>
          <li class="nav-item">
            <a class="nav-link custom <?php echo $tractores_active?>" href="/plsuite/mxportal/camiones/">Camiones</a>
          </li>
          <li class="nav-item">
            <a class="nav-link custom <?php echo $cajas_active?>" href="#">Cajas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link custom <?php echo $clientes_active?>" href="/plsuite/mxportal/clientes/">Clientes</a>
          </li>
          <!--li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Operadores
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#">Lista Operadores</a>
              <a class="dropdown-item" href="#">Ver Viajes</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Agregar Viaje Nuevo</a>
            </div>
          </li> -->
        </ul>
        <ul class="navbar-nav">
          <!-- <li class="nav-item">
            <a href="/plsuite/Ubicaciones/Reports" class="nav-link">Reports</a>
          </li> -->
          <li class="nav-item dropdown mr-3">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Opciones
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <!-- <a class="dropdown-item" href="/plsuite/Ubicaciones/Reports">Reports</a> -->
              <div class="dropdown-divider"></div>
              <?php if ($_SESSION['user_info']['cred_is_admin']): ?>
                <a class="dropdown-item" href="/plsuite/Ubicaciones/Users">Usuarios</a>
              <?php endif; ?>
              <?php if ($_SESSION['user_info']['cred_american_portal']): ?>
                <a class="dropdown-item" href="/plsuite/Ubicaciones">Portal Americano</a>
              <?php endif; ?>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="modal" data-target="#signOutModal" role="button">Cerrar Sesión</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
</html>
