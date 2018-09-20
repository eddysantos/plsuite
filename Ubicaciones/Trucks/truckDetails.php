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
     <link rel="stylesheet" href="/plsuite/Resources/alertify/css/alertify.min.css">
     <link rel="stylesheet" href="/plsuite/Resources/alertify/css/themes/bootstrap.min.css">
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
       <div class="row">
         <div class="col-sm-2 ml-0 pl-0 border border-bottom-0 border-left-0 border-top-0 ml-0 pl-0 pr-0">
           <nav class="nav flex-column" id="truck-details-nav-pane" role="tablist">
             <a class="nav-link active" id="general-info-tab" data-toggle="tab" role="tab" aria-selected="true" aria-controls="general-info" href="#general-info">General Information</a>
             <a class="nav-link disabled" id="settlements-tab" data-toggle="tab" role="tab" aria-selected="false" aria-controls="documentation" href="#documentation-info">Documentation</a>
             <a class="nav-link disabled" id="settlements-tab" data-toggle="tab" role="tab" aria-selected="false" aria-controls="maintenance_logs" href="#settlements-info">Maintenance Log</a>
             <a class="nav-link disabled" id="settlements-tab" data-toggle="tab" role="tab" aria-selected="false" aria-controls="settlements" href="#settlements-info">Settlements</a>
             <a class="nav-link disabled" id="loans-tab" data-toggle="tab" role="tab" aria-selected="false" aria-controls="loans" href="#loans-info">Loans</a>
           </nav>
         </div>
         <div class="col-sm-10 tab-info">
               <div class="tab-content" id="truck-details-tab-content">
                 <div class="tab-pane fade show active" id="general-info" role="tabpanel" aria-labelledby="general-info-tab">
                   <div class="row pt-5">
                     <div class="col-lg-6 offset-1">
                       <form class="" onsubmit="return false">

                         <div class="form-group row">
                           <label for="" class="col-lg-3 col-form-label">Truck Status</label>
                           <div class="col-lg-9">
                             <select class="form-control" id="tStatus" name="">
                               <option value="Active" <?php echo $row['truckStatus'] == 'Active' ? 'selected' : '' ?>>Active</option>
                               <option value="OOS" <?php echo $row['truckStatus'] == 'OOS' ? 'selected' : '' ?>>OOS</option>
                               <option value="Grounded" <?php echo $row['truckStatus'] == 'Grounded' ? 'selected' : '' ?>>Grounded</option>
                               <option value="Inactive" <?php echo $row['truckStatus'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                             </select>
                           </div>
                         </div>
                         <div class="form-group row">
                           <label for="" class="col-lg-3 col-form-label">Truck Number</label>
                           <div class="col-lg-9">
                             <input type="text" id="tNumber" class="form-control" name="" value="<?php echo $row['truckNumber'] ?>">
                           </div>
                         </div>
                         <div class="form-group row">
                           <label for="" class="col-lg-3 col-form-label">Owner</label>
                           <div class="col-lg-9">
                             <select class="form-control" id="tOwnedBy" name="truck_owner">
                               <option value="0">Prolog Transportation</option>
                               <?php foreach ($system_callback['owners'] as $owner): ?>
                                 <option value="<?php echo $owner['pkid_driver'] ?>" <?php echo $row['truckOwnedBy'] == $owner['pkid_driver'] ? "selected" : "" ?>><?php echo $owner['nameFirst']." ".$owner['nameLast'] ?></option>
                               <?php endforeach; ?>
                             </select>
                           </div>
                         </div>
                         <div class="form-group row">
                           <label for="" class="col-lg-3 col-form-label">VIN</label>
                           <div class="col-lg-9">
                             <input type="text" id="tVIN" class="form-control" name="" value="<?php echo $row['truckVIN'] ?>">
                           </div>
                         </div>
                         <div class="form-group row">
                           <label for="" class="col-lg-3 col-form-label">Make</label>
                           <div class="col-lg-9">
                             <input type="text" id="tBrand" class="form-control" name="" value="<?php echo $row['truckBrand'] ?>">
                           </div>
                         </div>
                         <!-- <div class="form-group row">
                           <label for="" class="col-lg-3 col-form-label">Model</label>
                           <div class="col-lg-9">
                             <input type="text" class="form-control" name="" value="">
                           </div>
                         </div> -->
                         <div class="form-group row">
                           <label for="" class="col-lg-3 col-form-label">Year</label>
                           <div class="col-lg-3">
                             <input type="text" id="tYear" class="form-control text-center" name="" value="<?php echo $row['truckYear'] ?>">
                           </div>
                         </div>
                         <div class="form-group row">
                           <label for="" class="col-lg-3 col-form-label">Plates</label>
                           <div class="col-lg-3">
                             <input type="text" id="tPlates" class="form-control text-center" name="" value="<?php echo $row['truckPlates'] ?>">
                           </div>
                         </div>
                         <div class="form-group row">
                           <label for="" class="col-lg-3 col-form-label">PPM</label>
                           <div class="col-lg-3">
                             <input type="number" id="tPayPerMile" class="form-control text-center" name="" value="<?php echo $row['pay_per_mile'] ?>">
                           </div>
                         </div>
                        <input type="text" id="truck_id" name="" value="<?php echo $row['pkid_truck'] ?>" hidden>
                       </form>

                     </div> <!-- DIV LG 6 -->
                     <div class="col-lg-2 offset-2">
                       <div class="flex-columns">
                         <button type="button" class="btn btn-outline-success mb-1 w-100" id="saveTruckDetails" name="button">Save Changes</button>
                       </div>
                     </div>
                   </div>
                 </div>

                 <div class="tab-pane fade" id="documentation-info" role="tabpanel" aria-labelledby="general-info-tab">
                   <div class="row pt-5">
                     <div class="col-lg-10 offset-1 ">
                       <div class="">

                         <ul class="nav mb-3">
                           <li class="nav-item">
                             <a href="#" class="nav-link active trucks">Current Docs</a>
                           </li>
                           <li class="nav-item">
                             <a href="#" class="nav-link trucks">Archive</a>
                           </li>
                         </ul>

                         <form class="form-inline justify-content-between" onsubmit="return false;">
                           <div class="">
                             <label for="" class="sr-only">Document Type</label>
                             <select class="form-control mr-sm-2" name="">
                               <option value="">Select Document Type</option>
                             </select>
                             <label for="" class="sr-only">Uploaded File</label>
                             <input type="file" class="mr-sm-2" name="" value="">
                           </div>
                           <button type="button" class="btn btn-outline-success" name="button">Add File</button>
                         </form>

                         <table class="table table-striped table-sm">
                           <thead class="border-top-0">
                             <th class="border-top-0"></th>
                             <th class="border-top-0">Document Type</th>
                             <th class="border-top-0">Actions</th>
                           </thead>
                         </table>
                       </div>

                     </div> <!-- DIV LG 6 -->
                   </div>
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




  </body>
 </html>

<?php
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>
 <script src="js/truckDetails.js" charset="utf-8"></script>
