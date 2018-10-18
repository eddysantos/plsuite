<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';
//require $root . '/plsuite/Resources/PHP/Utilities/header.php';

$trip_id = $_GET['tripid'];
// $tripyear = $_GET['tripyear'];
$disabled_finalized = '';
$show_close_trip = true;
$show_add_linehaul = true;
$last_destination = array('City'=>'', 'State'=>'', 'Zip'=>'');

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

$query = "SELECT t.pkid_trip pkidtrip , t.trip_year tripyear, t.trip_number trip_number, t.trip_status trip_status , t.trailer_number trailer_number, t.trailer_plates trailer_plates, t.date_open date_open , t.date_close date_close , tl.pk_idlinehaul idlh, t.first_movement first_movement, t.last_movement last_movement, sum(( SELECT sum(miles_google) FROM ct_trip_linehaul_movement tlm WHERE tl.pk_idlinehaul = tlm.fkid_linehaul AND tl.linehaul_status <> 'Cancelled')) total_miles , sum(( SELECT sum(miles_google) FROM ct_trip_linehaul_movement tlm WHERE tlm.fkid_linehaul = tl.pk_idlinehaul AND tlm.movement_type = 'L' AND tl.linehaul_status <> 'Cancelled')) loaded_miles , sum(( SELECT sum(miles_google) FROM ct_trip_linehaul_movement tlm WHERE tlm.fkid_linehaul = tl.pk_idlinehaul AND tlm.movement_type = 'E' AND tl.linehaul_status <> 'Cancelled')) empty_miles , SUM( IF( tl.linehaul_status <> 'Cancelled' , tl.trip_rate , 0)) total_rate FROM ct_trip t LEFT JOIN ct_trip_linehaul tl ON t.pkid_trip = tl.fk_idtrip WHERE pkid_trip = ? GROUP BY pkid_trip ORDER BY t.pkid_trip DESC";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during trip fetch query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('s', $trip_id);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during trip fetch variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during trip fetch query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();

if ($rslt->num_rows == 0) {
  $system_callback['query']['code'] = 2;
  $system_callback['query']['message'] = "Script called successfully but there are no rows to display. For trailer query.";
  exit_script($system_callback);
} else {
  $trip = $rslt->fetch_assoc();
  $system_callback['trailer']['data']['trailerNumber'] = $trip['trailerNumber'];
  $system_callback['trailer']['data']['trailerPlates'] = $trip['trailerPlates'];
}

if ($trip['trip_status'] == 'Closed') {
  $dis_trip = 'disabled';
  $show_add_linehaul = false;
  $show_close_trip = false;
} else {
  $dis_trip = '';
}

$query = "SELECT tl.pk_idlinehaul AS pk_idlinehaul , tl.fk_tripyear AS trip_year , tl.linehaul_status AS STATUS , tl.origin_city AS origin_city , tl.origin_state AS origin_state , tl.origin_zip AS origin_zip , tl.destination_city AS destination_city , tl.destination_state AS destination_state , tl.destination_zip AS destination_zip , tl.trip_rate AS trip_rate , tl.broker_reference AS broker_reference , tl.fkid_broker AS brokerid , b.brokerName AS trip_brokerName , tl.rpm AS rpm , sum(tlm.miles_google) AS total_miles , SUM( CASE tlm.movement_type WHEN 'E' THEN tlm.miles_google ELSE 0 END) AS empty_miles , SUM( CASE tlm.movement_type WHEN 'L' THEN tlm.miles_google ELSE 0 END) AS loaded_miles , date_departure AS departure , date_arrival AS arrival , date_delivery AS delivery , date_appointment AS appointment , pk_linehaul_number AS lh_number ,( SELECT concat(nameFirst , ' ' , nameLast) FROM ct_drivers WHERE pkid_driver = tlm.fkid_driver) driver ,( SELECT concat(truckNumber) FROM ct_truck WHERE pkid_truck = tlm.fkid_tractor) tractor FROM ct_trip_linehaul tl LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker LEFT JOIN ct_trip_linehaul_movement tlm ON tlm.fkid_linehaul = tl.pk_idlinehaul WHERE tl.fk_idtrip = ? GROUP BY pk_idlinehaul";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during TRIP_LINEHAUL query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('s', $trip_id);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during TRIP_LINEHAUL variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during TRIP_LINEHAUL query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();
$linehauls = array();

if ($rslt->num_rows == 0) {
  $system_callback['query']['code'] = 2;
  $system_callback['query']['message'] = "Script called successfully but there are no rows to display. For trailer query.";
} else {
  while ($row = $rslt->fetch_assoc()) {
    if ($row['status'] == 'Closed' || $row['status'] == 'Cancelled' ) {
      $disabled_finalized = '';
    } else {
      $show_close_trip = false;
      $disabled_finalized = 'disabled';
    }
    $linehauls[] = $row;
  }
}

foreach ($linehauls as $key => $value) {
  $linehauls[$key]['departure'] = parseDate($linehauls[$key]['departure']);
  $linehauls[$key]['arrival'] = parseDate($linehauls[$key]['arrival']);
  $linehauls[$key]['appointment'] = parseDate($linehauls[$key]['appointment']);
  $linehauls[$key]['delivery'] = parseDate($linehauls[$key]['delivery']);
}

$lastEl = array_values(array_slice($linehauls, -1))[0];

/* GET LAST DESTINATION FROM TRIP */

if ($trip['last_movement']) {
  $query = "SELECT destination_city city, destination_state state, destination_zip zip FROM ct_trip_linehaul_movement WHERE pkid_movement = $trip[last_movement]";
  $stmt = $db->query($query);
  $last_destination = $stmt->fetch_assoc();
}


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
     <link rel="stylesheet" href="/plsuite/Resources/alertify/css/themes/bootstrap.min.css">
     <script src="/plsuite/Resources/fa_5/js/fontawesome-all.js"></script>
     <link rel="stylesheet" media="screen and (max-device-width: 700px)" href="/plsuite/Resources/CSS/mainMobile.css">
     <title>Prolog Transportation Inc</title>
   </head>
  <body style="min-height:100%">
  <input type="text" name="" id="trip-identifier" value="<?php echo $trip_id?>" trip-number="<?php echo $trip['trip_number']?>" hidden>

   <div class="" id="trip-information" >
    <header id="trip-header"> <!-- This header appears for the trip information -->
       <div class="custom-header">
         <div class="custom-header-bar">&nbsp;</div>
         <div class="">
           <a class="ml-3 mr-5" role="button" id="backToDash" href="javascript:history.back()"><i class="fa fa-chevron-left"></i></a>
           <div class="w-100 d-flex align-items-center justify-content-between">
             <div class="pr-5">
               Trip Status - <span class="trip-status" id="trip_status"></span>
             </div>
             <div class="">
               <i class="fa fa-circle mr-2 trip" id="set-trip-status-button"></i> <!-- Agregar clase para status.-->
               <?php if ($show_close_trip): ?>
                 <button class="btn btn-outline-primary form-control mr-3 finalizeRecord" type-of-record="trip" action="Closed" tripyear="<?php echo $trip['tripyear']?>" recordid="<?php echo $trip['pkidtrip']?>" type="button" id="btnCloseTrip" name="button">
                   <i class="fa fa-check"></i> Close Trip
                 </button>
               <?php endif; ?>
               <?php if ($show_add_linehaul): ?>
                 <button class="btn btn-outline-success form-control mr-3" name="button" data-toggle="modal" data-focus="false" data-target="#addLinehaulModal">
                   <i class="fa fa-plus"></i> Add Linehaul
                 </button>
               <?php endif; ?>
             </div>
           </div>
         </div>
       </div>
    </header>
    <div class="container-fluid grey-font mt-3" id="trip-summary"> <!-- This content appears to show the trip information.-->
     <div class="row">
       <div class="col-lg-3">
         <div class="row">
           <div class="col-lg-4">
             <p class="text-dark" >Trailer:</p>
           </div>
           <div class="col-lg-6">
             <p id="trailer_number"></p>
             <p id="trailer_plates" hidden></p>
             <p id="id_trailer" hidden></p>
           </div>
         </div>
         <div class="row">
           <div class="col-lg-4">
             <p class="text-dark mb-0">Opened:</p>
           </div>
           <div class="col-lg-6">
             <p class="mb-0" id="date_open"></p>
           </div>
         </div>
         <div class="row">
           <div class="col-lg-4">
             <p class="text-dark">Closed:</p>
           </div>
           <div class="col-lg-6">
             <p class="" id="date_close"></p>
           </div>
         </div>
         <div class="row">
           <div class="col-lg-4">
             <p class="text-dark mb-0">Total Miles:</p>
           </div>
           <div class="col-lg-6">
             <p class="mb-0" id="total_miles"></p>
           </div>
         </div>
         <div class="row">
           <div class="col-lg-4">
             <p class="text-dark mb-0">Total Rate:</p>
           </div>
           <div class="col-lg-6">
             <p class="mb-0" id="total_rate"></p>
           </div>
         </div>
         <div class="row">
           <div class="col-lg-4">
             <p class="text-dark mb-0">RPM:</p>
           </div>
           <div class="col-lg-6">
             <p class="mb-0" id="rpm"></p>
           </div>
         </div>
       </div>
       <div class="col-lg-9">
         <table class="table table-striped text-dark border">
           <tbody id="summary-dash">

           </tbody>
         </table>
       </div>
     </div>
   </div>
  </div>

  <div class="" id="linehaul-information" style="display: none">
     <header id="lh-header">
       <div class="custom-header">
         <div class="custom-header-bar">&nbsp;</div>
         <div class="">
           <a class="ml-3 mr-5" role="button" id="show-trip"><i class="fa fa-chevron-left"></i></a>
           <div class="w-100 d-flex align-items-center justify-content-between">
             <div class="pr-5">
               Linehaul - <span class="lh_number"></span>
             </div>
             <div class="">
               <i class="fa fa-circle mr-2 lh-status-button"></i>

               <button type="button" class="btn btn-outline-primary finalizeRecord linehaul closelh mr-3" name="button" data-toggle="tooltip" data-placement="top" title="Close Trip" type-of-record="linehaul" action="Closed" tripyear="<?php echo $trip['tripyear']?>"><i class="far fa-check-circle"></i>Close Linehaul</button>

             </div>
           </div>
         </div>
       </div>
     </header>
     <!-- <div class="container-fluid">
       <div class="alert linehaulSavedNotice text-center mt-3" role="alert" style="display: none"></div>
     </div> -->

     <div class="main-details-container">
       <div class="row div-100h">
         <div class="col-sm-2 ml-0 pl-0 border border-bottom-0 border-left-0 border-top-0 ml-0 pl-0 pr-0">
           <nav class="nav flex-column nav-30-px" id="lh-details-tablist" role="tablist">
             <a class="nav-link dash side-panel active" id="lh-details-tab" data-toggle="tab" role="tab" aria-selected="true" aria-controls="lh-details" href="#lh-details-pane">
               Linehaul Details
             </a>
             <a class="nav-link dash side-panel" id="lh-movs-tab" data-toggle="tab" role="tab" aria-selected="false" aria-controls="lh-movs-dash" href="#lh-movs-pane">
               Movements
             </a>
             <a class="nav-link disabled dash side-panel" id="lh-expenses-tab" data-toggle="tab" role="tab" aria-selected="false" aria-controls="lh-expenses-dash" href="#lh-expenses-pane">
               Expenses
             </a>
             <a class="nav-link disabled dash side-panel " id="lh-dispatch-log-tab" data-toggle="tab" role="tab" aria-selected="false" aria-controls="lh-dispatch-log-tab" href="#lh-dispatch-log-pane" disabled="true">
               Dispatch Communications
             </a>
           </nav>
         </div>
         <div class="col-sm-10 tab-info">
           <div class="tab-content p-1" id="linehaul-details-panes">
             <div class="tab-pane fade show active" id="lh-details-pane" role="tab-panel" aria-labelledby="lh-details-tab">
               <div class="row">
                 <div class="col-lg-10 offset-1">
                   <form class="form-group" id="lh-details-form">
                     <fieldset id="lh-fields">
                       <input type="text" class="linehaulid" id="linehaulid" name="" value="" hidden>
                       <input type="text" class="lh_status" name="" value="" hidden>
                       <div class="form-row">
                         <label for="" class="col-form-label col-md-2">Trailer</label>
                         <div class="form-group col-md-6">
                           <input type="text" class="form-control trailer_number readonly" readonly name="" value="">
                         </div>
                       </div>
                       <div class="form-row">
                         <label for="" class="col-form-label col-md-2">Broker</label>
                         <div class="form-group col-md-6">
                           <input type="text" class="form-control broker popup-input" id-display="#broker-popup-list-lh-details" db-id="" name="" value="">
                           <div id="broker-popup-list-lh-details" class="popup-list mt-2" style="display: none; z-index: 9999">
                           </div>
                         </div>
                         <label for="" class="col-form-label col-md-2 text-right">Reference</label>
                         <div class="form-group col-md-2">
                           <input type="text" class="form-control broker_reference" name="" value="">
                         </div>
                       </div>

                       <div class="form-row">
                         <label for="" class="col-form-label col-md-2">Origin</label>
                         <div class="form-group col-md-2">
                           <input type="text" class="form-control origin_zip disabled" name="" value="" readonly>
                         </div>
                         <div class="form-group col-md-1">
                           <input type="text" class="form-control origin_state disabled" name="" value="" readonly>
                         </div>
                         <div class="form-group col-md-3">
                           <input type="text" class="form-control origin_city disabled" name="" value="" readonly>
                         </div>
                         <label for="" class="col-form-label col-md-2 text-right">Trip Rate</label>
                         <div class="form-group col-md-2">
                           <div class="input-group">
                             <div class="input-group-addon p-0">
                               <span class="input-group-text">$</span>
                             </div>
                             <input type="text" class="form-control rate" name="" value="">
                           </div>
                         </div>
                       </div>

                       <div class="form-row">
                         <label for="" class="col-form-label col-md-2">Destination</label>
                         <div class="form-group col-md-2">
                           <input type="text" class="form-control destination_zip readonly" name="" value="" disabled>
                         </div>
                         <div class="form-group col-md-1">
                           <input type="text" class="form-control destination_state readonly" name="" value="" disabled>
                         </div>
                         <div class="form-group col-md-3">
                           <input type="text" class="form-control destination_city readonly" name="" value="" disabled>
                         </div>
                         <label for="" class="col-form-label col-md-2 text-right ">Empty Miles</label>
                         <div class="form-group col-md-2">
                           <input type="text" class="form-control empty_miles disabled" name="" value="" readonly>
                         </div>
                       </div>

                       <div class="form-row">
                         <label for="" class="col-form-label col-md-2">Appointment</label>
                         <div class="form-group col-md-4">
                           <input type="date" class="form-control appointment date" name="" value="">
                         </div>
                         <div class="form-group col-md-1">
                           <select class="form-control appointment hour" id="appointment_time_hour" name="appointment_time_hour">
                             <option value="">Hr</option>
                             <option value="01">01</option>
                             <option value="02">02</option>
                             <option value="03">03</option>
                             <option value="04">04</option>
                             <option value="05">05</option>
                             <option value="06">06</option>
                             <option value="07">07</option>
                             <option value="08">08</option>
                             <option value="09">09</option>
                             <option value="10">10</option>
                             <option value="11">11</option>
                             <option value="12">12</option>
                             <option value="13">13</option>
                             <option value="14">14</option>
                             <option value="15">15</option>
                             <option value="16">16</option>
                             <option value="17">17</option>
                             <option value="18">18</option>
                             <option value="19">19</option>
                             <option value="20">20</option>
                             <option value="21">21</option>
                             <option value="22">22</option>
                             <option value="23">23</option>
                             <option value="24">24</option>
                           </select>
                         </div>
                         <div class="form-group col-md-1 pl-0">
                           <select class="form-control appointment minute" id="appointment_time_minute" name="appointment_time_minute">
                             <option value="">Min</option>
                             <option value="00">00</option>
                             <option value="05">05</option>
                             <option value="10">10</option>
                             <option value="15">15</option>
                             <option value="20">20</option>
                             <option value="25">25</option>
                             <option value="30">30</option>
                             <option value="35">35</option>
                             <option value="40">40</option>
                             <option value="45">45</option>
                             <option value="50">50</option>
                             <option value="55">55</option>
                           </select>
                         </div>
                         <label for="" class="col-form-label col-md-2 text-right ">Loaded Miles</label>
                         <div class="form-group col-md-2">
                           <input type="text" class="form-control loaded_miles disabled" name="" value="" readonly>
                         </div>
                       </div>

                       <div class="form-row">
                         <label for="" class="col-form-label col-md-2">Departure</label>
                         <div class="form-group col-md-4">
                           <input type="date" class="form-control departure date" name="" value="">
                         </div>
                         <div class="form-group col-md-1">
                           <select class="form-control departure hour" id="departure_time_hour" name="departure_time_hour">
                             <option value="">Hr</option>
                             <option value="01">01</option>
                             <option value="02">02</option>
                             <option value="03">03</option>
                             <option value="04">04</option>
                             <option value="05">05</option>
                             <option value="06">06</option>
                             <option value="07">07</option>
                             <option value="08">08</option>
                             <option value="09">09</option>
                             <option value="10">10</option>
                             <option value="11">11</option>
                             <option value="12">12</option>
                             <option value="13">13</option>
                             <option value="14">14</option>
                             <option value="15">15</option>
                             <option value="16">16</option>
                             <option value="17">17</option>
                             <option value="18">18</option>
                             <option value="19">19</option>
                             <option value="20">20</option>
                             <option value="21">21</option>
                             <option value="22">22</option>
                             <option value="23">23</option>
                             <option value="24">24</option>
                           </select>
                         </div>
                         <div class="form-group col-md-1 pl-0">
                           <select class="form-control departure minute" id="departure_time_minute" name="departure_time_minute">
                             <option value="">Min</option>
                             <option value="00">00</option>
                             <option value="05">05</option>
                             <option value="10">10</option>
                             <option value="15">15</option>
                             <option value="20">20</option>
                             <option value="25">25</option>
                             <option value="30">30</option>
                             <option value="35">35</option>
                             <option value="40">40</option>
                             <option value="45">45</option>
                             <option value="50">50</option>
                             <option value="55">55</option>
                           </select>
                         </div>
                         <label for="" class="col-form-label col-md-2 text-right ">Total Miles</label>
                         <div class="form-group col-md-2">
                           <input type="text" class="form-control total_miles disabled" name="" value="" readonly>
                         </div>
                       </div>

                       <div class="form-row">
                         <label for="" class="col-form-label col-md-2">Arrival</label>
                         <div class="form-group col-md-4">
                           <input type="date" class="form-control arrival date" id="arrival_time" name="" value="">
                         </div>
                         <div class="form-group col-md-1">
                           <select class="form-control arrival hour" id="arrival_time_hour" name="arrival_time_hour">
                             <option value="">Hr</option>
                             <option value="01">01</option>
                             <option value="02">02</option>
                             <option value="03">03</option>
                             <option value="04">04</option>
                             <option value="05">05</option>
                             <option value="06">06</option>
                             <option value="07">07</option>
                             <option value="08">08</option>
                             <option value="09">09</option>
                             <option value="10">10</option>
                             <option value="11">11</option>
                             <option value="12">12</option>
                             <option value="13">13</option>
                             <option value="14">14</option>
                             <option value="15">15</option>
                             <option value="16">16</option>
                             <option value="17">17</option>
                             <option value="18">18</option>
                             <option value="19">19</option>
                             <option value="20">20</option>
                             <option value="21">21</option>
                             <option value="22">22</option>
                             <option value="23">23</option>
                             <option value="24">24</option>
                           </select>
                         </div>
                         <div class="form-group col-md-1 pl-0">
                           <select class="form-control arrival minute" id="arrival_time_minute" name="arrival_time_minute">
                             <option value="">Min</option>
                             <option value="00">00</option>
                             <option value="05">05</option>
                             <option value="10">10</option>
                             <option value="15">15</option>
                             <option value="20">20</option>
                             <option value="25">25</option>
                             <option value="30">30</option>
                             <option value="35">35</option>
                             <option value="40">40</option>
                             <option value="45">45</option>
                             <option value="50">50</option>
                             <option value="55">55</option>
                           </select>
                         </div>
                         <label for="" class="col-form-label col-md-2 text-right">RPM</label>
                         <div class="form-group col-md-2">
                           <div class="input-group">
                             <div class="input-group-addon p-0">
                               <span class="input-group-text">$</span>
                             </div>
                             <input type="text" class="form-control rpm disabled" name="" value="" readonly>
                           </div>
                         </div>
                       </div>


                       <div class="form-row">
                         <label for="" class="col-form-label col-md-2">Delivery</label>
                         <div class="form-group col-md-4">
                           <input type="date" class="form-control delivery date" id="delivery_time" name="" value="">
                         </div>
                         <div class="form-group col-md-1">
                           <select class="form-control delivery hour" id="delivery_time_hour" name="delivery_time_hour">
                             <option value="">Hr</option>
                             <option value="01">01</option>
                             <option value="02">02</option>
                             <option value="03">03</option>
                             <option value="04">04</option>
                             <option value="05">05</option>
                             <option value="06">06</option>
                             <option value="07">07</option>
                             <option value="08">08</option>
                             <option value="09">09</option>
                             <option value="10">10</option>
                             <option value="11">11</option>
                             <option value="12">12</option>
                             <option value="13">13</option>
                             <option value="14">14</option>
                             <option value="15">15</option>
                             <option value="16">16</option>
                             <option value="17">17</option>
                             <option value="18">18</option>
                             <option value="19">19</option>
                             <option value="20">20</option>
                             <option value="21">21</option>
                             <option value="22">22</option>
                             <option value="23">23</option>
                             <option value="24">24</option>
                           </select>
                         </div>
                         <div class="form-group col-md-1 pl-0">
                           <select class="form-control delivery minute" id="delivery_time_minute" name="delivery_time_minute">
                             <option value="">Min</option>
                             <option value="00">00</option>
                             <option value="05">05</option>
                             <option value="10">10</option>
                             <option value="15">15</option>
                             <option value="20">20</option>
                             <option value="25">25</option>
                             <option value="30">30</option>
                             <option value="35">35</option>
                             <option value="40">40</option>
                             <option value="45">45</option>
                             <option value="50">50</option>
                             <option value="55">55</option>
                           </select>
                         </div>

                       </div>
                       <div class="form-row">
                         <label for="" class="col-form-label col-md-2">Comments</label>
                         <div class="col-md-10">
                           <textarea name="" id="lh_comments" class="form-control lh_comment" rows="8"></textarea>
                         </div>
                       </div>

                     </fieldset>
                   </form>
                 </div>
                 <div class="col-lg-1">

                   <div class="" id="lh-edit-enabled">

                     <div class="row" style="display: none" id="cancel-editing">
                       <div class="col-12">
                         <div class="form-group">
                           <button type="button" class="btn btn-outline-secondary" id="cancel-editing-button" name="button" data-container="body" data-toggle="tooltip" data-placement="top" title="Disable Editing"><i class="fas fa-undo-alt"></i></button>
                         </div>
                       </div>
                     </div>

                     <div class="row">
                       <div class="col-12">
                         <div class="form-group">
                           <button type="button" class="btn btn-danger finalizeRecord linehaul" form-parent="#lh-details-form" name="button" data-container="body" data-toggle="tooltip" data-placement="top" title="Cancel Trip" type-of-record="linehaul" action="Cancelled" tripyear="<?php echo $trip['tripyear']?>"><i class="fas fa-ban"></i></button>
                         </div>
                       </div>
                     </div>

                     <div class="row">
                       <div class="col-12">
                         <div class="form-group">
                           <button type="button" class="btn btn-outline-success saveLhChanges" tripid="<?php echo $trip['pkidtrip']?>" tripyear="<?php echo $trip['tripyear']?>" form-parent="#lh-details-form" name="button" data-toggle="tooltip" data-placement="top" title="Save Changes"><i class="far fa-save"></i></button>
                         </div>
                       </div>
                     </div>
                   </div>

                   <div class="" id="lh-edit-disabled" style="display: none">
                     <div class="row">
                       <div class="col-12">
                         <div class="form-group">
                           <button type="button" class="btn btn-outline-secondary" id="enable-editing" name="button" data-container="body" data-toggle="tooltip" data-placement="top" title="Enable Editing"><i class="fas fa-pencil-alt"></i></button>
                         </div>
                       </div>
                     </div>
                   </div>

                 </div>
               </div>
             </div>
             <div class="tab-pane fade" id="lh-movs-pane" role="tab-panel" aria-labelledby="lh-movs-tab">
               <div class="clearfix mt-1 mb-1">
                 <button type="button" class="btn btn-outline-success float-right add-movement" name="button"><i class="fa fa-plus"></i> Add Movement</button>
               </div>
               <table class="table table-striped border text-dark">
                 <tbody id="mov-dash"></tbody>
               </table>
             </div>
           </div>
         </div>
       </div>
     </div>

     <!--div class="container-fluid grey-font mt-3" id="lh-summary">  This content appears to show the linehaul information.
       <div class="row">
         <div class="col-lg-6">
           <form class="form-group" id="lh-details-form">
             <fieldset id="lh-fields">
               <input type="text" class="linehaulid" id="linehaulid" name="" value="" hidden>
               <input type="text" class="lh_status" name="" value="" hidden>
               <div class="form-row">
                 <label for="" class="col-form-label col-md-2">Trailer</label>
                 <div class="form-group col-md-6">
                   <input type="text" class="form-control trailer_number readonly" readonly name="" value="">
                 </div>
               </div>
               <div class="form-row">
                 <label for="" class="col-form-label col-md-2">Broker</label>
                 <div class="form-group col-md-6">
                   <input type="text" class="form-control broker popup-input" id-display="#broker-popup-list-lh-details" db-id="" name="" value="">
                   <div id="broker-popup-list-lh-details" class="popup-list mt-2" style="display: none; z-index: 9999">
                   </div>
                 </div>
                 <label for="" class="col-form-label col-md-2 text-right">Reference</label>
                 <div class="form-group col-md-2">
                   <input type="text" class="form-control broker_reference" name="" value="">
                 </div>
               </div>

               <div class="form-row">
                 <label for="" class="col-form-label col-md-2">Origin</label>
                 <div class="form-group col-md-2">
                   <input type="text" class="form-control origin_zip disabled" name="" value="" readonly>
                 </div>
                 <div class="form-group col-md-1">
                   <input type="text" class="form-control origin_state disabled" name="" value="" readonly>
                 </div>
                 <div class="form-group col-md-3">
                   <input type="text" class="form-control origin_city disabled" name="" value="" readonly>
                 </div>
                 <label for="" class="col-form-label col-md-2 text-right">Trip Rate</label>
                 <div class="form-group col-md-2">
                   <div class="input-group">
                     <div class="input-group-addon p-0">
                       <span class="input-group-text">$</span>
                     </div>
                     <input type="text" class="form-control rate" name="" value="">
                   </div>
                 </div>
               </div>

               <div class="form-row">
                 <label for="" class="col-form-label col-md-2">Destination</label>
                 <div class="form-group col-md-2">
                   <input type="text" class="form-control destination_zip readonly" name="" value="" disabled>
                 </div>
                 <div class="form-group col-md-1">
                   <input type="text" class="form-control destination_state readonly" name="" value="" disabled>
                 </div>
                 <div class="form-group col-md-3">
                   <input type="text" class="form-control destination_city readonly" name="" value="" disabled>
                 </div>
                 <label for="" class="col-form-label col-md-2 text-right ">Empty Miles</label>
                 <div class="form-group col-md-2">
                   <input type="text" class="form-control empty_miles disabled" name="" value="" readonly>
                 </div>
               </div>

               <div class="form-row">
                 <label for="" class="col-form-label col-md-2">Appointment</label>
                 <div class="form-group col-md-4">
                   <input type="date" class="form-control appointment date" name="" value="">
                 </div>
                 <div class="form-group col-md-1">
                   <select class="form-control appointment hour" id="appointment_time_hour" name="appointment_time_hour">
                     <option value="">Hr</option>
                     <option value="01">01</option>
                     <option value="02">02</option>
                     <option value="03">03</option>
                     <option value="04">04</option>
                     <option value="05">05</option>
                     <option value="06">06</option>
                     <option value="07">07</option>
                     <option value="08">08</option>
                     <option value="09">09</option>
                     <option value="10">10</option>
                     <option value="11">11</option>
                     <option value="12">12</option>
                     <option value="13">13</option>
                     <option value="14">14</option>
                     <option value="15">15</option>
                     <option value="16">16</option>
                     <option value="17">17</option>
                     <option value="18">18</option>
                     <option value="19">19</option>
                     <option value="20">20</option>
                     <option value="21">21</option>
                     <option value="22">22</option>
                     <option value="23">23</option>
                     <option value="24">24</option>
                   </select>
                 </div>
                 <div class="form-group col-md-1 pl-0">
                   <select class="form-control appointment minute" id="appointment_time_minute" name="appointment_time_minute">
                     <option value="">Min</option>
                     <option value="00">00</option>
                     <option value="05">05</option>
                     <option value="10">10</option>
                     <option value="15">15</option>
                     <option value="20">20</option>
                     <option value="25">25</option>
                     <option value="30">30</option>
                     <option value="35">35</option>
                     <option value="40">40</option>
                     <option value="45">45</option>
                     <option value="50">50</option>
                     <option value="55">55</option>
                   </select>
                 </div>
                 <label for="" class="col-form-label col-md-2 text-right ">Loaded Miles</label>
                 <div class="form-group col-md-2">
                   <input type="text" class="form-control loaded_miles disabled" name="" value="" readonly>
                 </div>
               </div>

               <div class="form-row">
                 <label for="" class="col-form-label col-md-2">Departure</label>
                 <div class="form-group col-md-4">
                   <input type="date" class="form-control departure date" name="" value="">
                 </div>
                 <div class="form-group col-md-1">
                   <select class="form-control departure hour" id="departure_time_hour" name="departure_time_hour">
                     <option value="">Hr</option>
                     <option value="01">01</option>
                     <option value="02">02</option>
                     <option value="03">03</option>
                     <option value="04">04</option>
                     <option value="05">05</option>
                     <option value="06">06</option>
                     <option value="07">07</option>
                     <option value="08">08</option>
                     <option value="09">09</option>
                     <option value="10">10</option>
                     <option value="11">11</option>
                     <option value="12">12</option>
                     <option value="13">13</option>
                     <option value="14">14</option>
                     <option value="15">15</option>
                     <option value="16">16</option>
                     <option value="17">17</option>
                     <option value="18">18</option>
                     <option value="19">19</option>
                     <option value="20">20</option>
                     <option value="21">21</option>
                     <option value="22">22</option>
                     <option value="23">23</option>
                     <option value="24">24</option>
                   </select>
                 </div>
                 <div class="form-group col-md-1 pl-0">
                   <select class="form-control departure minute" id="departure_time_minute" name="departure_time_minute">
                     <option value="">Min</option>
                     <option value="00">00</option>
                     <option value="05">05</option>
                     <option value="10">10</option>
                     <option value="15">15</option>
                     <option value="20">20</option>
                     <option value="25">25</option>
                     <option value="30">30</option>
                     <option value="35">35</option>
                     <option value="40">40</option>
                     <option value="45">45</option>
                     <option value="50">50</option>
                     <option value="55">55</option>
                   </select>
                 </div>
                 <label for="" class="col-form-label col-md-2 text-right ">Total Miles</label>
                 <div class="form-group col-md-2">
                   <input type="text" class="form-control total_miles disabled" name="" value="" readonly>
                 </div>
               </div>

               <div class="form-row">
                 <label for="" class="col-form-label col-md-2">Arrival</label>
                 <div class="form-group col-md-4">
                   <input type="date" class="form-control arrival date" name="" value="">
                 </div>
                 <div class="form-group col-md-1">
                   <select class="form-control arrival hour" id="arrival_time_hour" name="arrival_time_hour">
                     <option value="">Hr</option>
                     <option value="01">01</option>
                     <option value="02">02</option>
                     <option value="03">03</option>
                     <option value="04">04</option>
                     <option value="05">05</option>
                     <option value="06">06</option>
                     <option value="07">07</option>
                     <option value="08">08</option>
                     <option value="09">09</option>
                     <option value="10">10</option>
                     <option value="11">11</option>
                     <option value="12">12</option>
                     <option value="13">13</option>
                     <option value="14">14</option>
                     <option value="15">15</option>
                     <option value="16">16</option>
                     <option value="17">17</option>
                     <option value="18">18</option>
                     <option value="19">19</option>
                     <option value="20">20</option>
                     <option value="21">21</option>
                     <option value="22">22</option>
                     <option value="23">23</option>
                     <option value="24">24</option>
                   </select>
                 </div>
                 <div class="form-group col-md-1 pl-0">
                   <select class="form-control arrival minute" id="arrival_time_minute" name="arrival_time_minute">
                     <option value="">Min</option>
                     <option value="00">00</option>
                     <option value="05">05</option>
                     <option value="10">10</option>
                     <option value="15">15</option>
                     <option value="20">20</option>
                     <option value="25">25</option>
                     <option value="30">30</option>
                     <option value="35">35</option>
                     <option value="40">40</option>
                     <option value="45">45</option>
                     <option value="50">50</option>
                     <option value="55">55</option>
                   </select>
                 </div>
                 <label for="" class="col-form-label col-md-2 text-right">RPM</label>
                 <div class="form-group col-md-2">
                   <div class="input-group">
                     <div class="input-group-addon p-0">
                       <span class="input-group-text">$</span>
                     </div>
                     <input type="text" class="form-control rpm disabled" name="" value="" readonly>
                   </div>
                 </div>
               </div>


               <div class="form-row">
                 <label for="" class="col-form-label col-md-2">Delivery</label>
                 <div class="form-group col-md-4">
                   <input type="date" class="form-control delivery date" name="" value="">
                 </div>
                 <div class="form-group col-md-1">
                   <select class="form-control delivery hour" id="delivery_time_hour" name="delivery_time_hour">
                     <option value="">Hr</option>
                     <option value="01">01</option>
                     <option value="02">02</option>
                     <option value="03">03</option>
                     <option value="04">04</option>
                     <option value="05">05</option>
                     <option value="06">06</option>
                     <option value="07">07</option>
                     <option value="08">08</option>
                     <option value="09">09</option>
                     <option value="10">10</option>
                     <option value="11">11</option>
                     <option value="12">12</option>
                     <option value="13">13</option>
                     <option value="14">14</option>
                     <option value="15">15</option>
                     <option value="16">16</option>
                     <option value="17">17</option>
                     <option value="18">18</option>
                     <option value="19">19</option>
                     <option value="20">20</option>
                     <option value="21">21</option>
                     <option value="22">22</option>
                     <option value="23">23</option>
                     <option value="24">24</option>
                   </select>
                 </div>
                 <div class="form-group col-md-1 pl-0">
                   <select class="form-control delivery minute" id="delivery_time_minute" name="delivery_time_minute">
                     <option value="">Min</option>
                     <option value="00">00</option>
                     <option value="05">05</option>
                     <option value="10">10</option>
                     <option value="15">15</option>
                     <option value="20">20</option>
                     <option value="25">25</option>
                     <option value="30">30</option>
                     <option value="35">35</option>
                     <option value="40">40</option>
                     <option value="45">45</option>
                     <option value="50">50</option>
                     <option value="55">55</option>
                   </select>
                 </div>

               </div>
               <div class="form-row">
                 <label for="" class="col-form-label col-md-2">Comments</label>
                 <div class="col-md-10">
                   <textarea name="" id="lh_comments" class="form-control lh_comment" rows="8"></textarea>
                 </div>
               </div>

             </fieldset>
           </form>
         </div>
         <div class="col-lg-1">

           <div class="" id="lh-edit-enabled">

             <div class="row" style="display: none" id="cancel-editing">
               <div class="col-12">
                 <div class="form-group">
                   <button type="button" class="btn btn-outline-secondary" id="cancel-editing-button" name="button" data-container="body" data-toggle="tooltip" data-placement="top" title="Disable Editing"><i class="fas fa-undo-alt"></i></button>
                 </div>
               </div>
             </div>

             <div class="row">
               <div class="col-12">
                 <div class="form-group">
                   <button type="button" class="btn btn-danger finalizeRecord linehaul" form-parent="#lh-details-form" name="button" data-container="body" data-toggle="tooltip" data-placement="top" title="Cancel Trip" type-of-record="linehaul" action="Cancelled" tripyear="<?php echo $trip['tripyear']?>"><i class="fas fa-ban"></i></button>
                 </div>
               </div>
             </div>

             <div class="row">
               <div class="col-12">
                 <div class="form-group">
                   <button type="button" class="btn btn-outline-success saveLhChanges" tripid="<?php echo $trip['pkidtrip']?>" tripyear="<?php echo $trip['tripyear']?>" form-parent="#lh-details-form" name="button" data-toggle="tooltip" data-placement="top" title="Save Changes"><i class="far fa-save"></i></button>
                 </div>
               </div>
             </div>
           </div>

           <div class="" id="lh-edit-disabled" style="display: none">
             <div class="row">
               <div class="col-12">
                 <div class="form-group">
                   <button type="button" class="btn btn-outline-secondary" id="enable-editing" name="button" data-container="body" data-toggle="tooltip" data-placement="top" title="Enable Editing"><i class="fas fa-pencil-alt"></i></button>
                 </div>
               </div>
             </div>
           </div>

         </div>
         <div class="col-lg-5">
           <div class="tab-content">
             <div class="tab-pane fade show active" id="lh-movements">
               <div class="clearfix mt-1 mb-1">
                 <button type="button" class="btn btn-outline-success float-right add-movement" name="button"><i class="fa fa-plus"></i> Add Movement</button>
               </div>
               <table class="table table-striped border text-dark">
                 <tbody id="mov-dash"></tbody>
               </table>
             </div>
             <div class="tab-pane fade black-font" id="lh-deductions">
               Here we will include de deductions.
             </div>
           </div>
         </div>
       </div>
    </div> -->
  </div>




  </body>
 </html>
<?php
require 'modales/addLinehaul.php';
require 'modales/addMovement.php';
require 'modales/closeTripConfirmation.php';
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>
 <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> -->
 <script src="/plsuite/Resources/jquery_ui_1_12_1/jquery-ui.min.js" charset="utf-8"></script>
 <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuBCFwHZCWMgyeTJ1MI32sXlGnJtIIsUA" async defer></script>
 <script src="js/tripDetails.js" charset="utf-8"></script>
