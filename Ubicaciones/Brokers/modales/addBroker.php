<div class="modal fade" id="addBrokerModal" tabindex="-1" role="dialog" aria-labelledby="addTrip" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="nb-id">Add new broker</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" role="button">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form onsubmit="return false;">
          <div class="form-group">
            <input class="form-control mb-3" id="nb-name" type="text" name="" value="" placeholder="Broker Name" autocomplete="new-password">
            <input class="form-control mb-3" id="nb-cname" type="text" name="" value="" placeholder="Main Contact Name" autocomplete="new-password">
            <div class="form-group row">
              <div class="col-lg-5">
                <input class="form-control mb-3" id="nb-ph" type="text" name="" value="" placeholder="Phone Number" autocomplete="new-password">
              </div>
              <div class="col-lg-3">
                <input class="form-control mb-3" id="nb-xt" type="text" name="" value="" placeholder="Ext" autocomplete="new-password">
              </div>
              <div class="col-lg-4">
                <input class="form-control mb-3" id="nb-cp" type="text" name="" value="" placeholder="Cell Phone" autocomplete="new-password">
              </div>
            </div>
            <input class="form-control mb-3" id="nb-em" type="text" name="" value="" placeholder="Main Contact Email" autocomplete="new-password">
            <div class="form-group row">
              <label for="" class="col-lg-4 col-form-label">Business Hours:</label>
              <div class="col-lg-4">
                <select class="form-control" id="nb-bf" name="">
                  <option value="">From:</option>
                  <option value="12:00 AM">12 AM</option>
                  <option value="1:00 AM">1 AM</option>
                  <option value="2:00 AM">2 AM</option>
                  <option value="3:00 AM">3 AM</option>
                  <option value="4:00 AM">4 AM</option>
                  <option value="5:00 AM">5 AM</option>
                  <option value="6:00 AM">6 AM</option>
                  <option value="7:00 AM">7 AM</option>
                  <option value="8:00 AM">8 AM</option>
                  <option value="9:00 AM">9 AM</option>
                  <option value="10:00 AM">10 AM</option>
                  <option value="11:00 AM">11 AM</option>
                  <option value="12:00 PM">12 PM</option>
                  <option value="1:00 PM">1 PM</option>
                  <option value="2:00 PM">2 PM</option>
                  <option value="3:00 PM">3 PM</option>
                  <option value="4:00 PM">4 PM</option>
                  <option value="5:00 PM">5 PM</option>
                  <option value="6:00 PM">6 PM</option>
                  <option value="7:00 PM">7 PM</option>
                  <option value="8:00 PM">8 PM</option>
                  <option value="9:00 PM">9 PM</option>
                  <option value="10:00 PM">10 PM</option>
                  <option value="11:00 PM">11 PM</option>
                </select>
              </div>
              <div class="col-lg-4">
                <select class="form-control" id="nb-bt" name="">
                  <option value="">To:</option>
                  <option value="12:00 AM">12 AM</option>
                  <option value="1:00 AM">1 AM</option>
                  <option value="2:00 AM">2 AM</option>
                  <option value="3:00 AM">3 AM</option>
                  <option value="4:00 AM">4 AM</option>
                  <option value="5:00 AM">5 AM</option>
                  <option value="6:00 AM">6 AM</option>
                  <option value="7:00 AM">7 AM</option>
                  <option value="8:00 AM">8 AM</option>
                  <option value="9:00 AM">9 AM</option>
                  <option value="10:00 AM">10 AM</option>
                  <option value="11:00 AM">11 AM</option>
                  <option value="12:00 PM">12 PM</option>
                  <option value="1:00 PM">1 PM</option>
                  <option value="2:00 PM">2 PM</option>
                  <option value="3:00 PM">3 PM</option>
                  <option value="4:00 PM">4 PM</option>
                  <option value="5:00 PM">5 PM</option>
                  <option value="6:00 PM">6 PM</option>
                  <option value="7:00 PM">7 PM</option>
                  <option value="8:00 PM">8 PM</option>
                  <option value="9:00 PM">9 PM</option>
                  <option value="10:00 PM">10 PM</option>
                  <option value="11:00 PM">11 PM</option>
                </select>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary" id="addBrokerSubmit" name="button">Add Broker</button>
      </div>
    </div>
  </div>
</div>
