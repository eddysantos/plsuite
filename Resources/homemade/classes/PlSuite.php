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
      $return_value[$row['pkid_broker']] = $row['brokerName'];
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
      $return_value[$row['pkid_driver']] .= $row['nameFirst'] != "" ? $row['nameFirst'] . " " : "";
      $return_value[$row['pkid_driver']] .= $row['nameSecond'] != "" ? $row['nameSecond'] . " " : "";
      $return_value[$row['pkid_driver']] .= $row['nameLast'] != "" ? $row['nameLast'] : "";
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
      $return_value[$row['pkid_trailer']] .= $row['trailerNumber'];

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
      $return_value[$row['pkid_truck']] .= $row['truckNumber'];

    }
    return $return_value;


  }


}
