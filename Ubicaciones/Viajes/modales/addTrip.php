<div class="modal fade" id="addTripModal" tabindex="-1" role="dialog" aria-labelledby="addTripModal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Trip</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="nav justify-content-center" id="add_trip_progress" role="tablist">
          <li class="nav-item">
            <a class="nav-link custom active" id="trip-details-tab" data-toggle="tab" tab-type="addTripModal" href="#trip-details-pane" role="tab" aria-controls="trip-details" aria-selected="true">Trip Details</a>
          </li>
          <li class="nav-item">
            <a class="nav-link custom disabled" disabled id="lh-details-tab" data-toggle="tab" tab-type="addTripModal" href="#lh-details-pane" role="tab" aria-controls="lh-details" aria-selected="true">Linehaul Details</a>
          </li>
          <li class="nav-item">
            <a class="nav-link custom disabled" disabled id="conveyance-details-tab" data-toggle="tab" tab-type="addTripModal" href="#" role="tab" aria-controls="conveyance-details" aria-selected="true">Conveyance</a>
          </li>
          <li class="nav-item">
            <a class="nav-link custom disabled" disabled id="trip-confirmation-tab" data-toggle="tab" tab-type="addTripModal" href="#" role="tab" aria-controls="trip-confirmation" aria-selected="true">Confirm Trip</a>
          </li>
        </ul>

        <div class="progress mb-3" style="height: 2px">
          <div class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <div class="tab-content" id="trip-details-content">
          <div class="tab-pane fade" id="trip-details-pane" role="tabpanel" aria-labelledby="trip-details-tab">
            <form class="needs-validation" action="index.html" onsubmit="return false;" method="post">

              <div class="form-group row">
                <label for="trailer-number" class="col-sm-2 offset-2 col-form-label text-right">Trailer</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control popup-input" id-display="#trailer-popup-list" type="text" autocomplete="new-password" db-id="" name="" value="" placeholder="Trailer Number">
                  <div id="trailer-popup-list" class="popup-list mt-3" style="display: none; z-index: 9999"></div>
                  <small class="invalid-feedback font-italic">This field cannot be empty.</small>
                </div>
              </div>

              <div class="form-group row">
                <label for="broker-name" class="col-sm-2 offset-2 col-form-label text-right">Broker</label>
                <div class="col-sm-5">
                  <div class="input-group">
                    <div class="input-group-prepend" id="btnQuickAddBroker" role="button" data-container="body" data-toggle="popover">
                      <span class="input-group-text" id="addBrokerotf" ><i class="fas fa-plus"></i></span>
                    </div>
                    <input type="text" class="form-control popup-input selected-broker" id-display="#broker-popup-list" aria-describedby="addBrokerotf" id="brokerName" type="text" autocomplete="new-password" db-id="" name="" value="" placeholder="Broker Name">
                  </div>
                  <div id="broker-popup-list" class="popup-list mt-3" style="display: none; z-index: 9999"></div>
                  <small class="invalid-feedback">This field cannot be empty.</small>
                </div>
              </div>

              <div class="form-group row">
                <label for="broker-reference" class="col-sm-2 offset-2 col-form-label text-right">Reference</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" name="broker-reference" value="" placeholder="Broker Reference">
                  <small class="invalid-feedback">This field cannot be empty.</small>
                </div>
              </div>

              <div class="form-group row">
                <label for="trip-rate" class="col-sm-2 offset-2 col-form-label text-right">Rate</label>
                <div class="col-sm-5">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                    </div>
                    <input type="text" class="form-control" name="trip-rate" value="" placeholder="Trip Rate">
                  </div>
                  <small class="invalid-feedback">This field cannot be empty.</small>
                </div>
              </div>

            </form>
          </div>
          <div class="tab-pane fade" id="lh-details-pane" role="tabpanel" aria-labelledby="lh-details-tab">
            <form onsubmit="return false;">
              <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label text-right">Origin</label>
                <div class="col-lg-2">
                  <input type="text" class="form-control zipInput" autocomplete="new-password" name="" value="" placeholder="Zip Code">
                  <small class="invalid-feedback font-italic" style="position:relative; width:300px">This field cannot be empty.</small>
                </div>
                <div class="col-lg-2" readonly>
                  <input type="text" class="form-control stateInput" name="" value="" placeholder="State" readonly disabled>
                </div>
                <div class="col-lg-5">
                  <input type="text" class="form-control cityInput" name="" value="" placeholder="City" readonly disabled>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label text-right">Destination</label>
                <div class="col-lg-2">
                  <input type="text" class="form-control zipInput" autocomplete="new-password" name="" value="" placeholder="Zip Code">
                  <small class="invalid-feedback font-italic" style="position:relative; width:300px">This field cannot be empty.</small>
                </div>
                <div class="col-lg-2" readonly>
                  <input type="text" class="form-control stateInput" name="" value="" placeholder="State" readonly disabled>
                </div>
                <div class="col-lg-5">
                  <input type="text" class="form-control cityInput" name="" value="" placeholder="City" readonly disabled>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label text-right">Total Miles</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="" value="" placeholder="miles">
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label text-right">Appointment</label>
                <div class="col-lg-5">
                  <input type="date" class="form-control" id="date-field" name="" value="">
                </div>
                <div class="col-lg-2">
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
                <div class="col-lg-2">
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
            </form>
          </div>
          <div class="tab-pane fade show active" id="conveyance-details-pane" role="tabpanel" aria-labelledby="conveyance-details-tab">
            <form onsubmit="false">
              <div class="form-group row">
                <label for="" class="col-form-label col-sm-2 text-right">Tractor</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" name="" value="" placeholder="Tractor Number">
                </div>
              </div>
            </form>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary next-pane disabled" disabled>Next</button>
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
