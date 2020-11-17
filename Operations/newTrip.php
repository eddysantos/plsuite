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
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuBCFwHZCWMgyeTJ1MI32sXlGnJtIIsUA&libraries=places">
    </script>
</head>
<body class="d-flex flex-column h-vh-100">
  <div class="container">
    <h1>Add New Trip</h1>
    <div data-role="accordion" >
      <div class="frame active">
          <div class="heading">Trip Information</div>
          <div class="content py-2">
            <div class="form-group">
              <label for="">Client Name</label>
              <input type="text" class="input input-small" name="" value="">
              <small class="text-muted">You need to select an item from the dropdown</small>
            </div>
            <div class="form-group">
              <label for="">Client Contact</label>
              <input type="text" class="input input-small" name="" value="">
            </div>
            <div class="row">
              <div class="cell-md-6">
                <div class="form-group">
                  <label for="">Client Reference</label>
                  <input type="text" class="input input-small" placeholder="" name="" value="">
                </div>
              </div>
              <div class="cell-md-6">
                <div class="form-group">
                  <label for="">Trip Rate</label>
                  <input type="text" class="input input-small" data-role="input" data-prepend="<span class='mif-money'></span>" placeholder="" name="" value="">
                </div>
              </div>
            </div>
            <div class="mb-2 mt-6" id="stopList">
              <div class="mb-1 stop">
                <h5>Pickup</h5>
                <div class="row">
                  <div class="cell-md-6">
                    <label for="">Location</label>
                    <input type="text" class="input input-small google-autocomplete" placeholder="Type the location zip, name or address..." name="" value="">
                    <div class="gac-info pt-2">
                        <h5 class="name m-0"></h5>
                        <div class="">
                            <span class="street_number"></span> <span class="route"></span>
                        </div>
                        <div class="">
                            <span class="locality"></span> <span class="administrative_area_level_1"></span> <span class="postal_code"></span>
                        </div>
                        <div>
                            <span class="country"></span>
                        </div>
                    </div>
                  </div>
                  <div class="cell-md-3">
                    <label for="">Appt From</label>
                    <input type="date" class="input input-small" name="" value="">
                    <div class="d-flex mt-1">
                      <select class="input-small mr-1" data-role="select" data-filter="false"  name="">
                          <option value="0">0</option>
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
                      </select>
                      <select class="input-small ml-1" data-role="select" data-filter="false"  name="">
                          <option value="00">00</option>
                          <option value="15">15</option>
                          <option value="30">30</option>
                          <option value="45">45</option>
                      </select>
                    </div>
                  </div>
                  <div class="cell-md-3">
                    <label for="">Appt To</label>
                    <input type="date" class="input input-small" name="" value="">
                    <div class="d-flex mt-1">
                      <select class="input-small mr-1" data-role="select" data-filter="false"  name="">
                          <option value="0">0</option>
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
                      </select>
                      <select class="input-small ml-1" data-role="select" data-filter="false"  name="">
                          <option value="00">00</option>
                          <option value="15">15</option>
                          <option value="30">30</option>
                          <option value="45">45</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>

              <div class="border-top bd-gray p-2 mb-1 stop">
                  <div class="d-flex flex-justify-between w-100">
                      <h5 class="stop-heading">Stop 1</h5>
                      <button class="button link float-left fg-red float-right remove-stop"> <span class="mif-cross-light"></span> </button>
                  </div>
                  <div class="row">
                      <div class="cell-md-6">
                        <label for="">Location</label>
                        <input type="text" class="input input-small google-autocomplete" placeholder="Type the location zip, name or address..." name="" value="">
                        <div class="gac-info pt-2">
                            <h5 class="name m-0"></h5>
                            <div class="">
                                <span class="street_number"></span> <span class="route"></span>
                            </div>
                            <div class="">
                                <span class="locality"></span> <span class="administrative_area_level_1"></span> <span class="postal_code"></span>
                            </div>
                            <div>
                                <span class="country"></span>
                            </div>
                        </div>
                      </div>
                      <div class="cell-md-3">
                        <label for="">Appt From</label>
                        <input type="date" class="input input-small" name="" value="">
                        <div class="d-flex mt-1">
                          <select class="input-small mr-1" data-role="select" data-filter="false"  name="">
                              <option value="0">0</option>
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
                          </select>
                          <select class="input-small ml-1" data-role="select" data-filter="false"  name="">
                              <option value="00">00</option>
                              <option value="15">15</option>
                              <option value="30">30</option>
                              <option value="45">45</option>
                          </select>
                        </div>
                      </div>
                      <div class="cell-md-3">
                        <label for="">Appt To</label>
                        <input type="date" class="input input-small" name="" value="">
                        <div class="d-flex mt-1">
                          <select class="input-small mr-1" data-role="select" data-filter="false"  name="">
                              <option value="0">0</option>
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
                          </select>
                          <select class="input-small ml-1" data-role="select" data-filter="false"  name="">
                              <option value="00">00</option>
                              <option value="15">15</option>
                              <option value="30">30</option>
                              <option value="45">45</option>
                          </select>
                        </div>
                      </div>
                  </div>
                <div class="row">
                  <div class="cell-3 offset-9">
                    <select class="input input-small" name="">
                      <option value="L">Loaded</option>
                      <option value="E">Empty</option>
                      <!-- <option value="EAL">Emtpy As Loaded</option> -->
                    </select>
                  </div>
                </div>
              </div>

            </div>
            <button type="button" class="button secondary" id="addStopBtn" name="button">Add Stop</button>


          </div>
      </div>
      <div class="frame">
          <div class="heading">Conveyance Information</div>
          <div class="content py-2">
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

    </div>
    <button type="button" class="button large primary mt-2 float-right" id="confirmInfoBtn" name="button">Confirm Info</button>
  </div>


    <script src="/plsuite/Resources/JQuery/jquery-3.2.1.min.js" charset="utf-8"></script>
    <script src="/plsuite/Resources/Metro/build/js/metro.min.js"></script>
    <script src="/plsuite/Resources/fa_5/js/fontawesome-all.min.js" data-auto-replace-svg="nest" charset="utf-8"></script>
    <script src="js/newTrip.js" charset="utf-8"></script>

</body>
</html>
