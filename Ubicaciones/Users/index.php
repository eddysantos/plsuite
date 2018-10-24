<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';
// require $root . '/plsuite/Resources/PHP/Utilities/header.php';
require 'actions/get_users_main.php';

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
     <link rel="stylesheet" href="/plsuite/Resources/alertify/css/themes/default.min.css">
     <link rel="stylesheet" href="/plsuite/Resources/alertify/css/themes/bootsrap.min.css">
     <script src="/plsuite/Resources/fa_5/js/fontawesome-all.js" charset="utf-8"></script>
     <title>Prolog Transportation Inc</title>
   </head>
  <body style="min-height:100%">
    <header>
      <div class="custom-header">
        <div class="custom-header-bar">&nbsp;</div>
        <div class="">
          <a class="ml-3 mr-5" role="button" href="/plsuite/Ubicaciones/viajes/dashboard.php"><i class="fa fa-chevron-left"></i></a>
          <div class="w-100 pr-4 d-flex align-items-center justify-content-between">
            <div class="pr-5">
              User Management
            </div>
            <div class="">
              <button type="button" name="button" data-toggle="modal" data-target="#addUserModal" class="btn btn-outline-success">
                <i class="fas fa-user-plus"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </header>




   <div class="container-fluid pt-3 main-container">
     <div class="div-100h">
       <table class="table table-striped custom-table">
         <tbody id="user-table">
           <?php echo $users_list ?>
         </tbody>
       </table>
     </div>
   </div>


  </body>
 </html>
<?php
require 'modals/addUser.php';
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>
 <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> -->
 <script src="/plsuite/Resources/jquery_ui_1_12_1/jquery-ui.min.js" charset="utf-8"></script>
 <script src="js/users.js" charset="utf-8"></script>
