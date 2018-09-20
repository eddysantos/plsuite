<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/plsuite/Resources/Bootstrap_4_1_1/css/bootstrap.min.css">
    <link rel="stylesheet" href="/plsuite/Resources/alertify/css/alertify.min.css">
    <link rel="stylesheet" href="/plsuite/Resources/alertify/css/themes/default.min.css">
    <link rel="stylesheet" href="/plsuite/Resources/alertify/css/themes/bootsrap.min.css">
    <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/CSS/main.css">
    <!-- <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/fontAwesome/css/font-awesome.min.css"> -->
    <link rel="stylesheet" media="screen and (max-device-width: 700px)" href="/plsuite/Resources/CSS/mainMobile.css">
    <script src="/plsuite/Resources/JQuery/jquery-3.2.1.min.js" charset="utf-8"></script>
    <!-- <script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script> -->
    <script src="/plsuite/Resources/fa_5/js/fontawesome-all.js" charset="utf-8"></script>

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
            <a class="nav-link custom <?php echo $dash_active?>" href="/plsuite/Ubicaciones/dashboard.php">Dashboard</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link custom dropdown-toggle <?php echo $viajes_active?>" href="#" id="tripDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded='false'>Trips</a>
            <div class="dropdown-menu" aria-lablledby="tripDropdown">
              <a class="dropdown-item" href="/plsuite/Ubicaciones/Viajes/dashboard.php">Active Trips</a>
              <a class="dropdown-item" href="/plsuite/Ubicaciones/Viajes/tripSearch.php" href="#">Trip Search</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="/plsuite/Ubicaciones/Viajes/invoice_control" href="">Invoice Control</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link custom <?php echo $operadores_active?>" href="/plsuite/Ubicaciones/Drivers/dashboard.php">Drivers</a>
          </li>
          <li class="nav-item">
            <a class="nav-link custom <?php echo $tractores_active?>" href="/plsuite/Ubicaciones/Trucks/dashboard.php">Trucks</a>
          </li>
          <li class="nav-item">
            <a class="nav-link custom <?php echo $cajas_active?>" href="/plsuite/Ubicaciones/Trailers/dashboard.php">Trailers</a>
          </li>
          <li class="nav-item">
            <a class="nav-link custom <?php echo $brokers_active?>" href="/plsuite/Ubicaciones/Brokers/dashboard.php">Brokers</a>
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
          <li class="nav-item">
            <a href="/plsuite/Ubicaciones/Reports" class="nav-link">Reports</a>
          </li>
          <li class="nav-item dropdown mr-3">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Options
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" disabled href="/plsuite/Ubicaciones/Config">Configuration</a>
              <a class="dropdown-item disabled" disabled href="#">Users</a>
              <!-- <a class="dropdown-item" href="/plsuite/Ubicaciones/Reports">Reports</a> -->
              <div class="dropdown-divider"></div>
              <a class="dropdown-item disabled" href="" disabled>Mexican Portal</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="modal" data-target="#signOutModal" role="button">Sign Out</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
</html>
