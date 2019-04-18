<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';
// require $root . '/plsuite/Resources/PHP/Utilities/header.php';


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
     <link rel="stylesheet" media="screen and (max-device-width: 700px)" href="/plsuite/Resources/CSS/mainMobile.css">
     <link href="https://fonts.googleapis.com/css?family=Sansita" rel="stylesheet">
     <title>PLT - Users</title>
   </head>
  <body style="height:100%">
    <header class="">
      <div class="custom-header">
        <div class="custom-header-bar">&nbsp;</div>
        <div class="">
          <a class="ml-3 mr-5" role="button" href="/plsuite/Ubicaciones/viajes/dashboard.php"><i class="fa fa-chevron-left"></i></a>
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
      <table class="table table-striped table-sm">
        <thead>
          <tr>
            <th style="width: 50px"></th>
            <th>Name</th>
            <th>Username</th>
            <th>Actions</th>
            <th>Portals</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <div class="form">
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" id="customSwitch1">
                  <label class="custom-control-label" for="customSwitch1"></label>
                </div>
              </div>
            </td>
            <td>Eduardo Santos</td>
            <td>esantos</td>
            <td>Some Actions</td>
            <td>
              <div class="form">
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" id="mexicanPortal">
                  <label class="custom-control-label" for="mexicanPortal">Mexican Portal</label>
                </div>
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" id="americanPortal">
                  <label class="custom-control-label" for="americanPortal">American Portal</label>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td>Active</td>
            <td>Oscar Santos</td>
            <td>osantos</td>
            <td>Some Actions</td>
            <td>Portals</td>
          </tr>
        </tbody>
      </table>
    </div>



  </body>
 </html>
<?php
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>
 <script src="/plsuite/Resources/jquery_ui_1_12_1/jquery-ui.min.js" charset="utf-8"></script>
 <script src="js/users.js" charset="utf-8"></script>
