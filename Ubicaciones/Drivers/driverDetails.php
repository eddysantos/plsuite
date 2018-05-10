<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';
// require $root . '/plsuite/Resources/PHP/Utilities/header.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$query = "SELECT * FROM ct_drivers WHERE pkid_driver = ? AND deletedDriver IS NULL";

$driver = $_GET['driverid'];

$stmt = $db->prepare($query);
$stmt->bind_param('s', $driver);
$stmt->execute();
$rslt = $stmt->get_result();

$driver = $rslt->fetch_assoc();

$query = "SELECT * FROM ct_truck WHERE deletedTruck IS NULL";

$stmt = $db->prepare($query);
$stmt->execute();
$rslt = $stmt->get_result();

while ($row = $rslt->fetch_assoc()) {
  $trucks[] = $row;
}




 ?>

 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="/plsuite/Resources/Bootstrap4/css/bootstrap.min.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/CSS/main.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/CSS/drivers.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/fontAwesome/css/font-awesome.min.css">
     <link rel="stylesheet" media="screen and (max-device-width: 700px)" href="/plsuite/Resources/CSS/mainMobile.css">
     <link href="https://fonts.googleapis.com/css?family=Sansita" rel="stylesheet">
     <title>Prolog Transportation Inc</title>
   </head>
  <body style="min-height:100%">

   <header>
     <div class="custom-header">
       <div class="custom-header-bar">&nbsp;</div>
       <div><a class="ml-3 mr-5" role="button" href="dashboard.php"> <i class="fa fa-chevron-left"></i> </a> <?php echo $driver['nameFirst'] . " " . $driver['nameLast']?></div>
     </div>
   </header>
   <div class="container mt-5 driver-details bottom-side-border pb-4">
     <div class="container float-left driver-details-child right-side-border">
       <h5 class="grey-font">Driver General Information</h5>
       <form>
         <div class="form-group row">
           <label class="col-2 col-form-label" for="dFirstName">First</label>
           <div class="col-4">
             <input class="form-control" type="text" name="dFirstName" id="dFirstName" value="<?php echo $driver['nameFirst'] ?>">
           </div>
           <label class="col-2 col-form-label" for="dLastName">Last</label>
           <div class="col-4">
             <input class="form-control" type="text" name="dLastName" id="dLastName" value="<?php echo $driver['nameLast'] ?>">
           </div>
         </div>
         <div class="form-group row">
           <label class="col-2 col-form-label" for="dPhone">Phone</label>
           <div class="col-10">
             <input class="form-control" type="text" name="dPhone" id="dPhone" value="<?php echo $driver['phoneNumber'] ?>">
           </div>
         </div>
         <div class="form-group row">
           <label class="col-2 col-form-label" for="dEmail">E-Mail</label>
           <div class="col-10">
             <input class="form-control" type="text" name="dEmail" id="dEmail" value="<?php echo $driver['email'] ?>">
           </div>
         </div>
         <div class="form-group row">
           <label class="col-2 col-form-label" for="dIsDriver">Driver?</label>
           <div class="col-4">
             <select class="form-control" name="dIsDriver" id="dIsDriver">
               <option value="Yes" <?php echo $driver['isDriver'] == "Yes" ? "Selected" : ""?> >Yes</option>
               <option value="No" <?php echo $driver['isDriver'] == "No" ? "Selected" : ""?> >No</option>
             </select>
           </div>
           <label class="col-2 col-form-label" for="dIsOwner">Owner?</label>
           <div class="col-4">
             <select class="form-control" name="dIsOwner" id="dIsOwner">
               <option value="Yes" <?php echo $driver['isOwner'] == "Yes" ? "Selected" : ""?> >Yes</option>
               <option value="No" <?php echo $driver['isOwner'] == "No" ? "Selected" : ""?> >No</option>
             </select>
           </div>
         </div>
         <div class="form-group row">
           <label class="col-2 col-form-label" for="defaultTruck">Default Truck</label>
             <div class="form-group col-4">
               <select class="form-control" id="defaultTruck" name="defaultTruck">
                 <option value="">None</option>
                 <?php foreach ($trucks as $truck): ?>
                   <option value="<?php echo $truck['pkid_truck']?>" <?php echo $truck['pkid_truck'] == $driver['default_truck'] ? 'selected' : ''; ?>><?php echo $truck['truckNumber'] ?></option>
                 <?php endforeach; ?>
               </select>
             </div>
         </div>
         <input type="text" id="dIdDriver" name="dIdDriver" value="<?php echo $driver['pkid_driver'] ?>" hidden>
       </form>
     </div>
     <div class="container float-right driver-details-child">
       <h5 class="grey-font">Address</h5>
       <form>
         <div class="form-group row">
           <label class="col-3 col-form-label" for="dStNumber">Number / St</label>
           <div class="col-5">
             <input class="form-control" type="text" name="dStNumber" id="dStNumber" placeholder="stNumber">
           </div>
           <div class="col-4">
             <input class="form-control" type="text" name="dStName" id="dStName" placeholder="stName">
           </div>

         </div>
         <div class="form-group row">
           <label class="col-3 col-form-label" for="dAddrLine2">Addr Line 2</label>
           <div class="col-9">
             <input class="form-control" type="text" name="dAddrLine2" id="dAddrLine2" placeholder="Line 2">
           </div>
         </div>
         <div class="form-group row">
           <label class="col-3 col-form-label" for="dCity">City</label>
           <div class="col-4">
             <input class="form-control" type="text" name="dCity" id="dCity" value="" placeholder="City">
           </div>
           <label class="col-2 col-form-label text-right" for="dState">State</label>
           <div class="col-3">
             <input class="form-control" type="text" name="dState" id="dState" value="" placeholder="State">
           </div>
         </div>
         <div class="form-group row">
           <label class="col-3 col-form-label" for="dZipCode">Zip</label>
           <div class="col-3">
             <input class="form-control" type="text" name="dZipCode" id="dZipCode" value="" placeholder="Zip Code">
           </div>
           <label class="col-3 col-form-label" for="dCountry">Country</label>
           <div class="col-3">
             <input class="form-control" type="text" name="dCountry" id="dCountry" value="" placeholder="Country">
           </div>
         </div>
       </form>
     </div>
     <button class="btn btn-success float-right" id="saveDriverDetails" type="button" name="button">Save Info</button>
   </div>

   <div class="container mt-5 driver-details">
     <div class="container float-left driver-details-child right-side-border" hidden>
       Aquí va la información de los camiones. Solo si es Owner-op.
     </div>
     <div class="container float-right driver-details-child">
       Aquí va la información de los e-documents. Aplica para todos los elementos.
     </div>
   </div>



  </body>
 </html>

<?php
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>
 <script src="js/drivers.js" charset="utf-8"></script>
