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
          <div class="form-row">
            <label for="" class="col-form-label col-md-3">Appointment</label>
            <div class="form-group col-md-5">
              <input type="date" class="form-control appointmentadd date" id="appointment_date_add" name="" value="">
            </div>
            <div class="form-group col-md-2">
              <select class="form-control appointment hour" id="appointment_time_hour_add" name="appointment_time_hour">
                <option value="">Hr</option>
                <option value="01">01</option>
                <option value="02">02</option>
                <option value="03">03</option>
                <option value="04">04</option>
                <option value="05">05</option>
                <option value="06">06</option>
                <option value="07">07</option>
                <option value="08">08</option>
                <option value="09">09</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
                <option value="15">15</option>
                <option value="16">16</option>
                <option value="17">17</option>
                <option value="18">18</option>
                <option value="19">19</option>
                <option value="20">20</option>
                <option value="21">21</option>
                <option value="22">22</option>
                <option value="23">23</option>
                <option value="24">24</option>
              </select>
            </div>
            <div class="form-group col-md-2 pl-0">
              <select class="form-control appointment minute" id="appointment_time_minute_add" name="appointment_time_minute">
                <option value="">Min</option>
                <option value="00">00</option>
                <option value="05">05</option>
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
                <option value="25">25</option>
                <option value="30">30</option>
                <option value="35">35</option>
                <option value="40">40</option>
                <option value="45">45</option>
                <option value="50">50</option>
                <option value="55">55</option>
              </select>
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
        <button type="button" id="addTripButtonLoading" style="display: none" class="btn btn-success disabled" name="button" disabled><i class="fas fa-circle-notch fa-spin"></i></button>
        <button type="button" id="addTripButton" loading="#addTripButtonLoading" class="btn btn-success disabled" name="button" disabled>Add Trip</button>
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
