<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

require $root . '/plsuite/mxportal/resources/php/trailer_list.php';

$data['id'] =  $_GET['id'];
$remolques = trailer_list(); //Obtained from trailer_list.php

/**Fetch information on the trip**/
$query = "SELECT trip_status FROM mx_trips WHERE pk_mx_trip = ?";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('s', $data['id']);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();

if ($rslt->num_rows == 0) {
  $system_callback['code'] = 2;
  $system_callback['message'] = "No se pudieron encontrar los datos del viaje. Porfavor notifique a sistemas.";
  exit_script($system_callback);
}

$trip = $rslt->fetch_assoc();


function parseDate($datestamp){
  $return = array(
    'date'=>"",
    'time'=>array(
      'hour'=>"",
      'minute'=>""
    )
  );

  if ($datestamp == "") {
    return $return;
  }

  $return['date'] = date('Y-m-d', strtotime($datestamp));
  $return['time']['hour'] = date('H', strtotime($datestamp));
  $return['time']['minute'] = date('i', strtotime($datestamp));

  return $return;
}
function encrypt($string){
  $cipher = "AES-256-CBC";
  $key =hash('sha256', "ewgdhfjjluo3pip4l");
  $iv = substr(hash('sha256', "sdfkljsadf567890saf"), 0, 16);
  $token = openssl_encrypt($string, $cipher, $key, 0, $iv);
  $token = base64_encode($token);

  return $token;
  // $token = openssl_decrypt(base64_decode("UmhaN284bEUxeStZWXF0eTJ3ODhNQT09"),$cipher, $key, 0, $iv);
}

if ($trip['trip_status'] == "Abierto") {
  $close_button = "
  <div class=''>
    <button type='button' class='btn btn-warning' id='close_trip' name='button' disabled>Cerrar Viaje</button>
  </div>";
  $add_mov_button = "<div class='d-flex justify-content-end mb-2'>
    <button type='button' class='btn btn-outline-primary' data-toggle='modal' data-target='#agregarMovimiento_modal' name='button'>Agregar</button>
  </div>";
}

 ?>

 <!DOCTYPE html>
 <html class="h-100">
   <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <link rel="icon" href="/plsuite/Resources/images/icono.png">

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="/plsuite/Resources/Bootstrap_4_3/css/bootstrap.min.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/CSS/main.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/CSS/trips.css">
     <link rel="stylesheet" href="/plsuite/Resources/alertify/css/alertify.min.css">
     <link rel="stylesheet" href="/plsuite/Resources/alertify/css/themes/bootstrap.min.css">
     <script src="/plsuite/Resources/fa_5/js/fontawesome-all.min.js" data-auto-replace-svg="nest" charset="utf-8"></script>
     <link rel="stylesheet" media="screen and (max-device-width: 700px)" href="/plsuite/Resources/CSS/mainMobile.css">
     <title>Prolog Transportation Inc</title>
   </head>
  <body class="h-100">
  <input type="text" name="" id="mx_trip" value="<?php echo $data['id'] ?>" hidden>

   <div class="" id="trip-information" >
    <header id="trip-header"> <!-- This header appears for the trip information -->
       <div class="custom-header">
         <div class="custom-header-bar">&nbsp;</div>
         <div class="">
           <a class="ml-3 mr-5" role="button" id="backToDash" href="javascript:history.back()"><i class="fa fa-chevron-left"></i></a>
           <div class="w-100 d-flex align-items-center mr-3 justify-content-between">
             <div class="pr-5">
               <span class="trip-status" id="tripStatus">Aqui va el status</span>
             </div>
             <?php echo $close_button ?>
           </div>
         </div>
       </div>
    </header>
    <div class="container-fluid pt-0" style="height: calc(100vh - 110px)" id="trip-summary">
     <div class="row h-100">
       <div class="col-lg-3 border-right">
         <div class="row">
           <div class="col-lg-4">
             <p class="text-dark mb-0" >Operador:</p>
           </div>
           <div class="col-lg-6">
             <p id="" class="mb-0 text-secondary"><span id="firstName"></span> <span id=lastName></span></p>
           </div>
         </div>
         <div class="row">
           <div class="col-lg-4">
             <p class="text-dark mb-0">Tractor:</p>
           </div>
           <div class="col-lg-6 text-secondary">
             <p class="mb-0" id="tractorNumber"></p>
           </div>
         </div>
         <div class="row">
           <div class="col-lg-4">
             <p class="text-dark">Cliente:</p>
           </div>
           <div class="col-lg-6 text-secondary">
             <p id="pk_mx_client" hidden></p>
             <p class="" id="clientName"></p>
           </div>
         </div>
         <div class="row">
           <div class="col-lg-4">
             <p class="text-dark mb-0">Abierto:</p>
           </div>
           <div class="col-lg-6 text-secondary">
             <p class="mb-0" id="dateCreated"></p>
           </div>
         </div>
         <div class="row">
           <div class="col-lg-4">
             <p class="text-dark">Cerrado:</p>
           </div>
           <div class="col-lg-6 text-secondary">
             <p class="mb-0" id="dateClosed"></p>
           </div>
         </div>
         <div class="row mt-5 no-gutters">
           <div class="col-lg-12" style="">
             <ul class="nav flex-column" id="trip_Options" role="tablist" style="margin-right: -15px; margin-left: -15px">
               <li class="nav-item border-top">
                 <a class="nav-link active" id="movimietos-tab" data-toggle="tab" href="#movimientos-pane" role="tab" aria-controls="movimientos" aria-selected="true">Movimientos</a>
               </li>
               <li class="nav-item border-top">
                 <a class="nav-link disabled" id="movimietos-tab" data-toggle="tab" href="#gastos-pane" role="tab" aria-controls="gastos" aria-selected="false" aria-disabled="true">Gastos</a>
               </li>
             </ul>

           </div>
         </div>

       </div>
       <div class="col-lg-9">
         <div class="tab-content" id="tripTabsContent">
           <div class="tab-pane fade show active pt-2" id="movimientos-pane" role="tabpanel" aria-labelledby="movimientos-tab">
             <?php echo $add_mov_button ?>
             <table class="table table-striped" >
               <tbody id="opsDetails_movs"></tbody>
             </table>
           </div>
           <div class="tab-pane fade" id="gastos-pane" role="tabpanel" aria-labelledby="gastos-tab">
             TAB DE GASTOS ACTIVA
           </div>
         </div>
       </div>
     </div>
   </div>
  </div>




  </body>
 </html>
<?php
require 'modales/editar_movimiento.php';
require 'modales/agregar_movimiento.php';
require 'modales/doc_viewer.php';
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>
 <script src="js/ops_details.js" charset="utf-8"></script>
 <script src="/plsuite/Resources/jquery_ui_1_12_1/jquery-ui.min.js" charset="utf-8"></script>
