<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';
// require $root . '/plsuite/Resources/PHP/Utilities/header.php';


 ?>

 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="/plsuite/Resources/Bootstrap4/css/bootstrap.min.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/CSS/main.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/CSS/trips.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/fontAwesome/css/font-awesome.min.css">
     <link rel="stylesheet" media="screen and (max-device-width: 700px)" href="/plsuite/Resources/CSS/mainMobile.css">
     <link href="https://fonts.googleapis.com/css?family=Sansita" rel="stylesheet">
     <title>Prolog Transportation Inc</title>
   </head>
  <body style="min-height:100%">

   <header>
     <div class="custom-header">
       <div class="custom-header-bar">&nbsp;</div>
       <div class="">
         <a class="ml-3 mr-5" role="button" href="/plsuite/Ubicaciones/viajes/dashboard.php"><i class="fa fa-chevron-left"></i></a>
         <div class="w-100 d-flex align-items-center justify-content-between">
           <div class="pr-5">
             Reports
           </div>
         </div>
       </div>
     </div>
   </header>

   <div class="container-fluid pt-3">
     <div class="row">
       <div class="col-lg-3">
         <div class="card">
           <div class="card-body">
             <h4 class="card-title">PL Trips</h4>
             <p>This report will show the closed linehauls on the given time period.</p>
             <button type="button" class="btn btn-info tog-modal" data-target="#datePickerModal" report-name="plTripsReport" data-topic="PL Trips" name="button">Run</button>
           </div>
         </div>
       </div>
     </div>
   </div>


  </body>
 </html>
<?php
require 'modales/datePickerModal.php';
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>
 <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> -->
 <script src="/plsuite/Resources/jquery_ui_1_12_1/jquery-ui.min.js" charset="utf-8"></script>
 <script src="js/reports.js" charset="utf-8"></script>
