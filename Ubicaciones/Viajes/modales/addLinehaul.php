<div class="modal fade" id="addLinehaulModal" tabindex="-1" role="dialog" aria-labelledby="addLinehaulModal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Linehaul</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="nav justify-content-center" id="add_trip_progress" role="tablist">
          <li class="nav-item">
            <a class="nav-link custom active" id="trip-details-tab" data-toggle="tab" tab-type="addTripModal" href="#trip-details-pane" role="tab" aria-controls="trip-details" aria-selected="true" progress="">Linehaul Details</a>
          </li>
          <li class="nav-item">
            <a class="nav-link custom disabled" disabled id="lh-details-tab" data-toggle="tab" tab-type="addTripModal" href="#lh-details-pane-add" role="tab" aria-controls="lh-details" aria-selected="true" progress="33">Movement Details</a>
          </li>
          <li class="nav-item">
            <a class="nav-link custom disabled" disabled id="conveyance-details-tab" data-toggle="tab" tab-type="addTripModal" href="#conveyance-details-pane" role="tab" aria-controls="conveyance-details" aria-selected="true" progress="66">Conveyance</a>
          </li>
          <li class="nav-item">
            <a class="nav-link custom disabled" disabled id="trip-confirmation-tab" data-toggle="tab" tab-type="addTripModal" href="#trip-confirmation-pane" role="tab" aria-controls="trip-confirmation" aria-selected="true" progress="100">Confirm Trip</a>
          </li>
        </ul>

        <div class="progress mb-3" style="height: 2px">
          <div class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <div class="tab-content" id="trip-details-content">
          <div class="tab-pane fade show active" id="trip-details-pane" role="tabpanel" aria-labelledby="trip-details-tab">
            <form class="needs-validation" action="index.html" onsubmit="return false;" method="post">

              <div class="form-group row">
                <label for="trailer-number" class="col-sm-2 col-form-label text-right">Trip</label>
                <div class="col-sm-5">
                  <div class="">
                    <input type="text" class="form-control-plaintext tripid grey-font readonly" readonly db-id="" name="" value="" placeholder="Trip Number">
                  </div>
                  <div id="trailer-popup-list" class="popup-list mt-3" style="display: none; z-index: 9999"></div>
                  <small class="invalid-feedback font-italic">This field cannot be empty.</small>
                </div>
              </div>

              <div class="form-group row">
                <label for="trailer-number" class="col-sm-2 col-form-label text-right">Trailer</label>
                <div class="col-sm-5">
                  <div class="">
                    <input type="text" class="form-control-plaintext popup-input trailerid grey-font readonly" readonly id-display="#trailer-popup-list" type="text" autocomplete="new-password" db-id="" name="" value="" placeholder="Trailer Number">
                  </div>
                  <div id="trailer-popup-list" class="popup-list mt-3" style="display: none; z-index: 9999"></div>
                  <small class="invalid-feedback font-italic">This field cannot be empty.</small>
                </div>
              </div>

              <div class="form-group row">
                <label for="broker-name" class="col-sm-2 col-form-label text-right">Broker</label>
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
                <label for="broker-reference" class="col-sm-2 col-form-label text-right">Reference</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control broker-reference" name="broker-reference" value="" placeholder="Broker Reference">
                  <small class="invalid-feedback">This field cannot be empty.</small>
                </div>
              </div>

              <div class="form-group row">
                <label for="trip-rate" class="col-sm-2 col-form-label text-right">Rate</label>
                <div class="col-sm-5">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                    </div>
                    <input type="number" class="form-control trip-rate" name="trip-rate" value="" placeholder="Trip Rate">
                  </div>
                  <small class="invalid-feedback">This field cannot be empty.</small>
                </div>
              </div>

            </form>
          </div>
          <div class="tab-pane fade" id="lh-details-pane-add" role="tabpanel" aria-labelledby="lh-details-tab">
            <form onsubmit="return false;">
              <div class="form-group row movement">
                <label for="" class="col-sm-2 col-form-label text-right">Truck Location</label>
                <div class="col-lg-2">
                  <input type="text" class="form-control zipinput" kind="movement" autocomplete="new-password" name="" value="" placeholder="Zip Code" data-is-valid=true>
                  <small class="invalid-feedback font-italic" style="position:relative; width:300px">This field cannot be empty.</small>
                </div>
                <div class="col-lg-2" readonly>
                  <input type="text" class="form-control stateInput" name="" value="" placeholder="State" readonly disabled>
                </div>
                <div class="col-lg-3">
                  <input type="text" class="form-control cityInput" name="" value="" placeholder="City" readonly disabled>
                </div>
                <div class="col-lg-2">
                  <select class="form-control mov-type" name="" data-is-valid=true>
                    <option value="L">Loaded</option>
                    <option value="E" selected>Empty</option>
                  </select>
                </div>
              </div>
              <div class="form-group row movement">
                <label for="" class="col-sm-2 col-form-label text-right">Origin</label>
                <div class="col-lg-2">
                  <input type="text" class="form-control zipinput" kind="origin" autocomplete="new-password" name="" value="" placeholder="Zip Code">
                  <small class="invalid-feedback font-italic" style="position:relative; width:300px">This field cannot be empty.</small>
                </div>
                <div class="col-lg-2" readonly>
                  <input type="text" class="form-control stateInput" name="" value="" placeholder="State" readonly disabled>
                </div>
                <div class="col-lg-3">
                  <input type="text" class="form-control cityInput" name="" value="" placeholder="City" readonly disabled>
                </div>
                <div class="col-lg-2">
                  <select class="form-control mov-type" name="" data-is-valid=true>
                    <option value="L" selected>Loaded</option>
                    <option value="E">Empty</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-3 text-success add-extra-stop" role="button">
                  <i class="fa fa-plus"></i> Extra Stop
                </div>
              </div>
              <div class="form-group row movement">
                <label for="" class="col-sm-2 col-form-label text-right">Destination</label>
                <div class="col-lg-2">
                  <input type="text" class="form-control zipinput" kind="destination" autocomplete="new-password" name="" value="" placeholder="Zip Code">
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
                  <div class="form-control total_distance skip-validation d-flex justify-content-center" readonly>
                    0
                  </div>
                  <!-- <input type="text" class="form-control total_distance" name="" value="" placeholder="Total Miles" readonly disabled> -->
                  <small class="font-weight-light grey-font font-italic">All destinations must be captured to calculate miles.</small>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label text-right">Appointment</label>
                <div class="col-lg-5">
                  <input type="date" class="form-control appointment date" id="date-field" name="" value="">
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
          <div class="tab-pane fade" id="conveyance-details-pane" role="tabpanel" aria-labelledby="conveyance-details-tab">
            <form onsubmit="false">
              <div class="form-group row">
                <label for="" class="col-form-label col-sm-2 text-right">Tractor</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control popup-input truckid" id-display="#truck-popup-list-modal" type="text" autocomplete="new-password" db-id="" name="" value="" placeholder="Tractor Number">
                  <div class="invalid-feedback font-italic"></div>
                  <div id="truck-popup-list-modal" class="popup-list mt-3" style="display: none; z-index: 9999">
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-form-label col-sm-2 text-right">Driver(s)</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control popup-input driverid" id-display="#driver-popup-list-modal" type="text" autocomplete="new-password" db-id="" name="" value="" placeholder="Type Driver Name">
                  <div id="driver-popup-list-modal"  target="#listed-drivers" type="multiple" class="popup-list mt-3" style="display: none; z-index: 9999"></div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-5 offset-2" id="listed-drivers">
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane fade" id="trip-confirmation-pane" role="tabpanel" aria-labelledby="trip-confirmation-tab">
            <form onsubmit="return false">
              <div class="trip-contents">
                <div class="row">
                  <div class="col-sm-2 offset-1 text-right">
                    Trip
                  </div>
                  <div class="col-sm-9 grey-font">
                    <p class="d-inline"><span class="confirm-trip-info"></span></p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-2 offset-1 text-right">
                    Truck
                  </div>
                  <div class="col-sm-9 grey-font">
                    <p class="d-inline"><span class="confirm-truck-number"></span></p>
                    <p class="d-inline font-weight-light font-italic">(<span class="confirm-truck-plates"></span>)</p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-2 offset-1 text-right">
                    Trailer
                  </div>
                  <div class="col-sm-9 grey-font">
                    <p class="d-inline"><span class="confirm-trailer-number"></span></p>
                    <p class="d-inline font-weight-light font-italic">(<span class="confirm-trailer-plates"></span>)</p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-2 offset-1 text-right">
                    Driver(s)
                  </div>
                  <div class="col-sm-9 mb-2 grey-font confirm-driver-list">
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-2 offset-1 text-right">
                    Route
                  </div>
                  <div class="col-sm-9 grey-font" id="movement-confirmation">
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-2 offset-1 text-right">
                    Appointment
                  </div>
                  <div class="col-sm-9 grey-font" id="linehaul-appointment">
                    <p>
                      <span class="date"></span>
                      <span class="hour"></span>:<span class="minutes"></span>
                    </p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-2 offset-1 text-right">
                    Miles
                  </div>
                  <div class="col-sm-9 grey-font">
                    <p class="d-inline"><span class="total-miles"></span></p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-2 offset-1 text-right">
                    Rate
                  </div>
                  <div class="col-sm-9 grey-font">
                    <p class="d-inline">$ <span class="trip-rate-confirmation"></span></p>
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-sm-2 offset-1 text-right">
                    RPM
                  </div>
                  <div class="col-sm-9 grey-font">
                    <p class="d-inline">$ <span class="rpm-confirmation"></span></p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-2 offset-1 text-right">
                    Broker
                  </div>
                  <div class="col-sm-9 grey-font">
                    <p class="d-inline brokerid-confirmation"></p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-2 offset-1 text-right">
                    Reference
                  </div>
                  <div class="col-sm-9 grey-font">
                    <p class="d-inline broker-reference-confirmation"></p>
                  </div>
                </div>

              </div>
            </form>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <div class="next-pane-buttons">
          <button type="button" class="btn btn-primary next-pane disabled" disabled>Next</button>
        </div>
        <div class="add-trip-buttons" style="display: none">
          <button type="button" class="btn btn-primary add-linehaul next-pane disabled" disabled>Add Trip</button>
        </div>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
