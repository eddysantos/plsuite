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
       <div id="truckNumberLabel"><a class="ml-3 mr-5" role="button" href="dashboard.php"> <i class="fa fa-chevron-left"></i> </a> <?php echo $row['truckNumber']?> <a class="ml-3" id="editTruckNumberButton" role="button"> <i class="fa fa-pencil-square-o"></i> </a> </div>
       <div id="truckNumberEditLabel" style="display: none"><!--a class="ml-3 mr-5" role="button" href="dashboard.php"> <i class="fa fa-chevron-left"></i> </a-->
         <input type="text" class="form-control w-25 ml-3" name="newTruckNumber" id="newTruckNumber" value="<?php echo $row['truckNumber']?>">  <a class="ml-3" id="saveTruckNumberButton" role="button"> <i class="fa fa-floppy-o"></i> </a> </div>
     </div>
   </header>
   <div class="container mt-5 driver-details pb-4">
     <div class="container float-left driver-details-child right-side-border">
       <h5 class="grey-font">Truck General Information</h5>
       <form>
         <div class="form-group row">
           <label class="col-2 col-form-label" for="tBrand">Brand</label>
           <div class="col-10">
             <input class="form-control" type="text" name="tBrand" id="tBrand" value="<?php echo $row['truckBrand'] ?>">
           </div>
         </div>
         <div class="form-group row">
           <label class="col-2 col-form-label" for="tVIN">VIN</label>
           <div class="col-10">
             <input class="form-control" type="text" name="tVIN" id="tVIN" value="<?php echo $row['truckVIN'] ?>">
           </div>
         </div>
         <div class="form-group row">
           <label class="col-2 col-form-label" for="tYear">Year</label>
           <div class="col-4">
             <input class="form-control" type="text" name="tYear" id="tYear" value="<?php echo $row['truckYear'] ?>">
           </div>
           <label class="col-2 col-form-label" for="tPlates">Plates</label>
           <div class="col-4">
             <input class="form-control" type="text" name="tVIN" id="tPlates" value="<?php echo $row['truckPlates'] ?>">
           </div>
         </div>
         <div class="form-group row">
           <label class="col-3 col-form-label" for="tOwnedBy">Owned By</label>
           <div class="col-9">
             <select class="form-control" name="tOwnedBy" id="tOwnedBy">
               <option value="0" <?php echo $row['truckOwnedBy'] == 0 ? "Selected" : ""?> >Prolog Transportation</option>
               <?php foreach ($system_callback['owners'] as $owner): ?>
                 <option value="<?php echo $owner['pkid_driver']?>" <?php echo $owner['pkid_driver'] == $row['truckOwnedBy'] ? "selected" : ""?>><?php echo "$owner[nameFirst] $owner[nameLast]" ?></option>
               <?php endforeach; ?>
             </select>
           </div>
         </div>
         <div class="form-group row">
           <label for="tPayPerMile" class="col-2 col-form-label">PPM</label>
           <div class="col-4">
             <div class="input-group">
               <div class="input-group-addon input-group-prepend">
                 <span class="input-group-text">$</span>
               </div>
               <input type="number" class="form-control" name="tPayPerMile" id="tPayPerMile" name="" value="<?php echo $row['pay_per_mile']?>">
             </div>
           </div>
           <label for="tApplySurcharge" class="col-2 col-form-label">Apply Surcharge</label>
           <div class="col-4">
             <div class="input-group">
               <select class="form-control" id="tApplySurcharge" name="tApplySurcharge">
                 <option value="1" <?php echo $row['apply_surcharge'] == "1" ? 'selected' : ''?>>Yes</option>
                 <option value="0" <?php echo $row['apply_surcharge'] != "1" ? 'selected' : ''?>>No</option>
               </select>
             </div>
           </div>
         </div>
         <input type="text" id="truck_id" name="" value="<?php echo $row['pkid_truck']?>" hidden>
       </form>
     </div>
     <div class="container float-right driver-details-child">
       <div class="d-flex justify-content-between">
         <h5 class="grey-font d-inline">Documentation</h5>
         <p class="d-inline float-right">+ New Document</p>
       </div>
       <div class="row no-gutters">
         <h6 class="col-6">Document</h6>
         <h6 class="col-6">Expiration</h6>
       </div>
     </div>
   </div>

   <div class="container text-center bottom-side-border pb-3">
     <button class="btn btn-success w-25" type="button" name="button" id="saveTruckDetails">Save Info</button>
   </div>


   <!-- <div class="container mt-5 driver-details">
     <div class="container float-left driver-details-child right-side-border" hidden>
       Aquí va la información de los camiones. Solo si es Owner-op.
     </div>
     <div class="container float-right driver-details-child">
       Aquí va la información de los e-documents. Aplica para todos los elementos.
     </div>
   </div> -->



  </body>
 </html>

<?php
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>
 <script src="js/truckDetails.js" charset="utf-8"></script>
