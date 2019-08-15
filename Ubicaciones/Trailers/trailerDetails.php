<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';
// require $root . '/plsuite/Resources/PHP/Utilities/header.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$query = "SELECT * FROM ct_trailer WHERE pkid_trailer = ? AND deletedTrailer IS NULL";

$trailer = $_GET['trailerid'];

$stmt = $db->prepare($query);
$stmt->bind_param('s', $trailer);
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
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/CSS/trailers.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/fontAwesome/css/font-awesome.min.css">
     <link rel="stylesheet" media="screen and (max-device-width: 700px)" href="/plsuite/Resources/CSS/mainMobile.css">
     <link href="https://fonts.googleapis.com/css?family=Sansita" rel="stylesheet">
     <title>Prolog Transportation Inc</title>
   </head>
  <body style="min-height:100%">

   <header>
     <div class="custom-header">
       <div class="custom-header-bar">&nbsp;</div>
       <div id="trailerNumberLabel"><a class="ml-3 mr-5" role="button" href="dashboard.php"> <i class="fa fa-chevron-left"></i> </a> <?php echo $row['trailerNumber']?> <a class="ml-3" id="editTrailerNumberButton" role="button"> <i class="fa fa-pencil-square-o"></i> </a> </div>
       <div id="trailerNumberEditLabel" style="display: none"><!--a class="ml-3 mr-5" role="button" href="dashboard.php"> <i class="fa fa-chevron-left"></i> </a-->
         <input type="text" class="form-control w-25 ml-3" name="newTrailerNumber" id="newTrailerNumber" value="<?php echo $row['trailerNumber']?>">  <a class="ml-3" id="saveTrailerNumberButton" role="button"> <i class="fa fa-floppy-o"></i> </a> </div>
     </div>
   </header>
   <div class="container mt-5 driver-details pb-4">
     <div class="container float-left driver-details-child right-side-border">
       <h5 class="grey-font">Trailer General Information</h5>
       <form>
         <div class="form-group row">
           <label class="col-2 col-form-label" for="tBrand">Brand</label>
           <div class="col-10">
             <input class="form-control" type="text" name="tBrand" id="tBrand" value="<?php echo $row['trailerBrand'] ?>">
           </div>
         </div>
         <div class="form-group row">
           <label class="col-2 col-form-label" for="tVIN">VIN</label>
           <div class="col-10">
             <input class="form-control" type="text" name="tVIN" id="tVIN" value="<?php echo $row['trailerVIN'] ?>">
           </div>
         </div>
         <div class="form-group row">
           <label class="col-2 col-form-label" for="tYear">Year</label>
           <div class="col-4">
             <input class="form-control" type="text" name="tYear" id="tYear" value="<?php echo $row['trailerYear'] ?>">
           </div>
           <label class="col-2 col-form-label" for="tPlates">Plates</label>
           <div class="col-4">
             <input class="form-control" type="text" name="tVIN" id="tPlates" value="<?php echo $row['trailerPlates'] ?>">
           </div>
         </div>
         <div class="form-group row">
           <label class="col-3 col-form-label" for="tOwnedBy">Owned By</label>
           <div class="col-9">
             <select class="form-control" name="tOwnedBy" id="tOwnedBy">
               <option value="0" <?php echo $row['trailerOwnedBy'] == 0 ? "Selected" : ""?> >Prolog</option>
               <option value="1" <?php echo $row['trailerOwnedBy'] == 1 ? "Selected" : ""?> >IM International</option>
               <option value="2" <?php echo $row['trailerOwnedBy'] == 2 ? "Selected" : ""?> >Xtra Lease</option>
               <option value="3" <?php echo $row['trailerOwnedBy'] == 3 ? "Selected" : ""?> >Mega</option>
             </select>
           </div>
         </div>
         <input type="text" id="trailer_id" name="" value="<?php echo $row['pkid_trailer']?>" hidden>
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
     <button class="btn btn-success w-25" type="button" name="button" id="saveTrailerDetails">Save Info</button>
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
 <script src="js/trailers.js" charset="utf-8"></script>
