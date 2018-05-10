<div class="modal fade" id="closeTripModal" tabindex="-1" role="dialog" aria-labelledby="addTrip" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="">Close <span id="close-record-year"></span><span id="close-record-id"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" role="button">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="mb-0">Please make sure the  number is correct, and include the closure date.</p>
        <p class="text-danger">Remember, no edits will be possible after closure</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-danger finalizeRecord" type-of-record="trip" tripid="<?php echo $_GET['tripid']?>" name="button">Yes</button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" name="button">No</button>
      </div>
    </div>
  </div>
</div>
