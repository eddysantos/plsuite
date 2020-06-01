<!-- Modal para agregar POs -->
<div class="modal fade" id="addPoNumbersModal" tabindex="-1" role="dialog" aria-labelledby="addPOs" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Add New PO's</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-row">
          <div class="col">
            PO #
          </div>
          <div class="col-3">
            Date
          </div>
          <div class="col-2">
            Hour
          </div>
          <div class="col-2">
            Min
          </div>
        </div>
        <form class="" action="return false" id="addPoForm" method="post">
          <div class="form-row mb-1 po-line">
            <div class="col">
              <input type="text" class="form-control po-input" name="po_number" value="">
            </div>
            <div class="col-3">
              <input type="date" class="form-control po-input" name="po_date" value="">
            </div>
            <div class="col-2">
              <select class="custom-select po-input" name="po_hour">
                <option value="" selected>Select</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</2option>
                <option value="14">14</o3ption>
                <option value="15">15</o3ption>
                <option value="16">16</o3ption>
                <option value="17">17</o3ption>
                <option value="18">18</o3ption>
                <option value="19">19</o3ption>
                <option value="20">20</o3ption>
                <option value="21">21</o3ption>
                <option value="22">22</o3ption>
                <option value="23">23</o3ption>
                <option value="24">24</o3ption>
              </select>
            </div>
            <div class="col-2">
              <select class="custom-select po-input" name="po_minute">
                <option value="">Select</option>
                <option value="00">00</option>
                <option value="15">15</option>
                <option value="30">30</option>
                <option value="45">45</option>
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-dark" id="savePosBtn">Save POs</button>
      </div>
    </div>
  </div>
</div>
