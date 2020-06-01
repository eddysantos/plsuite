<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$query = "SELECT * FROM mx_clients WHERE pk_mx_client = ?";
$id = $_GET['client_id'];

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
}

$stmt->bind_param('s', $id);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
}

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
}

$rslt = $stmt->get_result();

if ($rslt->num_rows == 0) {
  $system_callback['code'] = 2;
  $system_callback['message'] = "Script called successfully but there are no rows to display.";
}

$row = $rslt->fetch_assoc();

 ?>

 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="/plsuite/Resources/Bootstrap_4_1_1/css/bootstrap.min.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/CSS/main.css">
     <link rel="stylesheet" href="/plsuite/Resources/alertify/css/alertify.min.css">
     <link rel="stylesheet" href="/plsuite/Resources/alertify/css/themes/default.min.css">
     <link rel="stylesheet" href="/plsuite/Resources/alertify/css/themes/bootstrap.min.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/CSS/no_header.css">
     <link href="https://fonts.googleapis.com/css?family=Sansita" rel="stylesheet">
     <script src="/plsuite/Resources/fa_5/js/fontawesome-all.min.js" data-auto-replace-svg="nest" charset="utf-8"></script>
     <title>Prolog Transportation Inc</title>
   </head>
  <body style="min-height:100%">

   <header>
     <div class="custom-header">
       <div class="custom-header-bar">&nbsp;</div>
       <div>
         <a class="ml-3 mr-5" role="button" href="index.php"> <i class="fa fa-chevron-left"></i></a>
         <input type="text" class="editable-input" name="" value="<?php echo $row['client_alias'] ?>" disabled>
         <i class="far fa-edit mx-3 edit-input-btn" role="button"></i>
       </div>
     </div>
   </header>

   <div class="container-fluid pt-5 px-5">
     <div class="row">
       <div class="col-md-6">
         <h5 class="section-header d-flex justify-content-between pb-1">
           Datos Generales
         </h5>
         <div class="mb-1">
           <button type="button" class="btn btn-outline-primary" name="button">
             <i class="far fa-edit edit-client-info" role="button"></i>
           </button>
         </div>
         <form class="uneditable-form" id="client-general-info">
           <input type="text" name="" id="pk_mx_client" value="<?php echo $row['pk_mx_client'] ?>" hidden>
           <div class="form-group">
             <label aria-labels="cliente_razonsocial">Razón Social</label>
             <input type="text" class="form-control" id="client_razonsocial" value="<?php echo $row['client_name'] ?>" required>
           </div>
           <div class="form-group">
             <label aria-labels="cliente_rfc">RFC</label>
             <input type="text" class="form-control" id="client_rfc" value="<?php echo $row['tax_id'] ?>" required>
           </div>
           <div class="form-group">
             <label aria-labels="">Dirección</label>
             <div class="form-inline align-items-baseline mb-2">
               <input type="text" class="form-control flex-grow-1" id="client_street_name" placeholder="Calle" name="" value="<?php echo $row['address_street'] ?>" autocomplete="new-password" required>
               <div class="pl-5">
                 <input type="text" class="form-control d-block mb-1" id="client_street_ext_number" placeholder="Numero Interior" name="" value="<?php echo $row['address_ext_number'] ?>" autocomplete="new-password" required>
                 <input type="text" class="form-control d-block" id="client_street_int_number" placeholder="Numero Exterior" name="" value="<?php echo $row['address_int_number'] ?>" autocomplete="new-password">
               </div>
             </div>
             <div class="form-group">
               <input type="text" class="form-control" id="client_locality" placeholder="Colonia" name="" value="<?php echo $row['address_locality'] ?>" required>
             </div>
             <div class="form-inline justify-content-between">
               <input type="text" class="form-control" id="client_city" placeholder="Ciudad" name="" value="<?php echo $row['address_city'] ?>" autocomplete="new-password" required>
               <input type="text" class="form-control" id="client_state" placeholder="Estado" name="" value="<?php echo $row['address_state'] ?>" autocomplete="new-password" required>
               <input type="text" class="form-control" id="client_zip_code" placeholder="Codigo Postal" name="" value="<?php echo $row['address_zip_code'] ?>" autocomplete="new-password" required>
             </div>
           </div>
         </form>

       </div>
       <div class="col-md-6">
         <h5 class="section-header d-flex justify-content-between pb-1">
           Destinos
         </h5>
         <div class="mb-1">
           <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#agregarDestino_modal" name="button">Agregar Destino</button>
         </div>
         <table class="table table-striped">
           <tbody id="table_mx_places"></tbody>
         </table>
       </div>
     </div>
   </div>


  </body>
 </html>

<?php
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
require 'modales/new_place.php';
 ?>
 <script src="js/detalle_clientes.js" charset="utf-8"></script>
