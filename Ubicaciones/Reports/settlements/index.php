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
     <link rel="stylesheet" href="/plsuite/Resources/Bootstrap_4_1_1/css/bootstrap.min.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/CSS/main.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/CSS/trips.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/fontAwesome/css/font-awesome.min.css">
     <link rel="stylesheet" media="screen and (max-device-width: 700px)" href="/plsuite/Resources/CSS/mainMobile.css">
     <!-- <link href="https://fonts.googleapis.com/css?family=Sansita" rel="stylesheet"> -->
     <title>Prolog Transportation Inc</title>
   </head>
  <body style="min-height:100%">

   <header>
     <div class="custom-header">
       <div class="custom-header-bar">&nbsp;</div>
       <div class="ml-5">
         <div class="w-100 d-flex align-items-center justify-content-between">
           <div class="pr-5">
             Settlements Portal
           </div>
         </div>
       </div>
     </div>
   </header>

   <div class="container-fluid pt-3">
     <div class="">
        <table class="table table-striped">
          <thead>
            <th>Truck Number</th>
            <th>Truck Owner</th>
            <th>Unsettled Trips</th>
            <th>Total Miles</th>
            <th>Gross Income</th>
            <th>Deductibles</th>
            <th>Total Payout</th>
          </thead>
        </table>
     </div>
     <!-- <div class='overlay d-flex align-items-center' style='z-index: 2000'><div class='overlay-loading justify-content-center d-flex align-items-center'><p><i class='fa fa-spinner fa-pulse fa-3x fa-fw'></i></p><p>Loading active trucks...</p><div></div> -->
   </div>


  </body>
 </html>
<?php
// require 'modales/datePickerModal.php';
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>
 <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> -->
 <script src="/plsuite/Resources/jquery_ui_1_12_1/jquery-ui.min.js" charset="utf-8"></script>
 <script src="js/reports.js" charset="utf-8"></script>
 <script src="js/settlements.js" charset="utf-8"></script>
