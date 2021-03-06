<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';
// require $root . '/plsuite/Resources/PHP/Utilities/header.php';
$back_button_url = "";

if (!$_SESSION['user_info']['cred_is_admin']) {
  header("location:/plsuite/access_denied.php");
}

switch ($_SESSION['current_portal']) {
  case 'us':
    $back_button_url = '/plsuite/Ubicaciones/viajes/dashboard.php';
    break;

  case 'mx':
    $back_button_url = '/plsuite/mxportal';
    break;
}

 ?>

 <!DOCTYPE html>
 <html style="height: 100%">
   <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="/plsuite/Resources/Bootstrap_4_3/css/bootstrap.min.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/CSS/main.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/CSS/trips.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/CSS/users.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/fontAwesome/css/font-awesome.min.css">
     <link rel="stylesheet" href="/plsuite/Resources/alertify/css/alertify.min.css">
     <!-- <link rel="stylesheet" href="/plsuite/Resources/alertify/css/themes/default.min.css"> -->
     <link rel="stylesheet" href="/plsuite/Resources/alertify/css/themes/bootstrap.min.css">
     <link rel="stylesheet" media="screen and (max-device-width: 700px)" href="/plsuite/Resources/CSS/mainMobile.css">
     <link href="https://fonts.googleapis.com/css?family=Sansita" rel="stylesheet">
     <title>PLT - Users</title>
   </head>
  <body style="height:100%">
    <header class="">
      <div class="custom-header">
        <div class="custom-header-bar">&nbsp;</div>
        <div class="">
          <a class="ml-3 mr-5" role="button" href="<?php echo $back_button_url?>"><i class="fa fa-chevron-left"></i></a>
          <div class="w-100 d-flex align-items-center justify-content-between">
            <div class="pr-5">
              Users
            </div>
            <div class="">
              <button type="button" class="btn btn-outline-primary mr-5" name="button">Add User</button>
            </div>
          </div>
        </div>
      </div>
    </header>
    <div class="content-div">
      <table class="table table-striped table-sm" id="user-table">
        <thead>
          <tr>
            <th style="width: 50px"></th>
            <th>Name</th>
            <th>Username</th>
            <th>Credentials</th>
            <th></th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>



  </body>
 </html>
<?php
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>
 <script src="/plsuite/Resources/jquery_ui_1_12_1/jquery-ui.min.js" charset="utf-8"></script>
 <script src="js/users.js" charset="utf-8"></script>
