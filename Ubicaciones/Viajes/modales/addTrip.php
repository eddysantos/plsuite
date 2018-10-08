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
            <a class="nav-link custom active" id="trip-details-tab" data-toggle="tab" next-window="" previous-window="" href="#trip-details-pane" role="tab" aria-controls="trip-details" aria-selected="true">Trip Details</a>
          </li>
          <li class="nav-item">
            <a class="nav-link custom" id="lh-details-tab" data-toggle="tab" href="#lh-details-pane" role="tab" aria-controls="lh-details" aria-selected="true">Linehaul Details</a>
          </li>
          <li class="nav-item">
            <a class="nav-link custom"id="conveyance-details-tab" data-toggle="tab" href="#" role="tab" aria-controls="conveyance-details" aria-selected="true">Conveyance</a>
          </li>
          <li class="nav-item">
            <a class="nav-link custom"id="trip-confirmation-tab" data-toggle="tab" href="#" role="tab" aria-controls="trip-confirmation" aria-selected="true">Confirm Trip</a>
          </li>
        </ul>

        <div class="progress mb-3" style="height: 2px">
          <div class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <div class="tab-content" id="trip-details-content">
          <div class="tab-pane fade show active" id="trip-details-pane" role="tabpanel" aria-labelledby="trip-details-tab">
            <form class="" action="index.html" onsubmit="return false;" method="post">

              <div class="form-group row">
                <label for="trailer-number" class="col-sm-2 offset-2 col-form-label text-right">Trailer</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" name="" value="" placeholder="Trailer Number Here">
                </div>
              </div>

              <div class="form-group row">
                <label for="broker-name" class="col-sm-2 offset-2 col-form-label text-right">Broker</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" name="broker-name" value="" placeholder="Broker Name Here">
                </div>
              </div>

              <div class="form-group row">
                <label for="broker-reference" class="col-sm-2 offset-2 col-form-label text-right">Reference</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" name="broker-reference" value="" placeholder="Broker Name Here">
                </div>
              </div>

              <div class="form-group row">
                <label for="trip-rate" class="col-sm-2 offset-2 col-form-label text-right">Rate</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" name="trip-rate" value="" placeholder="Broker Name Here">
                </div>
              </div>

            </form>
          </div>
          <div class="tab-pane fade" id="lh-details-pane" role="tabpanel" aria-labelledby="lh-details-tab">
            <form onsubmit="return false;">
              <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label text-right">Origin</label>
                <div class="col-lg-5">
                  <input type="text" class="form-control" name="" value="" placeholder="City">
                </div>
                <div class="col-lg-2">
                  <input type="text" class="form-control" name="" value="" placeholder="State">
                </div>
                <div class="col-lg-2">
                  <input type="text" class="form-control" name="" value="" placeholder="Zip Code">
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label text-right">Destination</label>
                <div class="col-lg-5">
                  <input type="text" class="form-control" name="" value="" placeholder="City">
                </div>
                <div class="col-lg-2">
                  <input type="text" class="form-control" name="" value="" placeholder="State">
                </div>
                <div class="col-lg-2">
                  <input type="text" class="form-control" name="" value="" placeholder="Zip Code">
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label text-right">Appointment</label>
                <div class="col-lg-5">
                  <input type="date" class="form-control" name="" value="">
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
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary next-pane" next-window="" previous-window="">Next</button>
      </div>
    </div>
  </div>
</div>
