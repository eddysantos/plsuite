<?php
/**
 *
 */
class Trip
{
  public $last_error;
  public $data = [];

  function __construct(){}

  function getPOs($date_from, $date_to, $status = "All"){
    $db = new Queryi();

    if ($status == "Open") {
      $and_where = "AND tl.date_delivery IS NULL";
    }

    if ($status == "Closed") {
      $and_where = "AND tl.date_delivery IS NOT NULL";
    }

    $query = "SELECT po.po_number po_number , po.po_pickup_date po_pickup_date , po.po_pickup_time po_pickup_time,tl.pk_idlinehaul idLinehaul, tl.lh_number lhNumber, t.trailer_number trailer , SUBSTRING_INDEX( GROUP_CONCAT( CONCAT(d.nameFirst , ' ' , d.nameLast)) , ',' , 1) driver , SUBSTRING_INDEX( GROUP_CONCAT(trk.truckNumber) , ',' , 1) tractor, tl.date_appointment date_appointment, tl.date_departure date_departure, tl.date_arrival date_arrival, tl.date_delivery date_delivery FROM client_po po LEFT JOIN ct_trip_linehaul tl ON po.po_number = tl.po_number LEFT JOIN ct_trip t ON tl.fk_idtrip = t.pkid_trip LEFT JOIN ct_trip_linehaul_movement tlm ON tl.pk_idlinehaul = tlm.fkid_linehaul LEFT JOIN ct_drivers d ON tlm.fkid_driver = d.pkid_driver LEFT JOIN ct_truck trk ON trk.pkid_truck = tlm.fkid_tractor WHERE po.po_pickup_date BETWEEN ? AND ? $and_where GROUP BY po_number";

    $stmt = $db->prepare($query);
    if (!($stmt)) {
      $this->last_error = "Error during query prepare [$db->errno]: $db->error";
      return false;
    }

    $stmt->bind_param('ss', $date_from, $date_to);
    if (!($stmt)) {
      $this->last_error = "Error during variables binding [$stmt->errno]: $stmt->error";
      exit_script($system_callback);
    }

    if (!($stmt->execute())) {
      $this->last_error = "Error during query execution [$db->errno]: $db->error";
      exit_script($system_callback);
    }

    $rslt = $stmt->get_result();

    if ($rslt->num_rows == 0) {
      $this->last_error = "The query did not return any data.";
    } else {
      while ($row = $rslt->fetch_assoc()) {
        $status = "Not Yet Assigned";

        if ($row['date_appointment'] != "") {
          $status = "Scheduled";
        }
        if ($row['date_departure'] != "") {
          $status = "In Transit";
        }
        if ($row['date_arrival'] != "") {
          $status = "Pending Delivery";
        }
        if ($row['date_delivery'] != "") {
          $status = "Delivered";
        }

        $row['status'] = $status;

        $this->data[] = $row;
      }
      return $this->data;
    }
  }
}



 ?>
