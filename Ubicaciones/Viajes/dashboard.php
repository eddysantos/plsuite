<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';

$dash_active = "";
$viajes_active = "active";
$operadores_active = "";
$tractores_active = "";
$cajas_active = "";

echo "<link rel='stylesheet' href='/plsuite/Resources/CSS/trips.css'>";
require $root . '/plsuite/Resources/PHP/Utilities/header.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$query = "SELECT t.trip_year AS TripYear , t.pkid_trip AS idTrip, t.trip_number trip_number,  t.trip_status AS status , t.date_open AS DateOpen , t.trailer_number AS TrailerNumber , tl.origin_city AS OriginCity , tl.origin_state AS OriginState , tl.destination_city AS DestinationCity , tl.destination_state AS DestinationState , b.brokerName AS broker, tl.linehaul_status AS lh_status , t.pkid_trip AS tripid , tl.pk_idlinehaul AS linehaulid, tl.pk_linehaul_number AS linehaul_number , max(tlm.pkid_movement) AS idMovement , tl.date_departure date_departure , tl.date_arrival date_arrival , tl.date_delivery date_delivery , SUM( CASE tlm.movement_type WHEN 'E' THEN tlm.miles_google ELSE 0 END) AS empty_miles , SUM( CASE tlm.movement_type WHEN 'L' THEN tlm.miles_google ELSE 0 END) AS loaded_miles ,( SELECT CONCAT(d.nameFirst , ' ' , d.nameLast) FROM ct_trip_linehaul_movement tlm LEFT JOIN ct_drivers d ON tlm.fkid_driver = d.pkid_driver WHERE tlm.fkid_linehaul = tl.pk_idlinehaul ORDER BY tlm.pkid_movement ASC LIMIT 1) last_driver FROM ct_trip t LEFT JOIN ct_trip_linehaul tl ON t.pkid_trip = tl.fk_idtrip LEFT JOIN ct_trip_linehaul_movement tlm ON tlm.fkid_linehaul = tl.pk_idlinehaul LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker WHERE t.date_close = CURDATE() OR t.trip_status = 'Open' GROUP BY t.trip_number, tl.pk_idlinehaul";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during trip query prepare [$db->errno]: $db->error";
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
      $system_callback['rows'][$row['idTrip']]['idTrip'] = $row['idTrip'];
      $system_callback['rows'][$row['idTrip']]['TripYear'] = $row['TripYear'];
      $system_callback['rows'][$row['idTrip']]['trip_number'] = $row['trip_number'];
      if ($row['lh_status'] == "Closed") {
        $system_callback['rows'][$row['idTrip']]['status'] = $row['status'] . " trip Closure";
      } else {
        $system_callback['rows'][$row['idTrip']]['status'] = $row['status'];
      }
    }
  }
}


 ?>
<div class="container-fluid align-items-right d-flex justify-content-between align-content-center mb-3 position-sticky" style="margin-top: 65px">
  <h1 class="nb-id d-inline text-secondary">Active Trips</h1>
  <div class="float-right">
    <button class="btn btn-outline-success" type="button" data-toggle="modal" data-focus="false" data-target="#addTripModal" name="button">Add Trip</button>
    <a class="btn btn-outline-secondary" href="actions/obReport.php" target="_blank">OB Report</a>
  </div>
</div>

<div class="container-fluid" style="overflow-y: scroll; max-height: 80vh">
  <table class="table table-striped">
    <!-- <thead class="nb-id dash">
      <th>Active Trips</th>
      <th></th>
    </thead> -->
    <tbody id="tripDashTable">
      <?php if ($system_callback['query']['code'] == 2): ?>
        <tr class="inline-table-row">
        <td style="width: 40px"></td>
        <td>No active trips found</td>
        <td class="text-right"></td>
      </tr>
      <?php endif; ?>
        <?php foreach ($system_callback['rows'] as $trip): ?>
          <tr class="inline-table-row" role="button" ty="<?php echo $trip['TripYear']?>" db-id="<?php echo $trip['idTrip']?>">
            <td style="width: 40px"><p class="text-right <?php echo $trip['status']?> trip"> <i class="fa fa-circle"></i> </p></td>
            <td>
              <p class="font-weight-bold"><?php echo "$trip[trip_number]<span class='font-weight-light'> | $trip[trailer_number]</span>" ?></p>
              <?php foreach ($trip as $t_key => $t_value): ?>
                <?php if ($t_key == 'trailer_number'||$t_key == 'TripYear'||$t_key == 'idTrip'||$t_key == 'status'||$t_key == 'trip_number'): ?>
                  <?php continue; ?>
                <?php endif; ?>
                <div class="mb-1">
                  <div class="row">
                    <div class="col-6">
                      <?php if ($t_value['lh_status'] == "Cancelled"): ?>
                        <span style="font-size: 70%"><i class="mr-1 far fa-circle <?php echo $t_value['lh_status']?>"></i></span>
                      <?php else: ?>
                        <span style="font-size: 70%"><i class="mr-1 fas fa-circle <?php echo $t_value['lh_status']?>"></i></span>
                      <?php endif; ?>
                      <?php echo $t_value['linehaul_number'] . " | $t_value[OriginCity], $t_value[OriginState] - $t_value[DestinationCity], $t_value[DestinationState] <span class='small maroon-font'>($t_value[broker])</span>" ?>
                    </div>
                    <div class="col-6 text-right">
                      <?php echo $t_value['last_driver'] ?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <span class="ml-5 grey-font">[<?php echo $t_value['loaded_miles'] ?> Loaded Miles | <?php echo $t_value['empty_miles'] ?> Empty Miles]</span>
                    </div>
                    <div class="col-6 text-right">
                      <?php echo ($t_value['date_arrival'] === NULL ? "" : ("<span class=''></span><span class='pl-2 grey-font'>" . date('Y-m-d', strtotime($t_value['date_arrival'])) . "</span>")) ?>
                      <!-- <div class="row">
                      </div> -->
                    </div>
                  </div>
                  <!-- <div class="row">
                      <div class="col-6 offset-6">
                        <div class="row">
                          <?php echo ($t_value['date_arrival'] === NULL ? "" : ("<div class='col-3 offset-2'>Arrival:</div><div class='col-5 grey-font'>" . date('Y-m-d', strtotime($t_value['date_arrival'])) . "</div>")) ?>
                        </div>

                      </div>

                  </div> -->
                </div>

              <?php endforeach; ?>
            </td>
        <?php endforeach; ?>
    </tbody>
  </table>
  <!-- <?php echo json_encode($system_callback['rows']) ?> -->
</div>

<?php
require 'modales/addTrip.php';
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuBCFwHZCWMgyeTJ1MI32sXlGnJtIIsUA" async defer></script>
<script src="/plsuite/Ubicaciones/Viajes/js/trips.js" charset="utf-8"></script>
