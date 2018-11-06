<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="" onsubmit="return false" id="add_user_form">
          <div class="form-group row">
            <label for="" class="col-form-label col-sm-2 text-right">First Name</label>
            <div class="col-sm-5">
              <input autocomplete="new-password" id="add_user_fname" type="text" class="form-control validate-text" name="" value="">
            </div>
          </div>
          <div class="form-group row">
            <label for="" class="col-form-label col-sm-2 text-right">Last Name</label>
            <div class="col-sm-5">
              <input autocomplete="new-password" id="add_user_lname" type="text" class="form-control validate-text" name="" value="">
            </div>
          </div>
          <div class="form-group row">
            <label for="" class="col-form-label col-sm-2 text-right">User Name</label>
            <div class="col-sm-5">
              <div class="input-group">
                <input autocomplete="new-password" id="add_user_uname" type="text" class="form-control" name="" value="">
                <div class="input-group-append" id="username-validation-addon">
                  <span class="input-group-text normal-state active"><i class="fas fa-minus"></i></span>
                  <span class="input-group-text valid-state text-success d-none"><i class="fas fa-check"></i></span>
                  <span class="input-group-text invalid-state text-danger d-none"><i class="fas fa-times"></i></span>
                  <span class="input-group-text loading-state text-info d-none"><i class="fas fa-spinner fa-spin"></i></span>
                </div>
                <small class="invalid-feedback">This usernames is invalid, or has been taken. Please try a different one</small>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label for="" class="col-form-label col-sm-2 text-right">E-Mail</label>
            <div class="col-sm-5">
              <input autocomplete="new-password" type="text" id="add_user_email" class="form-control validate-text" name="" value="">
            </div>
          </div>
          <div class="form-group row">
            <label for="" class="col-form-label col-sm-2 text-right">User Type</label>
            <div class="col-sm-3">
              <select class="form-control text-center" id="add_user_type" name="">
                <option value="Global">Global</option>
                <option value="Broker">Broker</option>
              </select>
              <small class="form-text text-muted" style="width: 500px"><b>Global </b>users have access to the entire Load Management System, restricted  by the permissions.</small>
              <small class="form-text text-muted" style="width: 500px"><b>Broker </b> users only have access to BL Tool, restricting it's content only to loads pertaining to them.</small>
            </div>
          </div>
          <div class="form-group row" id="broker-field" style="display: none">
            <label for="broker-name" class="col-sm-2 col-form-label text-right">Broker</label>
            <div class="col-md-7">
              <div class="input-group">
                <input type="text" class="form-control popup-input selected-broker validate-text" id="user-broker" id-display="#broker-popup-list" aria-describedby="addBrokerotf" id="brokerName" type="text" autocomplete="new-password" db-id="" name="" value="" placeholder="Broker Name">
              </div>
              <div id="broker-popup-list" class="popup-list mt-3" style="display: none; z-index: 9999"></div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary disabled" disabled name="button" id="add-user-btn">Add User</button>
      </div>
    </div>
  </div>
</div>
