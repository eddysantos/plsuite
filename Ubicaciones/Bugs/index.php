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
             Bugs and Suggestions Box
           </div>
         </div>
       </div>
     </div>
   </header>

   <div class="container-fluid mt-3 mb-3 text-right">
     <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#bugReportModal" name="button">Add New</button>
   </div>

   <table class="table table-hover">
     <thead>
       <tr>
         <th>Type</th>
         <th>Area</th>
         <th>Reported By</th>
         <th>Status</th>
         <th>Subject</th>
         <th class="w-50">Description</th>
         <th></th>
       </tr>
     </thead>
     <tbody id="bugDash">
     </tbody>
   </table>

   <!-- <div class="modal fade show" id="bugReportModal" tabindex="-1" role="dialog" aria-labelledby="bugReport" aria-hidden="true">
     <div class="modal-dialog">
       <div class="modal-content">
         <div class="modal-header">
           <h5>Welcome to the bug and suggestions box!</h5>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
           <p>You can use this handy tool to report a problem (bug!), or make a suggestion for a change on the website!</p>
           <form class="form-group" onsubmit="false">
             <div class="form-row">
               <label for="bugReportType" class="col-form-label col-3">Report Type</label>
               <div class="col-9 form-group">
                 <select class="form-control" name="bugReportType" id="bugReportType">
                   <option value="Bug">Bug</option>
                   <option value="Suggestion">Suggestion</option>
                 </select>
               </div>
             </div>
             <div class="form-row">
               <label for="bugReportType" class="col-form-label col-3">Website Area</label>
               <div class="col-9 form-group">
                 <select class="form-control" name="bugReportArea" id="bugReportArea">
                   <option value="Trips">Trips</option>
                   <option value="Drivers">Drivers</option>
                   <option value="Trucks">Trucks</option>
                   <option value="Trailers">Trailers</option>
                   <option value="Brokers">Brokers</option>
                   <option value="Other">Other</option>
                 </select>
               </div>
             </div>
             <div class="form-row">
               <label for="bugReportSubject" class="col-form-label col-3">Subject</label>
               <div class="col-9 form-group">
                 <input type="text" class="form-control" name="bugReportSubject" id="bugReportSubject" placeholder="Brief description of issue" value="">
               </div>
             </div>
             <label for="reportContent">Description</label>
             <textarea name="reportContent" class="form-control" rows="8" name="bugReportDescription" id="bugReportDescription" placeholder="Extensive description of issue"></textarea>
             <input type="text" class="form-control" name="reporting_user" id="bug_reported_by" value="<?php echo $_SESSION['user_info']['Nombre'] . " " .  $_SESSION['user_info']['Apellido']?>" hidden>
           </form>
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-outline-primary" id="submitBugReport" name="button">Submit</button>
         </div>
       </div>
     </div>
   </div> -->


  </body>
 </html>
<?php
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
require 'modales/bugDetails.php';
 ?>
 <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> -->
 <script src="/plsuite/Resources/jquery_ui_1_12_1/jquery-ui.min.js" charset="utf-8"></script>
 <!-- <script src="js/bugs.js" charset="utf-8"></script> -->
