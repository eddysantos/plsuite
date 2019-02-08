<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';
//require $root . '/plsuite/Resources/PHP/Utilities/header.php';

/**Fetch information on the trip**/

function parseDate($datestamp){
  $return = array(
    'date'=>"",
    'time'=>array(
      'hour'=>"",
      'minute'=>""
    )
  );

  if ($datestamp == "") {
    return $return;
  }

  $return['date'] = date('Y-m-d', strtotime($datestamp));
  $return['time']['hour'] = date('H', strtotime($datestamp));
  $return['time']['minute'] = date('i', strtotime($datestamp));

  return $return;
}


 ?>

 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="/plsuite/Resources/Bootstrap_4_2/css/bootstrap.min.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/CSS/main.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="/plsuite/Resources/CSS/trips.css">
     <link rel="stylesheet" href="/plsuite/Resources/alertify/css/alertify.min.css">
     <link rel="stylesheet" href="/plsuite/Resources/alertify/css/themes/bootstrap.min.css">
     <script src="/plsuite/Resources/fa_5/js/fontawesome-all.min.js" data-auto-replace-svg="nest" charset="utf-8"></script>
     <link rel="stylesheet" media="screen and (max-device-width: 700px)" href="/plsuite/Resources/CSS/mainMobile.css">
     <title>Prolog Transportation Inc</title>
   </head>
  <body style="min-height:100%">

   <div class="" id="trip-information" >
    <header id="trip-header"> <!-- This header appears for the trip information -->
       <div class="custom-header">
         <div class="custom-header-bar">&nbsp;</div>
         <div class="">
           <a class="ml-3 mr-5" role="button" id="backToDash" href="javascript:history.back()"><i class="fa fa-chevron-left"></i></a>
           <div class="w-100 d-flex align-items-center justify-content-between">
             <div class="pr-5">
               Add New Trip
             </div>
             <div class="mr-5">
               <button type="button" class="btn btn-outline-secondary" name="button" data-toggle="button" aria-pressed="false" autocomplete="off"><span id="toggle-fav-trips">Show<span> Favorite Trips</button>
             </div>
           </div>
         </div>
       </div>
    </header>
    <ul class="nav justify-content-center" id="add_trip_progress" role="tablist">
      <li class="nav-item">
        <a class="nav-link custom active" id="trip-details-tab" data-toggle="tab" tab-type="addTripWindow" href="#trip-details-pane" role="tab" aria-controls="trip-details" aria-selected="true" progress="0">Linehaul Details</a>
      </li>
      <li class="nav-item">
        <a class="nav-link custom disabled" disabled id="lh-details-tab" data-toggle="tab" tab-type="addTripWindow" href="#lh-details-pane" role="tab" aria-controls="lh-details" aria-selected="true" progress="33">Movement Details</a>
      </li>
      <li class="nav-item">
        <a class="nav-link custom disabled" disabled id="conveyance-details-tab" data-toggle="tab" tab-type="addTripWindow" href="#conveyance-details-pane" role="tab" aria-controls="conveyance-details" aria-selected="true" progress="66">Conveyance</a>
      </li>
      <li class="nav-item">
        <a class="nav-link custom disabled" disabled id="trip-confirmation-tab" data-toggle="tab" tab-type="addTripWindow" href="#trip-confirmation-pane" role="tab" aria-controls="trip-confirmation" aria-selected="true" progress="100">Confirm Trip</a>
      </li>
    </ul>

    <div class="progress mb-3" style="height: 2px">
      <div class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <div class="tab-content" id="trip-details-content">
      <div class="tab-pane fade show active" id="trip-details-pane" role="tabpanel" aria-labelledby="trip-details-tab">
        <form class="needs-validation" action="index.html" onsubmit="return false;" method="post">

          <div class="form-group row">
            <label for="trailer-number" class="col-sm-2 col-form-label text-right">Trailer</label>
            <div class="col-sm-5">
              <div class="">
                <input type="text" class="form-control popup-input trailerid" id-display="#trailer-popup-list" type="text" autocomplete="new-password" db-id="" name="" value="" placeholder="Trailer Number">
              </div>
              <div id="trailer-popup-list" class="popup-list mt-3" style="display: none; z-index: 9999"></div>
              <small class="invalid-feedback font-italic">This field cannot be empty.</small>
            </div>
          </div>

          <div class="form-group row">
            <label for="broker-name" class="col-sm-2 col-form-label text-right">Broker</label>
            <div class="col-sm-5">
              <div class="input-group">
                <div class="input-group-prepend" id="btnQuickAddBroker" role="button" data-container="body" data-toggle="popover">
                  <span class="input-group-text" id="addBrokerotf" ><i class="fas fa-plus"></i></span>
                </div>
                <input type="text" class="form-control popup-input selected-broker" id-display="#broker-popup-list" aria-describedby="addBrokerotf" id="brokerName" type="text" autocomplete="new-password" db-id="" name="" value="" placeholder="Broker Name">
              </div>
              <div id="broker-popup-list" class="popup-list mt-3" style="display: none; z-index: 9999"></div>
              <small class="invalid-feedback">This field cannot be empty.</small>
            </div>
          </div>

          <div class="form-group row">
            <label for="broker-reference" class="col-sm-2 col-form-label text-right">Reference</label>
            <div class="col-sm-5">
              <input type="text" class="form-control broker-reference" name="broker-reference" value="" placeholder="Broker Reference">
              <small class="invalid-feedback">This field cannot be empty.</small>
            </div>
          </div>

          <div class="form-group row">
            <label for="trip-rate" class="col-sm-2 col-form-label text-right">Rate</label>
            <div class="col-sm-5">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                </div>
                <input type="number" class="form-control trip-rate" name="trip-rate" value="" placeholder="Trip Rate">
              </div>
              <small class="invalid-feedback">This field cannot be empty.</small>
            </div>
          </div>

        </form>
      </div>
      <div class="tab-pane fade" id="lh-details-pane" role="tabpanel" aria-labelledby="lh-details-tab">
        <div class="m-2">
          <button type="button" class="btn btn-outline-primary" id="add-location" name="button">Add Location</button>
        </div>
        <table class="table border-bottom">
          <thead>
            <tr>
              <th></th>
              <th>O</th>
              <th>D</th>
              <th>Location</th>
              <th>Mov. Type</th>
              <th>EAL <span data-toggle="tooltip" data-placement="top" title="Empty As Loaded"><i class="far fa-question-circle"></i></span></th>
              <th>Appointment</th>
              <th style="width: 100px">Miles</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="movement-details">
            <tr id="tr-1">
              <td><i class="fas fa-sort"></i></td>
              <td>
                <div class="form-check">
                  <input class="form-check-input position-static" type="radio" name="origin-flag" value="1">
                </div>
              </td>
              <td>
                <div class="form-check">
                  <input class="form-check-input position-static" type="radio" name="destination-flag" value="1">
                </div>
              </td>
              <td class="w-25"> <input type="text" class="form-control form-control-sm google-location-input" id="gi-1" name="" value=""> </td>
              <td class="mov-type-td">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="mov-type-tr-1" value="E">
                  <label class="form-check-label" for="inlineRadio1">E</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="mov-type-tr-1" value="L">
                  <label class="form-check-label" for="inlineRadio1">L</label>
                </div>
              </td>
              <td>
                <select class="form-control form-control-sm" name="eal">
                  <!-- <option value="">Yes/No</option> -->
                  <option value="Yes">Yes</option>
                  <option value="No" selected>No</option>
                </select>
              </td>
              <td class="appt-td">
                <div class="form-group m-0 form-inline">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="na-appt" value="NA">
                    <label class="form-check-label" for="na-appt">N/A</label>
                  </div>
                  <input type="date" class="form-control form-control-sm appt" name="appt-date" value="">
                  <div class="">
                    <select class="form-control form-control-sm ml-1 appt" name="appt-from-hour">
                      <option value="">Hrs</option>
                      <?php for ($i=1; $i < 25; $i++) {
                        ?>
                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                        <?php
                      } ?>
                    </select>
                    :
                    <select class="form-control form-control-sm appt" name="appt-from-min">
                      <option value="">Mins</option>
                      <option value="00">00</option>
                      <?php for ($i=1; $i < 12; $i++) {
                        ?>
                        <option value="<?php echo $i * 5 ?>"><?php echo $i * 5 ?></option>
                        <?php
                      } ?>
                    </select>
                    -
                    <select class="form-control form-control-sm appt" name="appt-to-hour">
                      <option value="">Hrs</option>
                      <?php for ($i=1; $i < 25; $i++) {
                        ?>
                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                        <?php
                      } ?>
                    </select>
                    :
                    <select class="form-control form-control-sm appt" name="appt-to-min">
                      <option value="">Mins</option>
                      <option value="00">00</option>
                      <?php for ($i=1; $i < 12; $i++) {
                        ?>
                        <option value="<?php echo $i * 5 ?>"><?php echo $i * 5 ?></option>
                        <?php
                      } ?>
                    </select>
                  </div>
                </div>
              </td>
              <td>
                <div class="form-control form-control-sm readonly distance" value="">
                </div>
                <!-- <input type="text" class="form-control form-control-sm readonly distance" readonly name="" value=""> -->
              </td>
              <td><i class="fas fa-times text-danger remove-row"></i></td>
            </tr>
            <tr id="tr-2">
              <td><i class="fas fa-sort"></i></td>
              <td>
                <div class="form-check">
                  <input class="form-check-input position-static" type="radio" name="origin-flag" value="1">
                </div>
              </td>
              <td>
                <div class="form-check">
                  <input class="form-check-input position-static" type="radio" name="destination-flag" value="1">
                </div>
              </td>
              <td> <input type="text" class="form-control form-control-sm google-location-input" id="gi-2" name="" value=""> </td>
              <td class="mov-type-td">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="mov-type-tr-2" value="E">
                  <label class="form-check-label" for="inlineRadio1">E</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="mov-type-tr-2" value="L">
                  <label class="form-check-label" for="inlineRadio1">L</label>
                </div>
              </td>
              <td>
                <select class="form-control form-control-sm" name="eal">
                  <!-- <option value="">Yes/No</option> -->
                  <option value="Yes">Yes</option>
                  <option value="No" selected>No</option>
                </select>
              </td>
              <td class="appt-td">
                <div class="form-group m-0 form-inline">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="na-appt" value="NA">
                    <label class="form-check-label" for="na-appt">N/A</label>
                  </div>
                  <input type="date" class="form-control form-control-sm appt" name="appt-date" value="">
                  <div class="">
                    <select class="form-control form-control-sm ml-1 appt" name="appt-from-hour">
                      <option value="">Hrs</option>
                      <?php for ($i=1; $i < 25; $i++) {
                        ?>
                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                        <?php
                      } ?>
                    </select>
                    :
                    <select class="form-control form-control-sm appt" name="appt-from-min">
                      <option value="">Mins</option>
                      <option value="00">00</option>
                      <?php for ($i=1; $i < 12; $i++) {
                        ?>
                        <option value="<?php echo $i * 5 ?>"><?php echo $i * 5 ?></option>
                        <?php
                      } ?>
                    </select>
                    -
                    <select class="form-control form-control-sm appt" name="appt-to-hour">
                      <option value="">Hrs</option>
                      <?php for ($i=1; $i < 25; $i++) {
                        ?>
                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                        <?php
                      } ?>
                    </select>
                    :
                    <select class="form-control form-control-sm appt" name="appt-to-min">
                      <option value="">Mins</option>
                      <option value="00">00</option>
                      <?php for ($i=1; $i < 12; $i++) {
                        ?>
                        <option value="<?php echo $i * 5 ?>"><?php echo $i * 5 ?></option>
                        <?php
                      } ?>
                    </select>
                  </div>
                </div>
              </td>
              <td>
                <div class="form-control form-control-sm readonly distance" value=""></div>
                <!-- <input type="text" class="form-control form-control-sm readonly distance" readonly name="" value=""> -->
              </td>
              <td><i class="fas fa-times text-danger remove-row"></i></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="tab-pane fade" id="conveyance-details-pane" role="tabpanel" aria-labelledby="conveyance-details-tab">
        <form onsubmit="false">
          <div class="form-group row">
            <label for="" class="col-form-label col-sm-2 text-right">Tractor</label>
            <div class="col-sm-5">
              <input type="text" class="form-control popup-input truckid" id-display="#truck-popup-list-modal" type="text" autocomplete="new-password" db-id="" name="" value="" placeholder="Tractor Number">
              <div class="invalid-feedback font-italic"></div>
              <div id="truck-popup-list-modal" class="popup-list mt-3" style="display: none; z-index: 9999">
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label for="" class="col-form-label col-sm-2 text-right">Driver(s)</label>
            <div class="col-sm-5">
              <input type="text" class="form-control popup-input driverid" id-display="#driver-popup-list-modal" type="text" autocomplete="new-password" db-id="" name="" value="" placeholder="Type Driver Name">
              <div id="driver-popup-list-modal"  target="#listed-drivers" type="multiple" class="popup-list mt-3" style="display: none; z-index: 9999"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-5 offset-2" id="listed-drivers">
            </div>
          </div>
        </form>
      </div>
      <div class="tab-pane fade" id="trip-confirmation-pane" role="tabpanel" aria-labelledby="trip-confirmation-tab">
        <form onsubmit="return false">
          <div class="trip-contents">
            <div class="row">
              <div class="col-sm-2 offset-1 text-right">
                Truck
              </div>
              <div class="col-sm-7 grey-font">
                <p class="d-inline"><span class="confirm-truck-number"></span></p>
                <p class="d-inline font-weight-light font-italic">(<span class="confirm-truck-plates"></span>)</p>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-2 offset-1 text-right">
                Trailer
              </div>
              <div class="col-sm-7 grey-font">
                <p class="d-inline"><span class="confirm-trailer-number"></span></p>
                <p class="d-inline font-weight-light font-italic">(<span class="confirm-trailer-plates"></span>)</p>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-2 offset-1 text-right">
                Driver(s)
              </div>
              <div class="col-sm-7 mb-2 grey-font confirm-driver-list">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-2 offset-1 text-right">
                Route
              </div>
              <div class="col-sm-8 grey-font" id="movement-confirmation"> <!-- id="movement-confirmation" -->
              </div>
            </div>
            <div class="row">
              <div class="col-sm-2 offset-1 text-right">
                Miles
              </div>
              <div class="col-sm-7 grey-font">
                <p class="d-inline"><span class="total-miles"></span></p>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-2 offset-1 text-right">
                Rate
              </div>
              <div class="col-sm-7 grey-font">
                <p class="d-inline">$ <span class="trip-rate-confirmation"></span></p>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-sm-2 offset-1 text-right">
                RPM
              </div>
              <div class="col-sm-7 grey-font">
                <p class="d-inline">$ <span class="rpm-confirmation"></span></p>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-2 offset-1 text-right">
                Broker
              </div>
              <div class="col-sm-7 grey-font">
                <p class="d-inline brokerid-confirmation"></p>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-2 offset-1 text-right">
                Reference
              </div>
              <div class="col-sm-7 grey-font">
                <p class="d-inline broker-reference-confirmation"></p>
              </div>
            </div>

          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-3 offset-7">
        <div class="d-flex justify-content-end">
          <div class="next-pane-buttons">
            <button type="button" class="btn btn-primary next-pane">Next</button>
          </div>
          <div class="add-trip-buttons" style="display: none">
            <button type="button" class="btn btn-primary add-trip next-pane">Add Trip</button>
          </div>
          <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>



  </body>
 </html>
<?php
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>
 <script src="/plsuite/Resources/jquery_ui_1_12_1/jquery-ui.min.js" charset="utf-8"></script>
 <script src="js/addTrip.js" charset="utf-8"></script>
 <!-- <script src="js/tripDetails.js" charset="utf-8"></script> -->
