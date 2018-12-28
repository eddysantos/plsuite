<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';
//require $root . '/plsuite/Resources/PHP/Utilities/header.php';

/**Fetch information on the trip**/

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
     <link rel="stylesheet" href="/plsuite/Resources/alertify/css/alertify.min.css">
     <link rel="stylesheet" href="/plsuite/Resources/alertify/css/themes/bootstrap.min.css">
     <script src="/plsuite/Resources/fa_5/js/fontawesome-all.min.js"></script>
     <link rel="stylesheet" media="screen and (max-device-width: 700px)" href="/plsuite/Resources/CSS/mainMobile.css">
     <title>Prolog Transportation Inc</title>
   </head>
  <body style="min-height:100%">

   <div class="" id="trip-information" >
    <header id="trip-header"> <!-- This header appears for the trip information -->
       <div class="custom-header">
         <div class="custom-header-bar">&nbsp;</div>
         <div class="">
           <a class="ml-3 mr-5" role="button" id="backToDash" href="javascript:history.back()"><i class="fa fa-chevron-left"></i></a>
           <div class="w-100 d-flex align-items-center justify-content-between">
             <div class="pr-5">
               Add New Trip
             </div>
             <div class="mr-5">
               <button type="button" class="btn btn-outline-secondary" name="button" data-toggle="button" aria-pressed="false" autocomplete="off"><span id="toggle-fav-trips">Show<span> Favorite Trips</button>
             </div>
           </div>
         </div>
       </div>
    </header>



  </body>
 </html>
<?php
require 'modales/addLinehaul.php';
require 'modales/addMovement.php';
require 'modales/closeTripConfirmation.php';
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>
 <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> -->
 <script src="/plsuite/Resources/jquery_ui_1_12_1/jquery-ui.min.js" charset="utf-8"></script>
 <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuBCFwHZCWMgyeTJ1MI32sXlGnJtIIsUA" async defer></script> -->
 <script src="js/tripDetails.js" charset="utf-8"></script>
