<div class="modal fade" id="rpmCalculator" tabindex="-1" role="dialog" aria-labelledby="addTrip" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="">Rate Per Mile Calculator</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" role="button">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-group movmentForm" id="rpmCalculatorForm" onsubmit="return false;">
          <div class="form-group row movementrpmc origin">
            <label for="" class="col-form-label col-3">Origin</label>
            <div class="form-group col-2">
              <input class="form-control zipInputrpmc" type="text" autocomplete="new-password" value="" placeholder="Zip Code">
              <div class="invalid-feedback font-italic"></div>
            </div>
            <div class="form-group col-2">
              <input class="form-control stateInput disabled" disabled type="text" autocomplete="new-password"  value="" placeholder="State">
            </div>
            <div class="form-group col-3">
              <input class="form-control cityInput disabled" disabled type="text" autocomplete="new-password" value="" placeholder="City">
            </div>
            <div class="form-group col-1">
            </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-3 text-success addMovementrpmc" role="button">
              <i class="fa fa-plus"></i> Add Movement
            </div>
          </div>
          <div id="sortableMovementsrpmc">

          </div>
          <div class="form-group row movementrpmc destination">
            <label for="" class="col-form-label col-3">Destination</label>
            <div class="form-group col-2">
              <input class="form-control zipInputrpmc" type="text" autocomplete="new-password"  value="" placeholder="Zip Code">
              <div class="invalid-feedback font-italic"></div>
            </div>
            <div class="form-group col-2">
              <input class="form-control stateInput disabled" disabled type="text" autocomplete="new-password" value="" placeholder="State">
            </div>
            <div class="form-group col-3">
              <input class="form-control cityInput disabled" disabled type="text" autocomplete="new-password"  value="" placeholder="City">
            </div>
            <div class="form-group col-1">
            </div>
          </div>
          <div class="form-group row">
            <label for="" class="col-form-label col-1">Miles</label>
            <div class="col-2">
              <input type="text" class="form-control text-center disabled googleMilesrpmc" disabled name="" value="" readonly>
            </div>
            <label for="" class="col-form-label col-1">Rate</label>
            <div class="col-2">
              <input type="text" class="form-control text-center linehaulRaterpmc" name="" value="">
              <div class="invalid-feedback font-italic"></div>
            </div>
            <label for="" class="col-form-label col-1">RPM</label>
            <div class="col-2">
              <input type="text" class="form-control text-center ratepermilerpmc disabled" disabled name="" value="" readonly>
            </div>
            <div class="col-3">
              <button type="button" class="btn btn-outline-success calculateMilesrpmc form-control" name="button">Calculate</button>
            </div>
          </div>

        </form>
        <!-- <ul id="sortableMovements">
          <li>Movement1</li>
          <li>Movement2</li>
          <li>Movement3</li>
          <li>Movement4</li>
          <li>Movement5</li>
          <li>Movement6</li>
          <li>Movement7</li>
        </ul> -->
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
