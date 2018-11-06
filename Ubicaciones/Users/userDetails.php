<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';
// require $root . '/plsuite/Resources/PHP/Utilities/header.php';
require 'actions/get_user_details.php';

 ?>

 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="/plsuite/Resources/Bootstrap_4_1_1/css/bootstrap.min.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/CSS/main.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/CSS/users.css">
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
          <a class="ml-3 mr-5" role="button" href="/plsuite/Ubicaciones/Users/"><i class="fa fa-chevron-left"></i></a>
          <div class="w-100 pr-4 d-flex align-items-center justify-content-between">
            <div class="pr-5">
              User Details
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="main-details-container">
      <div class="row div-100h">
        <div class="col-sm-2 ml-0 pl-0 border border-bottom-0 border-left-0 border-top-0 ml-0 pl-0 pr-0">
          <nav class="nav flex-column nav-30-px" id="user-details-nav-pane" role="tablist">
            <a class="nav-link dash side-panel active" id="user-details-tab" data-toggle="tab" role="tab" aria-selected="true" aria-controls="user-details" href="#user-details-pane">
              User Details
            </a>
            <a class="nav-link dash side-panel" id="user-permissions-tab" data-toggle="tab" role="tab" aria-selected="false" aria-controls="user-permissions" href="#user-permissions-pane">
              Permissions
            </a>
          </nav>
        </div>
        <div class="col-sm-10 tab-info tab-content" style="overflow: scroll">
          <div class="tab-pane fade show active p-2" id="user-details-pane">
            <h6>General Info</h6>
            <div class="row">
              <div class="col-sm-8">
                <section>
                  <form class="" id="user-details-form" onsubmit='return false;'>
                    <div class="form-group row">
                      <label for="" class="col-md-2 text-right col-form-label">Username</label>
                      <div class="col-md-6">
                        <input type="text" class="form-control-plaintext" name="" value="<?php echo $get_user_details['NombreUsuario'] ?>" placeholder="First Name" readonly>
                        <input type="text" hidden name="" id="user-pkid" value="<?php echo $get_user_details['pkIdUsers']?>">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="" class="col-md-2 text-right col-form-label">Status</label>
                      <div class="col-md-2">
                        <select class="form-control" id="user-status" name="">
                          <option value="Active" <?php echo $get_user_details['Status'] == 'Active' ? 'Selected' : '' ?>>Active</option>
                          <option value="Inactive" <?php echo $get_user_details['Status'] == 'Inactive' ? 'Selected' : '' ?>>Inactive</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="" class="col-md-2 text-right col-form-label">First Name</label>
                      <div class="col-md-7">
                        <input type="text" class="form-control" id="user-first-name" name="" value="<?php echo $get_user_details['Nombre'] ?>" placeholder="First Name">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="" class="col-md-2 text-right col-form-label">Last Name</label>
                      <div class="col-md-7">
                        <input type="text" class="form-control" name="" id="user-last-name" value="<?php echo $get_user_details['Apellido'] ?>" placeholder="First Name">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="" class="col-md-2 text-right col-form-label">E-Mail</label>
                      <div class="col-md-7">
                        <input type="text" class="form-control" name="" id="user-email" autocomplete="new-password" value="<?php echo $get_user_details['email'] ?>" placeholder="E-Mail">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="" class="col-md-2 text-right col-form-label">User Type</label>
                      <div class="col-md-7">
                        <select class="form-control" id="user-type" name="">
                          <option value="Global" <?php echo $get_user_details['TipoUsuario'] == 'Global' ? 'Selected' : '' ?>>Global</option>
                          <option value="Broker" <?php echo $get_user_details['TipoUsuario'] == 'Broker' ? 'Selected' : '' ?>>Broker</option>
                        </select>
                        <small class="form-text text-muted"><b>Global </b>users have access to the entire Load Management System, restricted  by the permissions.</small>
                        <small class="form-text text-muted"><b>Broker </b> users only have access to BL Tool, restricting it's content only to loads pertaining to them.</small>
                      </div>
                    </div>
                    <div class="form-group row" id="broker-field" style="<?php echo $get_user_details['TipoUsuario'] != 'Broker' ? 'display: none' : "" ?>">
                      <label for="broker-name" class="col-sm-2 col-form-label text-right">Broker</label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="text" class="form-control popup-input selected-broker" id="user-broker" id-display="#broker-popup-list" aria-describedby="addBrokerotf" id="brokerName" type="text" autocomplete="new-password" db-id="<?php echo $get_user_details['fkid_broker'] ?>" name="" value="<?php echo $get_user_details['brokerName'] ?>" placeholder="Broker Name">
                        </div>
                        <div id="broker-popup-list" class="popup-list mt-3" style="display: none; z-index: 9999"></div>
                      </div>
                    </div>

                  </form>
                </section>
              </div>
              <div class="col-sm-2">
                <div class="d-flex flex-column">
                  <button type="button" name="button" id="save-user-details" class="btn btn-outline-success">Save Changes</button>
                  <button type="button" name="button" class="btn btn-outline-secondary mt-2">Reset Password</button>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane fade p-2" id="user-permissions-pane">
            <div class="row">
              <div class="col-md-7" id="permissions-lists">
                <div class="border p-2 rounded mb-2">
                  <ul class="permissions-list p-0">
                    <li>
                      <h6>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="administration_role">
                          <label class="form-check-label" for="administration_role">
                            Administration
                          </label>
                        </div>
                      </h6>
                      <ul class="permissions-list">
                        <li>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="user_management_role" data-group="user_management_role" data-parent="#administration_role">
                            <label class="form-check-label" for="user_management_role">
                              User Management
                            </label>
                          </div>
                          <ul class="permissions-list">
                            <li>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="user_management_role_add_users" data-group="user_management_role" data-parent="#user_management_role">
                                <label class="form-check-label" for="user_management_role_add_users">
                                  Add / Remove Users
                                </label>
                              </div>
                            </li>
                            <li>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="user_management_role_change_permissions" data-group="user_management_role" data-parent="#user_management_role">
                                <label class="form-check-label" for="user_management_role_change_permissions">
                                  Permissions Management
                                </label>
                              </div>
                            </li>
                          </ul>
                        </li>
                        <li>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="invoice_control_role" data-group="invoice_control_role">
                            <label class="form-check-label" for="invoice_control_role">
                              Invoice Control
                            </label>
                          </div>
                          <ul class="permissions-list">
                            <li>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="invoice_control_save" data-group="invoice_control_role" data-parent="#invoice_control_role">
                                <label class="form-check-label" for="invoice_control_save">
                                  Edit invoice information
                                </label>
                              </div>
                            </li>
                          </ul>
                        </li>
                      </ul>
                    </li>
                  </ul>
                </div>
                <div class="border p-2 rounded mb-2">
                  <ul class="permissions-list p-0">
                    <li>
                      <h6>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="trip_role">
                          <label class="form-check-label" for="trip_role">
                            Trips
                          </label>
                        </div>
                      </h6>
                      <ul class="permissions-list">
                        <li>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="trip_role_editing" data-group="trip-management" data-parent="#trip_role">
                            <label class="form-check-label" for="trip_role_editing">
                              Enable Editing
                            </label>
                          </div>
                        </li>
                        <li>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="trip_role_reopen_closed_trips" data-group="trip-management" data-parent="#trip_role">
                            <label class="form-check-label" for="trip_role_reopen_closed_trips">
                              Re-Open Trips
                            </label>
                          </div>
                        </li>
                      </ul>
                    </li>
                  </ul>
                </div>
                <div class="border p-2 rounded mb-2">
                  <h6>Drivers</h6>
                </div>
                <div class="border p-2 rounded mb-2">
                  <h6>Trucks</h6>
                </div>
                <div class="border p-2 rounded mb-2">
                  <h6>Trailer</h6>
                </div>
                <div class="border p-2 rounded mb-2">
                  <h6>Brokers</h6>
                </div>
              </div>
              <div class="col-md-2" id="permissions-save-col">
                <button type="button" name="button" class="btn btn-outline-success" id="save-permissions-btn">Save Changes</button>
              </div>
            </div>
          </div>
        </div>
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
