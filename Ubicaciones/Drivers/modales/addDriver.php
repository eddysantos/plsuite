<div class="modal fade" id="addDriverModal" tabindex="-1" role="dialog" aria-labelledby="addTrip" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="nb-id">Add new driver</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" role="button">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form onsubmit="return false;">
          <div class="form-group">
            <input class="form-control mb-3" id="nd-fn" type="text" name="" value="" placeholder="First Name" autocomplete="off">
            <input class="form-control mb-3" id="nd-ln" type="text" name="" value="" placeholder="Last Name" autocomplete="off">
            <input class="form-control mb-3" id="nd-pn" type="text" name="" value="" placeholder="Phone Number" autocomplete="off">
            <input class="form-control mb-3" id="nd-em" type="text" name="" value="" placeholder="E-Mail" autocomplete="off">
          </div>
          <div class="form-row">
            <div class="form-group col-6">
              <!-- <input class="form-control" type="text" name="" value="" placeholder="Is Owner?"> -->
              <select class="form-control" name="" id="nd-ow">
                <option value="">Is Owner?</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
            <div class="form-group col-6">
              <!-- <input class="form-control" type="text" name="" value="" placeholder="Is Driver?"> -->
              <select class="form-control" name="" id="nd-dr">
                <option value="">Is Driver?</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary" id="addDriverSubmit" name="button">Add Driver</button>
      </div>
    </div>
  </div>
</div>
