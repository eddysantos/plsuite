<div class="modal fade" id="datePickerModal" tabindex="-1" role="dialog" aria-labelledby="addTrip" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="datePickerHeader"><span class="datePickerTopic"></span> Date Selection</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" role="button">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Please select the dates to run the report:</p>
        <form class="form-group" onsubmit="return false;" method="post">
          <div class="form-row">
            <label for="reportDateFrom" class="col-3 text-right col-form-label">From:</label>
            <div class="form-group col-auto">
              <input class="form-control" type="date" name="reportDateFrom" id="reportDateFrom" value="">
            </div>
          </div>
          <div class="form-row">
            <label for="reportDateTo" class="col-3 text-right col-form-label">To:</label>
            <div class="form-group col-auto">
              <input class="form-control" type="date" name="reportDateTo" id="reportDateTo" value="">
            </div>
          </div>
          <div class="form-row">
            <label for="" class="col-3 text-right col-form-label">Level:</label>
            <div class="col-auto">
              <select class="form-control" id="reportLevel" name="">
                <option value="linehs" selected>Linehauls</option>
                <option value="movs">Movements</option>
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-primary executeReport" report-location="" name="button">Execute</button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" name="button">Cancel</button>
      </div>
    </div>
  </div>
</div>
