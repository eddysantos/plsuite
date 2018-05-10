<div class="modal fade" id="addTripModal" tabindex="-1" role="dialog" aria-labelledby="addTrip" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 540px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="nb-id">Add new trip</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" role="button">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-group" id="newTripForm" action="" onsubmit="return false;">
          <div class="form-row">
            <div class="form-group col-6">
              <input class="form-control popup-input" id-display="#trailer-popup-list" type="text" autocomplete="new-password" db-id="" name="" value="" placeholder="Trailer">
              <div class="invalid-feedback font-italic"></div>
              <div id="trailer-popup-list" class="popup-list mt-3" style="display: none; z-index: 9999">
              </div>
            </div>
            <div class="form-group col-6">
              <input class="form-control" type="text" autocomplete="new-password" name="tRate" id="tRate" value="" placeholder="Trip Rate">
              <div class="invalid-feedback font-italic"></div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-6">
              <div class="input-group mb-3">
                <div class="input-group-addon" id="btnQuickAddBroker" role="button" data-container="body" data-toggle="popover">
                  <span class="input-group-text" id="addBrokerotf" ><i class="fas fa-plus"></i></span>
                </div>
                <input class="form-control popup-input selected-broker" id-display="#broker-popup-list" aria-describedby="addBrokerotf" id="brokerName" type="text" autocomplete="new-password" db-id="" name="" value="" placeholder="Broker Name">
              </div>
              <div class="invalid-feedback font-italic"></div>
              <div id="broker-popup-list" class="popup-list mt-3" style="display: none; z-index: 9999"></div>
            </div>
            <div class="form-group col-6">
              <input type="text" class="form-control" name="broker-reference" id="broker-reference" value="" placeholder="Broker Reference Number">
            </div>
          </div>
          <br>
          <div class="form-row">
            <label for="" class="col-2 col-form-label">Origin</label>
            <div class="form-group col-3">
              <input class="form-control zipInput" type="text" autocomplete="new-password" name="oZip" id="oZip" value="" placeholder="Zip Code">
              <div class="invalid-feedback font-italic"></div>
            </div>
            <div class="form-group col-2">
              <input class="form-control stateInput disabled" type="text" disabled autocomplete="new-password" name="oSt" id="oState" value="" placeholder="State">
              <div class="invalid-feedback font-italic"></div>
            </div>
            <div class="form-group col-5">
              <input class="form-control cityInput disabled" type="text" disabled autocomplete="new-password" name="oCity" id="oCity" value="" placeholder="City">
              <div class="invalid-feedback font-italic"></div>
            </div>
          </div>
          <div class="form-row">
            <label for="" class="col-2 col-form-label">Destination</label>
            <div class="form-group col-3">
              <input class="form-control zipInput" type="text" autocomplete="new-password" name="dZip" id="dZip" value="" placeholder="Zip Code">
              <div class="invalid-feedback font-italic"></div>
            </div>
            <div class="form-group col-2">
              <input class="form-control stateInput disabled" type="text" disabled autocomplete="new-password" name="dState" id="dState" value="" placeholder="State">
              <div class="invalid-feedback font-italic"></div>
            </div>
            <div class="form-group col-5">
              <input class="form-control cityInput disabled" type="text" disabled autocomplete="new-password" name="dCity" id="dCity" value="" placeholder="City">
              <div class="invalid-feedback font-italic"></div>
            </div>
          </div>
          <br>
          <div class="form-row">
            <label for="" class="col-2 col-form-label">Conveyance</label>
            <div class="form-group offset-1 col-5">
              <input class="form-control popup-input driverid" id-display="#driver-popup-list-modal" type="text" autocomplete="new-password" db-id="" name="" value="" placeholder="Driver Name">
              <div class="invalid-feedback font-italic"></div>
              <div id="driver-popup-list-modal" class="popup-list mt-3" style="display: none; z-index: 9999">
              </div>
            </div>
            <div class="form-group col-4">
              <input class="form-control popup-input truckid" id-display="#truck-popup-list-modal" type="text" autocomplete="new-password" db-id="" name="" value="" placeholder="Tractor">
              <div class="invalid-feedback font-italic"></div>
              <div id="truck-popup-list-modal" class="popup-list mt-3" style="display: none; z-index: 9999">
              </div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-check col-3 d-flex align-items-center align-self-start">
              <input class="form-check-input teamDriverCheck" style="margin: 0" type="checkbox" value="" target="[id-display='#driver-popup-list-team-modal']" id="modalteamDriverCheck">
              <div class="invalid-feedback font-italic"></div>
              <label class="form-check-label" for="modalteamDriverCheck">
                Team Driver
              </label>
            </div>
            <div class="form-group col-9">
              <input class="form-control popup-input teamdriverid" id-display="#driver-popup-list-team-modal" style="display: none" type="text" autocomplete="new-password" db-id="" name="" value="" placeholder="Driver Name">
              <div class="invalid-feedback font-italic"></div>
              <div id="driver-popup-list-team-modal" class="popup-list mt-3" style="display: none; z-index: 9999">
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="addTripButton" class="btn btn-success disabled" name="button" disabled>Add Trip</button>
      </div>
    </div>
  </div>
</div>
<form class="" id="addBrokerQuick" onsubmit="return false;" hidden>
  <div class="form-group">
    <input type="text" class="form-control qa-broker-name" name="Brokername" placeholder="Name" value="">
  </div>
  <div class="form-group">
    <input type="text" class="form-control qa-broker-contact" name="brokerMainContact" placeholder="Main Contact" value="">
  </div>
  <div class="form-group text-right">
    <button type="button" class="btn btn-outline-success qa-broker-submit" name="button">Add</button>
  </div>
</form>
