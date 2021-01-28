<?php



/**
 *  This handler will contain all required elements throught PL Suite.
 */
class PlSuite
{

  private $db;
  public $last_error;

  function __construct(){
  }

  function getFilteredBrokerList($filter){
    $db = new Queryi();
    $query = "SELECT * FROM ct_brokers WHERE deletedBroker IS NULL AND (brokerName LIKE ?) LIMIT 10";
    $return_value = [];
    $filter_token = "%$filter%";

    $stmt = $db->prepare($query);
    if (!($stmt)) {
      $this->last_error = "Error during query prepare [$db->errno]: $db->error";
      error_log($this->last_error);
      return false;
    }

    $stmt->bind_param('s', $filter_token);
    if (!($stmt)) {
      $this->last_error = "Error during variables binding [$stmt->errno]: $stmt->error";
      error_log($this->last_error);
      return false;
    }

    if (!($stmt->execute())) {
      $this->last_error = "Error during query execution [$stmt->errno]: $stmt->error";
      error_log($this->last_error);
      return false;
    }

    $rslt = $stmt->get_result();

    if ($rslt->num_rows == 0) {
      $return_value[] = "No match found.";
      return $return_value;
    }

    while ($row = $rslt->fetch_assoc()) {
      $id = $db->encrypt($row['pkid_broker']);
      $return_value[$id] = $row['brokerName'];
    }
    return $return_value;


  }
  function getFilteredDriverList($filter){
    $db = new Queryi();
    $query = "SELECT * FROM ct_drivers WHERE deletedDriver IS NULL AND isDriver = 'Yes' AND CONCAT_WS(' ', nameFirst, nameSecond, nameLast) LIKE ? LIMIT 10";
    $return_value = [];
    $filter_token = "%$filter%";

    $stmt = $db->prepare($query);
    if (!($stmt)) {
      $this->last_error = "Error during query prepare [$db->errno]: $db->error";
      error_log($this->last_error);
      return false;
    }

    $stmt->bind_param('s', $filter_token);
    if (!($stmt)) {
      $this->last_error = "Error during variables binding [$stmt->errno]: $stmt->error";
      error_log($this->last_error);
      return false;
    }

    if (!($stmt->execute())) {
      $this->last_error = "Error during query execution [$stmt->errno]: $stmt->error";
      error_log($this->last_error);
      return false;
    }

    $rslt = $stmt->get_result();

    if ($rslt->num_rows == 0) {
      $return_value[] = "No match found.";
      return $return_value;
    }

    while ($row = $rslt->fetch_assoc()) {
      $id = $db->encrypt($row['pkid_driver']);
      $return_value[$id] .= $row['nameFirst'] != "" ? $row['nameFirst'] . " " : "";
      $return_value[$id] .= $row['nameSecond'] != "" ? $row['nameSecond'] . " " : "";
      $return_value[$id] .= $row['nameLast'] != "" ? $row['nameLast'] : "";
    }
    return $return_value;


  }
  function getFilteredTrailerList($filter){
    $db = new Queryi();
    $query = "SELECT * FROM ct_trailer WHERE deletedTrailer IS NULL AND trailerNumber LIKE ? LIMIT 10";
    $return_value = [];
    $filter_token = "%$filter%";

    $stmt = $db->prepare($query);
    if (!($stmt)) {
      $this->last_error = "Error during query prepare [$db->errno]: $db->error";
      error_log($this->last_error);
      return false;
    }

    $stmt->bind_param('s', $filter_token);
    if (!($stmt)) {
      $this->last_error = "Error during variables binding [$stmt->errno]: $stmt->error";
      error_log($this->last_error);
      return false;
    }

    if (!($stmt->execute())) {
      $this->last_error = "Error during query execution [$stmt->errno]: $stmt->error";
      error_log($this->last_error);
      return false;
    }

    $rslt = $stmt->get_result();

    if ($rslt->num_rows == 0) {
      $return_value[] = "No match found.";
      return $return_value;
    }

    while ($row = $rslt->fetch_assoc()) {
      $id = $db->encrypt($row['pkid_trailer']);
      $return_value[$id] .= $row['trailerNumber'];

    }
    return $return_value;


  }
  function getFilteredTractorList($filter){
    $db = new Queryi();
    $query = "SELECT * FROM ct_truck WHERE deletedTruck IS NULL AND truckNumber LIKE ? LIMIT 10";
    $return_value = [];
    $filter_token = "%$filter%";

    $stmt = $db->prepare($query);
    if (!($stmt)) {
      $this->last_error = "Error during query prepare [$db->errno]: $db->error";
      error_log($this->last_error);
      return false;
    }

    $stmt->bind_param('s', $filter_token);
    if (!($stmt)) {
      $this->last_error = "Error during variables binding [$stmt->errno]: $stmt->error";
      error_log($this->last_error);
      return false;
    }

    if (!($stmt->execute())) {
      $this->last_error = "Error during query execution [$stmt->errno]: $stmt->error";
      error_log($this->last_error);
      return false;
    }

    $rslt = $stmt->get_result();

    if ($rslt->num_rows == 0) {
      $return_value[] = "No match found.";
      return $return_value;
    }

    while ($row = $rslt->fetch_assoc()) {
      $id = $db->encrypt($row['pkid_truck']);
      $return_value[$id] .= $row['truckNumber'];

    }
    return $return_value;


  }

  function addTrip($data = NULL){

    if ($data == NULL) {
      $this->last_error = "There was no data to add the trip.";
      return false;
    }
    $db = new Queryi();
    extract($data);

    $driver = $driver_id != "" ? $this->getDriverDetails($db->decrypt($driver_id)) : false;
    $team_driver = $team_driver_id != "" ? $this->getDriverDetails($db->decrypt($team_driver_id)) : false;
    $tractor = $tractor_id != "" ? $this->getTractorDetails($db->decrypt($tractor_id)) : false;
    $trailer = $trailer_id != "" ? $this->getTractorDetails($db->decrypt($trailer_id)) : false;

    $tripFieldValue = [
      'trip_number_i' => 0,
      'fkid_trailer' => '',
      'trailer_number' => '',
      'trailer_plates' => '',
      'trip_year' => 0,
      'trip_number' => 0,
      'added_by' => '',
    ];



    $trip_year = date('y', strtotime('y'));
    $user = $_SESSION['user_info']['NombreUsuario'];




    try {
      $db->query('LOCK TABLES ct_trip, ct_trip_linehaul, ct_trip_linehaul_movement WRITE;');
      $db->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;");

      $query = "SELECT max(trip_number_i) max_trip FROM ct_trip WHERE trip_year = $trip_year";
      $maxTrip = $db->query($query)->fetch_assoc();
      $trip_number_i = $maxTrip['max_trip'] + 1;
      $trip_number = $trip_year . str_pad($trip_number_i, 4, 0, STR_PAD_LEFT);

      $tripFieldValue['trip_year'] = $trip_year;
      $tripFieldValue['trip_number_i'] = $trip_number_i;
      $tripFieldValue['trip_number'] = $trip_number;
      $tripFieldValue['fkid_trailer'] = $trialer['pkid_trailer'];
      $tripFieldValue['trailer_number'] = $trialer['trailerNumber'];
      $tripFieldValue['trailer_plates'] = $trialer['trailerPlates'];
      $tripFieldValue['added_by'] = $_SESSION['user_info']['NombreUsuario'];

      $pkid_trip = $db->insert('ct_trip', $tripFieldValue);

      if (!$pkid_trip) {
        $this->last_error = $db->last_error;
        return false;
      }



      $db->commit();
      $db->query('UNLOCK TABLES;');
      $db->close();
      return $pkid_trip;
    } catch (\Exception $e) {
      $db->rollback();
      $db->query('UNLOCK TABLES;');
    }



  }
  function addLinehaul($data = NULL){
    if ($data == NULL) {
      $this->last_error = "There was no data to work with in the linehaul.";
      return false;
    }

    extract($data);

    $query = "SELECT count(pk_idlinehaul) count FROM ct_trip_linehaul WHERE fk_idtrip = $pkid_trip";
    $linehaulCount = $db->query($query)->fetch_assoc();

    $linehaul_number = $linehaulCount + 1;
  }
  function addMovement($data){

  }

  private function getTractorDetails($id){
    $query = "SELECT * FROM ct_truck WHERE pkid_truck = $id";

    $db = Queryi();
    $query = $db->query($query);

    return $query->fetch_assoc();
  }
  private function getTrailerDetails($id){
    $query = "SELECT * FROM ct_trailer WHERE pkid_truck = $id";

    $db = Queryi();
    $query = $db->query($query);

    return $query->fetch_assoc();
  }
  private function getDriverDetails($id){
    $query = "SELECT * FROM ct_driver WHERE pkid_truck = $id";

    $db = Queryi();
    $query = $db->query($query);

    return $query->fetch_assoc();
  }

}
