<div class="modal fade" id="addLinehaulModal" tabindex="-1" role="dialog" aria-labelledby="addTrip" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="nb-id">Add new linehaul</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" role="button">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-group movmentForm" id="addLinehaulForm" onsubmit="return false;">
          <div class="form-group row">
            <label for="" class="col-form-label col-3">Broker</label>
            <div class="form-group col-6">
              <input class="form-control mb-3 popup-input brokerid" id-display="#broker-popup-list" type="text" autocomplete="new-password" db-id="" name="" value="" placeholder="Broker Name">
              <div class="invalid-feedback font-italic"></div>
              <div id="broker-popup-list" class="popup-list" style="display: none; z-index: 9999">
              </div>
            </div>
            <div class="form-group col-3">
              <input type="text" class="form-control " name="broker-reference" id="broker-reference" value="" placeholder="Reference Number">
            </div>
          </div>
          <div class="form-group row movement truckLocation">
            <label for="" class="col-form-label col-3">Truck Location</label>
            <div class="form-group col-2">
              <input class="form-control zipInput" type="text" autocomplete="new-password" name="tlZip" id="modaltlZip" value="<?php echo $lastEl['destination_zip']?>" placeholder="Zip Code">
              <div class="invalid-feedback font-italic"></div>
            </div>
            <div class="form-group col-2">
              <input class="form-control stateInput disabled" disabled type="text" autocomplete="new-password" name="tlSt" id="modaltlState" value="<?php echo $lastEl['destination_state']?>" placeholder="State">
            </div>
            <div class="form-group col-3">
              <input class="form-control cityInput disabled" disabled type="text" autocomplete="new-password" name="tlCity" id="modaltlCity" value="<?php echo $lastEl['destination_city']?>" placeholder="City">
            </div>

          </div>
          <div class="form-group row movement origin">
            <label for="" class="col-form-label col-3">Origin</label>
            <div class="form-group col-2">
              <input class="form-control zipInput" type="text" autocomplete="new-password" name="oZip" id="modaloZip" value="" placeholder="Zip Code">
              <div class="invalid-feedback font-italic"></div>
            </div>
            <div class="form-group col-2">
              <input class="form-control stateInput disabled" disabled type="text" autocomplete="new-password" name="oSt" id="modaloState" value="" placeholder="State">
            </div>
            <div class="form-group col-3">
              <input class="form-control cityInput disabled" disabled type="text" autocomplete="new-password" name="oCity" id="modaloCity" value="" placeholder="City">
            </div>
            <div class="form-group col-1">
              <input type="text" class="existsInDatabase" exists="" name="" value="" hidden>
              <input type="text" class="googleMiles" exists="" name="" value="" hidden>
              <select class="form-control movement_type" name="">
                <option value="E" selected>Empty</option>
                <option value="L">Loaded</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-3 text-success addMovement" role="button">
              <i class="fa fa-plus"></i> Add Movement
            </div>
          </div>
          <div id="sortableMovements">

          </div>
          <div class="form-group row movement destination">
            <label for="" class="col-form-label col-3">Destination</label>
            <div class="form-group col-2">
              <input class="form-control zipInput" type="text" autocomplete="new-password" name="dZip" id="modaldZip" value="" placeholder="Zip Code">
              <div class="invalid-feedback font-italic"></div>
            </div>
            <div class="form-group col-2">
              <input class="form-control stateInput disabled" disabled type="text" autocomplete="new-password" name="dSt" id="modaldState" value="" placeholder="State">
            </div>
            <div class="form-group col-3">
              <input class="form-control cityInput disabled" disabled type="text" autocomplete="new-password" name="dCity" id="modaldCity" value="" placeholder="City">
            </div>
            <div class="form-group col-1">
              <input type="text" class="existsInDatabase" exists="" name="" value="" hidden>
              <input type="text" class="googleMiles" exists="" name="" value="" hidden>
              <!-- <div class="form-check">
                <input type="radio" class="form-check-input" name="mov_type" id="mov_type_1" value="Empty">
                <label for="mov_type_1" class="form-check-label">Empty</label>
              </div> -->
              <select class="form-control movement_type" name="">
                <option value="E">Empty</option>
                <option value="L" selected>Loaded</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="" class="col-form-label col-1">Miles</label>
            <div class="col-2">
              <input type="text" class="form-control text-center disabled" disabled id="googleMiles" name="" value="" readonly>
            </div>
            <label for="" class="col-form-label col-1">Rate</label>
            <div class="col-2">
              <input type="text" class="form-control text-center linehaulRate" name="" value="">
              <div class="invalid-feedback font-italic"></div>
            </div>
            <label for="" class="col-form-label col-1">RPM</label>
            <div class="col-2">
              <input type="text" class="form-control text-center ratepermile disabled" disabled name="" value="" readonly>
            </div>
            <div class="col-3">
              <button type="button" class="btn btn-outline-success form-control" id="calculateMiles" name="button">Calculate</button>
            </div>
          </div>
          <label for="">Conveyance</label>
          <div class="form-group row">
            <div class="form-group col-8">
              <input class="form-control mb-3 popup-input driverid" id-display="#driver-popup-list-modal" type="text" autocomplete="new-password" db-id="" name="" value="" placeholder="Driver Name">
              <div class="invalid-feedback font-italic"></div>
              <div id="driver-popup-list-modal" class="popup-list" style="display: none; z-index: 9999">
              </div>
            </div>
            <div class="form-group col-4">
              <input class="form-control mb-3 popup-input truckid" id-display="#truck-popup-list-modal" type="text" autocomplete="new-password" db-id="" name="" value="" placeholder="Tractor Number">
              <div class="invalid-feedback font-italic"></div>
              <div id="truck-popup-list-modal" class="popup-list" style="display: none; z-index: 9999">
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="form-check col-4 d-flex align-items-center align-self-start">
              <input class="form-check-input teamDriverCheck" style="margin: 0" type="checkbox" value="" target="[id-display='#driver-popup-list-team-modal']" id="modalteamDriverCheck">
              <label class="form-check-label" for="modalteamDriverCheck">
                Team Driver
              </label>
            </div>
            <div class="form-group col-8">
              <input class="form-control mb-3 popup-input teamdriverid" id-display="#driver-popup-list-team-modal" style="display: none" type="text" autocomplete="new-password" db-id="" name="" value="" placeholder="Driver Name">
              <div id="driver-popup-list-team-modal" class="popup-list" style="display: none; z-index: 9999">
              </div>
            </div>
          </div>
          <input class="hidden" type="text" id="tripid" name="" value="<?php echo $_GET['tripid']?>" hidden>
          <input class="hidden" type="text" id="tripyear" name="" value="<?php echo $_GET['tripyear']?>" hidden>
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
        <button type="button" id="addLinehaulSubmit" class="btn btn-success disabled" name="button" disabled>Add Trip</button>
      </div>
    </div>
  </div>
</div>
