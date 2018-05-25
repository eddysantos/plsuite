<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';


echo "<link rel='stylesheet' href='/plsuite/Resources/CSS/invoiceControl.css'>";
require $root . '/plsuite/Resources/PHP/Utilities/header.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';




 ?>
<div class="container-fluid align-items-right d-flex align-content-center mb-3 position-sticky" style="margin-top: 65px">
  <h1 class="nb-id d-inline text-secondary">Invoice Control</h1>
  <!-- <div class="ml-5">
    <ul class="nav nav-pills" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="pending-invoice-tab" data-toggle="tab" href="#pending-invoice-trips" role="tab">Pending Invoice</a>
      </li>
      <li class="nav-item">
        <a class="nav-link"id="pending-payment-tab" data-toggle="tab" href="#pending-payment-trips" role="tab">Pending Payment</a>
      </li>
    </ul>
  </div> -->
</div>

<div class="container-fluid" style="overflow-y: scroll; height: calc(100vh - 190px);">
  <div class="h-100 w-100">
    <div class="tab-display" style="color: black !important; " id="pending-invoice-trips" role="tabpanel">
      <form class="form-inline justify-content-between" onsubmit="return false;">
        <div class="form-inline">
          <label class="form-control-label mr-3">To begin, type a trip or trailer number:</label>
          <input type="text" class="form-control" autocomplete="off" id="pending-invoice-trip-search" name="" value="">
        </div>
        <!-- <div class="form-group ml-2">
          <input type="checkbox" class="mr-2" name="" value="">
          <label for="">Check to show only pending payment</label>
        </div> -->
        <button type="button" class="btn btn-outline-secondary" id="pending-payments-toggle" active="0" name="button">Show pending payment only</button>
      </form>
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Trip Number</th>
            <th>Trailer</th>
            <th>From - To</th>
            <th>Rate</th>
            <th>Invoice</th>
          </tr>
        </thead>
        <tbody id="trip-invoice-search">

        </tbody>
      </table>
    </div>

    <div class="tab-display" style="color: black !important; display: none" id="pending-invoice-trip-details" role="tabpanel">
      <div class="sub-section-header mb-5">
        <a class="ml-1 mr-3 active tab-change" href="#pending-invoice-trips" role="button"><i class="fa fa-chevron-left"></i></a><span id="trip-number"><span>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <form class="form-group" id="lh-details-form">
            <fieldset id="lh-fields" readonly disabled>
              <input type="text" class="linehaulid" id="linehaulid" name="" value="" hidden>
              <div class="form-row">
                <label for="" class="col-form-label col-md-2">Broker</label>
                <div class="form-group col-md-6">
                  <input type="text" class="form-control broker" id="broker" name="" value="">
                </div>
                <label for="" class="col-form-label col-md-2 text-right">Reference</label>
                <div class="form-group col-md-2">
                  <input type="text" class="form-control" id="broker_reference" name="" value="">
                </div>
              </div>

              <div class="form-row">
                <label for="" class="col-form-label col-md-2">Origin</label>
                <div class="form-group col-md-3">
                  <input type="text" class="form-control disabled" id="ocity" name="" value="" readonly>
                </div>
                <div class="form-group col-md-1">
                  <input type="text" class="form-control disabled" name="" id="ostate" value="" readonly>
                </div>
                <label for="" class="col-form-label offset-2 col-md-2 text-right">Trip Rate</label>
                <div class="form-group col-md-2">
                  <div class="input-group">
                    <div class="input-group-addon p-0">
                      <span class="input-group-text">$</span>
                    </div>
                    <input type="text" class="form-control" id="rate" name="" value="">
                  </div>
                </div>
              </div>

              <div class="form-row">
                <label for="" class="col-form-label col-md-2">Destination</label>
                <div class="form-group col-md-3">
                  <input type="text" class="form-control readonly" id="dcity" name="" value="" disabled>
                </div>
                <div class="form-group col-md-1">
                  <input type="text" class="form-control readonly" name="" id="dstate" value="" disabled>
                </div>
              </div>

              <div class="form-row">
                <label for="" class="col-form-label col-md-2">Departure</label>
                <div class="form-group col-md-4">
                  <input type="date" class="form-control readonly" id="departure_date" readonly name="" value="">
                </div>
                <div class="form-group col-md-1">
                  <input class="form-control departure hour readonly" readonly id="departure_time_hour" name="departure_time_hour">
                </div>
                <div class="form-group col-md-1 pl-0">
                  <input class="form-control departure minute" id="departure_time_minute" name="departure_time_minute">
                </div>
              </div>

              <div class="form-row">
                <label for="" class="col-form-label col-md-2">Arrival</label>
                <div class="form-group col-md-4">
                  <input type="date" class="form-control" id="arrival_date" name="" value="">
                </div>
                <div class="form-group col-md-1">
                  <input class="form-control arrival hour" id="arrival_time_hour" name="arrival_time_hour">
                </div>
                <div class="form-group col-md-1 pl-0">
                  <input class="form-control arrival minute" id="arrival_time_minute" name="arrival_time_minute">
                </div>
              </div>

            </fieldset>
          </form>
        </div>
        <div class="col-lg-4 offset-1">
          <form class="form-group" onsubmit="return false;">
            <div class="form-row">
              <label for="invoice_number" class="col-form-label col-lg-4">Invoice Number</label>
              <div class="form-group col-lg-8">
                <input type="text" id="invoice_number" class="form-control" name="" value="">
              </div>
            </div>
            <div class="form-row">
              <label for="invoice_amount" class="col-form-label col-lg-4">Invoice Amount</label>
              <div class="form-group col-lg-8">
                <div class="input-group">
                  <div class="input-group-addon">
                    <span class="input-group-text">$</span>
                  </div>
                  <input type="number" id="invoice_amount" class="form-control" name="" value="">
                </div>
              </div>
            </div>
            <div class="form-row">
              <label for="payment_due" class="col-form-label col-lg-4">Payment Due</label>
              <div class="form-group col-lg-8">
                <div class="input-group">
                  <div class="input-group-btn">
                      <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Days due</button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item set-due-date" due-days="10">10 Days</a>
                        <a class="dropdown-item set-due-date" due-days="20">20 Days</a>
                        <a class="dropdown-item set-due-date" due-days="30">30 Days</a>
                      </div>
                    </div>
                  <input type="date" id="payment_due" class="form-control" name="" value="">
                </div>
              </div>
            </div>
            <div class="form-row">
              <label for="payment_date" class="col-form-label col-lg-4">Payment Date</label>
              <div class="form-group col-lg-8">
                <input type="date" id="payment_date" class="form-control" name="" value="">
              </div>
            </div>
            <div class="form-row">
              <label for="check_number" class="col-form-label col-lg-4">Check Number</label>
              <div class="form-group col-lg-8">
                <input type="text" id="check_number" class="form-control" name="" value="">
              </div>
            </div>
            <div class="form-row">
              <label for="bank_name" class="col-form-label col-lg-4">Bank Name</label>
              <div class="form-group col-lg-8">
                <input type="text" id="bank_name" class="form-control" name="" value="">
              </div>
            </div>
            <div class="form-row">
              <label for="check_comments" class="col-form-label col-lg-4">Comments</label>
              <div class="form-group col-lg-8">
                <textarea name="check_comments" class="form-control" id="check_comments" rows="5"></textarea>
              </div>
            </div>
            <div class="text-right">
              <div class="form-group ">
                <?php if ($_SESSION['user_info']['ic_save'] == 1): ?>
                  <button type="button" id="save-invoice-info" class="btn btn-outline-success" name="button">Save Info</button>
                <?php endif; ?>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>


  </div>
  <!-- <table class="table table-striped">
    <tbody id="tripDashTable">
    </tbody>
  </table> -->
</div>

<?php
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>
<script src="js/invoiceControl.js" charset="utf-8"></script>
<!-- <script src="/plsuite/Ubicaciones/Viajes/js/trips.js" charset="utf-8"></script> -->
