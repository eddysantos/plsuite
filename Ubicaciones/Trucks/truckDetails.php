<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';
// require $root . '/plsuite/Resources/PHP/Utilities/header.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$query = "SELECT pkid_driver, nameFirst, nameLast FROM ct_drivers WHERE isOwner = 'Yes'";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during trailer query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during trailer query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();

if ($rslt->num_rows == 0) {
  $system_callback['query']['code'] = 2;
  $system_callback['query']['message'] = "Script called successfully but there are no rows to display. For trip query.";
  $system_callback['query']['data'] .= $row;
  exit_script($system_callback);
} else {
  $system_callback['query']['code'] = 1;
  while ($row = $rslt->fetch_assoc()) {
    $system_callback['owners'][] = $row;
  }
}

$query = "SELECT * FROM ct_truck WHERE pkid_truck = ? AND deletedTruck IS NULL";

$driver = $_GET['truckid'];

$stmt = $db->prepare($query);
$stmt->bind_param('s', $driver);
$stmt->execute();
$rslt = $stmt->get_result();

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
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/CSS/trucks.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/fontAwesome/css/font-awesome.min.css">
     <link rel="stylesheet" media="screen and (max-device-width: 700px)" href="/plsuite/Resources/CSS/mainMobile.css">
     <link href="https://fonts.googleapis.com/css?family=Sansita" rel="stylesheet">
     <title>Prolog Transportation Inc</title>
   </head>
  <body style="min-height:100%">

   <header>
     <div class="custom-header">
       <div class="custom-header-bar">&nbsp;</div>
       <div id="truckNumberLabel"><a class="ml-3 mr-5" role="button" href="dashboard.php"> <i class="fa fa-chevron-left"></i> </a> <?php echo $row['truckNumber']?> </div>
     </div>
   </header>
   <div class="main-details-container">
     <!-- <div class="container-fluid"> -->
       <div class="row">
         <div class="col-lg-2 ml-0 pl-0 border border-bottom-0 border-left-0 border-top-0 ml-0 pl-0 pr-0">
           <nav class="nav flex-column" id="truck-details-nav-pane" role="tablist">
             <a class="nav-link active" id="general-info-tab" data-toggle="tab" role="tab" aria-selected="true" aria-controls="general-info" href="#general-info">General Information</a>
             <a class="nav-link" id="settlements-tab" data-toggle="tab" role="tab" aria-selected="false" aria-controls="settlements" href="#settlements-info">Settlements</a>
             <a class="nav-link" id="loans-tab" data-toggle="tab" role="tab" aria-selected="false" aria-controls="loans" href="#loans-info">Loans</a>
           </nav>
         </div>
         <div class="col-lg-10 tab-info">
           <div class="row">
             <div class="col-lg-6 offset-1 pt-5">
               <div class="tab-content" id="truck-details-tab-content">
                 <div class="tab-pane fade show active" id="general-info" role="tabpanel" aria-labelledby="general-info-tab">
                   <form class="form-group" onsubmit="return false">
                     <label for="">Truck Number</label>
                     <input type="text" class="form-control" name="" value="">
                     <div class="form-row">
                       <label for="" class="col-lg-3">Truck Number</label>
                       <div class="col-lg-9">
                         <input type="text" class="form-control" name="" value="">
                       </div>
                     </div>
                     <label for="">Owner</label>
                     <input type="text" class="form-control" name="" value="">
                     <label for="">Serial</label>
                     <input type="text" class="form-control" name="" value="">
                     <label for="">Make</label>
                     <input type="text" class="form-control" name="" value="">
                     <label for="">Model</label>
                     <input type="text" class="form-control" name="" value="">
                     <label for="">Year</label>
                     <input type="text" class="form-control" name="" value="">
                   </form>
                 </div>
                 <div class="tab-pane fade" id="settlements-info" role="tabpanel" aria-labelledby="general-info-tab">
                   Aquí la información de los settlements
                 </div>
                 <div class="tab-pane fade" id="loans-info" role="tabpanel" aria-labelledby="general-info-tab">
                   Aqui la información de los loans.
                 </div>
               </div>
             </div>
           </div>
         </div>
       </div>
     <!-- </div> -->
   </div>




  </body>
 </html>

<?php
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>
 <script src="js/truckDetails.js" charset="utf-8"></script>
