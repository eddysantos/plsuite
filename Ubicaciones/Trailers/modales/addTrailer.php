<div class="modal fade" id="addTrailerModal" tabindex="-1" role="dialog" aria-labelledby="addTrip" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="nb-id">Add new trailer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" role="button">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form onsubmit="return false;">
          <div class="form-group">
            <select class="form-control mb-3" name="" id="nt-ow">
              <option value="">Owned by...</option>
              <option value="0">Prolog Transportation Inc</option>
            </select>
            <input class="form-control mb-3" type="text" name="" value=""  id="nt-vin" placeholder="VIN" autocomplete="off">
            <input class="form-control mb-3" type="text" name="" value="" id="nt-br" placeholder="Brand" autocomplete="off">
            <input class="form-control mb-3" type="text" name="" value="" id="nt-ye" placeholder="Year" autocomplete="off">
            <input class="form-control mb-3" type="text" name="" value="" id="nt-tn" placeholder="Trailer Number" autocomplete="off">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary" id="addTrailerSubmit" name="button">Add Trailer</button>
      </div>
    </div>
  </div>
</div>
