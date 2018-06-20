<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';

$dash_active = "";
$viajes_active = "active";
$operadores_active = "";
$tractores_active = "";
$cajas_active = "";

$data = array(
  'input_f' => date('Y-m-d', strtotime('last week monday')),
  'input_t' => date('Y-m-d', strtotime('last week sunday')),
  'from' => date('Y-m-d', strtotime('last week monday')) . " 00:00",
  'to' => date('Y-m-d', strtotime('last week sunday')) . " 23:59",
  'txt' => ""
);

$bind_values = array();

if (isset($_GET['submit'])) {
  if ($_GET['cTripsTxt'] != "") {
    $data = array(
      'input_f'=>"",
      'input_t'=>"",
      'from' => "",
      'to' => "",
      'txt' => "%" . $_GET['cTripsTxt'] . "%"
    );
    $where = '(b.brokerName LIKE ? OR t.trailer_number LIKE ? OR tl.pk_linehaul_number LIKE ? OR t.pkid_trip LIKE ? OR tl.broker_reference LIKE ? OR tr.truckNumber LIKE ?)';
    $params = "ssssss";
    $bind_values[] =& $params;
    for ($i=0; $i <= 5; $i++) {
      $bind_values[] =& $data['txt'];
    }
  } else {
    $data = array(
      'input_f' => date('Y-m-d', strtotime($_GET['cTripsFrom'])),
      'input_t' => date('Y-m-d', strtotime($_GET['cTripsTo'])),
      'from' => date('Y-m-d', strtotime($_GET['cTripsFrom'])) . " 00:00",
      'to' => date('Y-m-d', strtotime($_GET['cTripsTo'])) . " 23:59",
      'txt' => ""
    );
    $where = '(tl.date_arrival >= ? AND tl.date_arrival <= ?)';
    $params = "ss";
    $bind_values[] =& $params;
    $bind_values[] =& $data['from'];
    $bind_values[] =& $data['to'];
  }
} else {
  $where = 'tl.date_arrival BETWEEN ? AND ?';
  $params = "ss";
  $bind_values[] =& $params;
  $bind_values[] =& $data['from'];
  $bind_values[] =& $data['to'];
}


echo "<link rel='stylesheet' href='/plsuite/Resources/CSS/trips.css'>";
require $root . '/plsuite/Resources/PHP/Utilities/header.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$query = "SELECT t.trip_year AS TripYear , t.pkid_trip AS idTrip , t.trip_status AS status , t.date_open AS DateOpen , t.trailer_number AS TrailerNumber , tl.pk_linehaul_number AS linehaul_number , tl.origin_city AS OriginCity , tl.origin_state AS OriginState , tl.destination_city AS DestinationCity , tl.destination_state AS DestinationState , b.brokerName AS broker , tl.linehaul_status AS lh_status , t.pkid_trip AS tripid , tl.pk_idlinehaul AS linehaulid , max(tlm.pkid_movement) AS idMovement , tl.date_departure date_departure , tl.date_arrival date_arrival , tl.date_delivery date_delivery , SUM( CASE tlm.movement_type WHEN 'E' THEN tlm.miles_google ELSE 0 END) AS empty_miles , SUM( CASE tlm.movement_type WHEN 'L' THEN tlm.miles_google ELSE 0 END) AS loaded_miles ,( SELECT CONCAT(d.nameFirst , ' ' , d.nameLast) FROM ct_trip_linehaul_movement tlm LEFT JOIN ct_drivers d ON tlm.fkid_driver = d.pkid_driver WHERE tlm.fkid_linehaul = tl.pk_idlinehaul ORDER BY tlm.pkid_movement DESC LIMIT 1) last_driver FROM ct_trip t LEFT JOIN ct_trip_linehaul tl ON t.pkid_trip = tl.fk_idtrip LEFT JOIN ct_trip_linehaul_movement tlm ON tlm.fkid_linehaul = tl.pk_idlinehaul LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker LEFT JOIN ct_truck tr ON tr.pkid_truck = tlm.fkid_tractor WHERE $where GROUP BY t.trip_year , t.pkid_trip , tl.pk_idlinehaul";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during trip query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

// $data['txt'] = "%" . $data['txt'] . "%";

call_user_func_array(array($stmt, 'bind_param'), $bind_values);
// $stmt->bind_param('ssssss',
//   $data['from'],
//   $data['to'],
//   $data['txt'],
//   $data['txt'],
//   $data['txt'],
//   $data['txt']
// );
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during trip query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();

if ($rslt->num_rows == 0) {
  $system_callback['query']['code'] = 2;
  $system_callback['query']['message'] = "Script called successfully but there are no rows to display. For trip query.";
  $system_callback['query']['data'] .= $row;
  //exit_script($system_callback);
} else {
    $system_callback['query']['code'] = 1;
  while ($row = $rslt->fetch_assoc()) {
    //$system_callback['trips'][] = $row;
    foreach ($row as $key => $value) {
      $system_callback['rows'][$row['idTrip']][$row['linehaulid']][$key] = $value;
      $system_callback['rows'][$row['idTrip']]['trailer_number'] = $row['TrailerNumber'];
      $system_callback['rows'][$row['idTrip']]['TripYear'] = $row['TripYear'];
      $system_callback['rows'][$row['idTrip']]['idTrip'] = $row['idTrip'];
      $system_callback['rows'][$row['idTrip']]['status'] = $row['status'];
      // $system_callback['rows'][$row['idTrip']]['trip_rate'] = $row['trip_rate'];
    }
  }
}

 ?>
<div class="container-fluid align-items-right justify-content-between d-flex mb-3 position-sticky" style="margin-top: 60px">
  <h1 class="nb-id d-inline text-secondary">Search Trips</h1>
  <form class="form-inline m-0" onsubmit="return false;">
    <div class="form-inline" id="search-inputs">
      <label for="cTripsFrom" class="mr-2">From</label>
      <input type="date" class="form-control form-control-sm mr-2" name="cTripsFrom" id="cTripsFrom" value="<?php echo $data['input_f']?>">
      <label for="cTripsTo" class="mr-2">To</label>
      <input type="date" class="form-control form-control-sm mr-4" name="cTripsTo" id="cTripsTo" value="<?php echo $data['input_t']?>">
      <label for="cTripsTxt" class="mr-2 sr-only">Custom Text to Search</label>
      <input type="text" class="form-control form-control-sm mr-4" name="cTripsTxt" id="cTripsTxt" value="<?php echo str_replace('%', '', $data['txt'])?>" placeholder="Type search term">
      <button type="submit" class="btn btn-outline-success btn-sm" name="submit" id="execute-search">Search</button>
      <button type="button" class="btn btn-outline-secondary btn-sm ml-1" style="display: none" id="open-filter" name="button">Filter Search</button>
    </div>
    <div class="form-inline" id="filter-search" style="display: none">
      <label for="trip-search-filter">Filter</label>
      <input type="text" class="form-control form-control-sm ml-1" id="filter-term" name="" value="">
      <button type="button" class="btn btn-outline-secondary btn-sm ml-1" id="search-again" name="button">Search Again</button>
    </div>
  </form>
  <!-- <button type="submit" class="btn btn-outline-primary m-2" data-toggle="modal" data-target="#trip-filter-modal" name="button"><i class="fas fa-filter"></i></button> -->
</div>

<div class="container-fluid" style="overflow-y: scroll; height: calc(100% - 200px)">
  <table class="table table-striped" id="search-table">
    <tbody id="tripDashTable"></tbody>
  </table>
</div>

<?php
// require 'modales/addTrip.php';
require 'modales/tripFilterModal.php';
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>
 <script src="/plsuite/Resources/JQuery/jquery.table-filterable-min.js" charset="utf-8"></script>
<script src="/plsuite/Ubicaciones/Viajes/js/tripSearch.js" charset="utf-8"></script>
