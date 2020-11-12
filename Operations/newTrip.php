<?php

$root = $_SERVER['DOCUMENT_ROOT'] . "/plsuite";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- Metro 4 -->
    <link rel="stylesheet" href="/plsuite/Resources/Metro/build/css/metro-all.css">
    <!-- <link rel="stylesheet" href="https://cdn.metroui.org.ua/v4.3.2/css/metro-all.min.css"> -->

</head>
<body class="d-flex flex-column h-vh-100">
  <div class="container">
    <h1>Add New Trip</h1>
    <div data-role="accordion" >
        <div class="frame active">
            <div class="heading">Client Information</div>
            <div class="content p-6">
                <form>
                  <div class="form-group">
                    <label for="">Client Name</label>
                    <input type="text" class="metro-input" name="" value="">
                    <small class="text-muted">You need to select an item from the dropdown</small>
                  </div>
                  <div class="form-group">
                    <label for="">Client Contact</label>
                    <input type="text" class="metro-input" name="" value="">
                  </div>
                  <div class="form-group">
                    <label for="">Client Reference</label>
                    <input type="text" class="metro-input" placeholder="" name="" value="">
                  </div>
                </form>
            </div>
        </div>
        <div class="frame">
            <div class="heading">Conveyance Information</div>
            <div class="content p-6">
              <div class="form-group">
                <label for="">Trailer Number</label>
                <input type="text" class="metro-input" name="" value="">
                <small class="text-muted">You need to select an item from the dropdown</small>
              </div>
              <div class="form-group">
                <label for="">Tractor Number</label>
                <input type="text" class="metro-input" name="" value="">
                <small class="text-muted">You need to select an item from the dropdown</small>
              </div>
              <div class="form-group">
                <label for="">Driver(s)</label>
                <input type="text" class="metro-input" name="" value="">
                <small class="text-muted">You need to select an item from the dropdown</small>
              </div>
            </div>
        </div>
        <div class="frame">
            <div class="heading">Trip Information</div>
            <div class="content p-6">
              <div class="row">
                <div class="cell-md-6">
                  <label for="">Pickup</label>
                  <input type="text" class="metro-input" name="" value="">
                </div>
                <div class="cell-md-3">
                  <label for="">Appt Date</label>
                  <input type="date" class="metro-input" name="" value="">
                </div>
                <div class="cell-md-3">
                  <label for="">Appt Time</label>
                  <div class="d-flex">
                    <input type="text" class="metro-input mr-1" name="" value="">
                    <input type="text" class="metro-input ml-1" name="" value="">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="cell-md-6">
                  <label for="">Stop 1</label>
                  <input type="text" class="metro-input" name="" value="">
                </div>
                <div class="cell-md-3">
                  <label for="">Appt Date</label>
                  <input type="date" class="metro-input" name="" value="">
                </div>
                <div class="cell-md-3">
                  <label for="">Appt Time</label>
                  <div class="d-flex">
                    <input type="text" class="metro-input mr-1" name="" value="">
                    <input type="text" class="metro-input ml-1" name="" value="">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="cell-md-6">
                  <label for="">Stop 2</label>
                  <input type="text" class="metro-input" name="" value="">
                </div>
                <div class="cell-md-3">
                  <label for="">Appt Date</label>
                  <input type="date" class="metro-input" name="" value="">
                </div>
                <div class="cell-md-3">
                  <label for="">Appt Time</label>
                  <div class="d-flex">
                    <input type="text" class="metro-input mr-1" name="" value="">
                    <input type="text" class="metro-input ml-1" name="" value="">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="cell-md-6">
                  <label for="">Stop 3</label>
                  <input type="text" class="metro-input" name="" value="">
                </div>
                <div class="cell-md-3">
                  <label for="">Appt Date</label>
                  <input type="date" class="metro-input" name="" value="">
                </div>
                <div class="cell-md-3">
                  <label for="">Appt Time</label>
                  <div class="d-flex">
                    <input type="text" class="metro-input mr-1" name="" value="">
                    <input type="text" class="metro-input ml-1" name="" value="">
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
    <button type="button" class="button large primary mt-2 float-right" name="button">Confirm Info</button>
  </div>

  <div class="p-2" data-role="window">
      Drag this window on the caption.
  </div>

    <script src="/plsuite/Resources/JQuery/jquery-3.2.1.min.js" charset="utf-8"></script>
    <script src="/plsuite/Resources/Metro/build/js/metro.min.js"></script>
    <script src="/plsuite/Resources/fa_5/js/fontawesome-all.min.js" data-auto-replace-svg="nest" charset="utf-8"></script>
</body>
</html>
