<div class="modal fade show" id="bugReportModal" tabindex="-1" role="dialog" aria-labelledby="bugReport" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Welcome to the bug and suggestions box!</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
         </button>
      </div>
      <div class="modal-body">
        <p>You can use this handy tool to report a problem (bug!), or make a suggestion for a change on the website!</p>
        <form class="form-group" onsubmit="false">
          <div class="form-row">
            <label for="bugReportType" class="col-form-label col-3">Report Type</label>
            <div class="col-9 form-group">
              <select class="form-control" name="bugReportType" id="bugReportType">
                <option value="Bug">Bug</option>
                <option value="Suggestion">Suggestion</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <label for="bugReportType" class="col-form-label col-3">Website Area</label>
            <div class="col-9 form-group">
              <select class="form-control" name="bugReportArea" id="bugReportArea">
                <option value="Trips">Trips</option>
                <option value="Drivers">Drivers</option>
                <option value="Trucks">Trucks</option>
                <option value="Trailers">Trailers</option>
                <option value="Brokers">Brokers</option>
                <option value="Other">Other</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <label for="bugReportSubject" class="col-form-label col-3">Subject</label>
            <div class="col-9 form-group">
              <input type="text" class="form-control" name="bugReportSubject" id="bugReportSubject" placeholder="Brief description of issue" value="">
            </div>
          </div>
          <label for="reportContent">Description</label>
          <textarea name="reportContent" class="form-control" rows="8" name="bugReportDescription" id="bugReportDescription" placeholder="Extensive description of issue"></textarea>
          <input type="text" class="form-control" name="reporting_user" id="bug_reported_by" value="<?php echo $_SESSION['user_info']['Nombre'] . " " .  $_SESSION['user_info']['Apellido']?>" hidden>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary" id="submitBugReport" name="button">Submit</button>
      </div>
    </div>
  </div>
</div>
