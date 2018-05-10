<div class="modal fade" id="addMovementLh" tabindex="-1" role="dialog" aria-labelledby="addTrip" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="header-nm">Add Movement</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" role="button">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-group" id="newMovementF" onsubmit="return false;">
          <label for="">Origin</label>
          <div class="form-row">
            <div class="form-group col-3">
              <input class="form-control origin new_movement" type="text" autocomplete="new-password" name="oZip" id="oZip" value="" placeholder="Zip Code">
              <div class="invalid-feedback font-italic"></div>
            </div>
            <div class="form-group col-2">
              <input class="form-control origin stateInput disabled" disabled type="text" autocomplete="new-password" name="oSt" id="oState" value="" placeholder="State">
            </div>
            <div class="form-group col-7">
              <input class="form-control origin cityInput disabled" disabled type="text" autocomplete="new-password" name="oCity" id="oCity" value="" placeholder="City">
            </div>
          </div>
          <label for="">Destination</label>
          <div class="form-row">
            <div class="form-group col-3">
              <input class="form-control dest new_movement" type="text" autocomplete="new-password" name="dZip" id="dZip" value="" placeholder="Zip Code">
              <div class="invalid-feedback font-italic"></div>
            </div>
            <div class="form-group col-2">
              <input class="form-control dest stateInput disabled" disabled type="text" autocomplete="new-password" name="dSt" id="dState" value="" placeholder="State">
            </div>
            <div class="form-group col-7">
              <input class="form-control dest cityInput disabled" disabled type="text" autocomplete="new-password" name="dCity" id="dCity" value="" placeholder="City">
            </div>
          </div>
          <div class="form-row">
            <label for="" class="col-form-label col-1">Miles</label>
            <div class="form-group col-2">
              <input type="text" class="form-control imMiles text-center disabled" disabled name="" value="" readonly>
            </div>
            <!-- <div class="form-group col-2">
            <button type="button" class="btn btn-outline-info" name="button">Get Miles</button>
            </div> -->
          </div>
          <label for="">Conveyance</label>
          <div class="form-row">
            <div class="form-group col-4">
              <input class="form-control mb-3 truckInput popup-input" id-display="#truck-popup-list" type="text" autocomplete="new-password" db-id="" name="" value="" placeholder="Tractor Number">
              <div class="invalid-feedback font-italic"></div>
              <div id="truck-popup-list" class="popup-list" style="display: none; z-index: 9999">
              </div>
            </div>
            <div class="form-group col-8">
              <input class="form-control mb-3 driverInput popup-input" id-display="#driver-popup-list" type="text" autocomplete="new-password" db-id="" name="" value="" placeholder="Driver Name">
              <div class="invalid-feedback font-italic"></div>
              <div id="driver-popup-list" class="popup-list" style="display: none; z-index: 9999">
              </div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-check col-4 d-flex align-items-center align-self-start">
              <input class="form-check-input teamDriverCheck" style="margin: 0" type="checkbox" target="[id-display='#driver-popup-list-team']" value="" id="teamDriverCheck">
              <label class="form-check-label" for="teamDriverCheck">
                Team Driver
              </label>
            </div>
            <div class="form-group col-8">
              <input class="form-control mb-3 teamInput popup-input" id-display="#driver-popup-list-team" style="display: none" type="text" autocomplete="new-password" db-id="" name="" value="" placeholder="Driver Name">
              <div id="driver-popup-list-team" class="popup-list" style="display: none; z-index: 9999">
              </div>
            </div>
          </div>
          <fieldset class="form-group">
              <div class="row">
                <div class="col-form-label col-3 pt-0">Movement Type</div>
                <div class="col-3">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="movement_type" id="movement_type_empty" value="E" checked>
                    <label class="form-check-label" for="movement_type_empty">
                      Empty
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="movement_type" id="movement_type_loaded" value="L">
                    <label class="form-check-label" for="movement_type_loaded">
                      Loaded
                    </label>
                  </div>
                </div>
                <div class="col-form-label col-3 pt-0">Extra Stop</div>
                <div class="col-3">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="extra_stop" id="extra_stop_false" value="0" checked>
                    <label class="form-check-label" for="extra_stop_false">
                      No
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="extra_stop" id="extra_stop_true" value="1">
                    <label class="form-check-label" for="extra_stop_true">
                      Yes
                    </label>
                  </div>
                </div>
              </div>
            </fieldset>
          <fieldset class="form-group">
              <div class="row">
                <div class="col-form-label col-3 pt-0">Empty As Loaded</div>
                <div class="col-9">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="empty_as_loaded" id="empty_as_loaded_false" value="0" checked>
                    <label class="form-check-label" for="empty_as_loaded_false">
                      No
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="empty_as_loaded" id="empty_as_loaded_true" value="1">
                    <label class="form-check-label" for="empty_as_loaded_true">
                      Yes
                    </label>
                  </div>
                </div>
              </div>
            </fieldset>
            <input type="text" name="" value="1" style="display: none" id="mvneworedit" class="hidden" hidden>
            <input type="text" name="" value="1" style="display: none" id="mvid" class="hidden" hidden>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success submitMovement float-right disabled" id="newMovBtn" name="button" disabled>Add Movement</button>
      </div>
    </div>
  </div>
</div>
