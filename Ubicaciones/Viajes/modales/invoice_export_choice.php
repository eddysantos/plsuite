<div class="modal fade" id="trip-filter-modal" tabindex="-1" role="dialog" aria-labelledby="tripFilter" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="">Trip Filter</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" role="button">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-group" onsubmit="return false" method="post">
          <label for="filter_truck" class="mb-0">Truck</label>
          <input type="text" id="filter_truck" class="form-control form-control-sm popup-input" id-display="#truck-popup-list-modal" name="" value="">
          <div id="truck-popup-list-modal" class="popup-list mt-3" style="display: none; z-index: 9999"></div>

          <label for="filter_driver" class="mb-0 mt-1">Driver</label>
          <input type="text" id="filter_driver" class="form-control form-control-sm" name="" value="">

          <label for="filter_trailer" class="mb-0 mt-1">Trailer Number</label>
          <input type="text" id="filter_trailer" class="form-control form-control-sm" name="" value="">
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-primary" name="button">Load Trips</button>
      </div>
    </div>
  </div>
</div>
