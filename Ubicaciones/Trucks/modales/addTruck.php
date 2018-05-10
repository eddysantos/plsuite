<?php

$query = "SELECT pkid_driver, nameFirst, nameLast FROM ct_drivers WHERE isOwner = 'Yes'";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during trailer query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during trailer query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();

if ($rslt->num_rows == 0) {
  $system_callback['query']['code'] = 2;
  $system_callback['query']['message'] = "Script called successfully but there are no rows to display. For trip query.";
  $system_callback['query']['data'] .= $row;
  exit_script($system_callback);
} else {
  $system_callback['query']['code'] = 1;
  while ($row = $rslt->fetch_assoc()) {
    $system_callback['owners'][] = $row;
  }
}

 ?>
<div class="modal fade" id="addTruckModal" tabindex="-1" role="dialog" aria-labelledby="addTrip" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="nb-id">Add new truck</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" role="button">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form onsubmit="return false;">
          <div class="form-group">
            <select class="form-control mb-3" id="nt-ow" name="">
              <option value="0">Owned by...</option>
              <option value="0">Prolog Transportation</option>
              <?php foreach ($system_callback['owners'] as $owner): ?>
                <option value="<?php echo $owner['pkid_driver']?>" <?php echo $owner['pkid_driver'] == $row['truckOwnedBy'] ? "selected" : ""?>><?php echo "$owner[nameFirst] $owner[nameLast]" ?></option>
              <?php endforeach; ?>
            </select>
            <input class="form-control mb-3" type="text" id="nt-vin" name="" value="" placeholder="VIN" autocomplete="off">
            <input class="form-control mb-3" type="text" name="" id="nt-br" value="" placeholder="Brand" autocomplete="off">
            <input class="form-control mb-3" type="text" name="" id="nt-ye" value="" placeholder="Year" autocomplete="off">
            <input class="form-control mb-3" type="text" name="" id="nt-tn" value="" placeholder="Truck Number" autocomplete="off">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary" id="addTruckSubmit" name="button">Add Truck</button>
      </div>
    </div>
  </div>
</div>
