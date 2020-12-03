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
    <link rel="stylesheet" href="/plsuite/Resources/CSS/plsuite2/main.css">
    <!-- Alertify -->
    <link rel="stylesheet" href="/plsuite/Resources/alertify/css/alertify.min.css">
    <link rel="stylesheet" href="/plsuite/Resources/alertify/css/themes/default.min.css">
    <link rel="stylesheet" href="/plsuite/Resources/CSS/plsuite2/autocomplete.css">
    <!-- <link rel="stylesheet" href="https://cdn.metroui.org.ua/v4.3.2/css/metro-all.min.css"> -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuBCFwHZCWMgyeTJ1MI32sXlGnJtIIsUA&libraries=places">
    </script>
</head>
<body class="d-flex flex-column h-vh-100">
  <div class="container pb-10" id="tripInfoContainer">
    <h1>Add New Trip</h1>
    <div data-role="accordion" >
      <div class="frame active">
          <div class="heading">Trip Information</div>
          <div class="content py-2">
            <div class="form-group">
              <label for="">Client Name</label>
              <input type="text" name="client_name" class="input input-small" trip-input data-role="input" id="clientInput"  value="CH Robinson" data-dbid="1">
            </div>
            <!-- <div class="form-group">
              <label for="">Client Contact</label>
              <input type="text" class="input input-small" name="" value="">
            </div> -->
            <div class="row">
              <div class="cell-md-6">
                <div class="form-group">
                  <label for="">Client Reference</label>
                  <input type="text" class="input input-small" data-role="input" trip-input id="client_reference" placeholder="" name="client_reference" value="32527282635367">
                </div>
              </div>
              <div class="cell-md-6">
                <div class="form-group">
                  <label for="">Trip Rate</label>
                  <input type="number" class="input input-small" trip-input data-role="input" data-prepend="<span class='mif-money'></span>" placeholder="" name="trip_rate" value="2500">
                </div>
              </div>
            </div>
            <div class="mb-2 mt-6" id="stopList">
              <div class="mb-1 trip-stop">
                <h5 stop-input class="m-0" name="stop_label">Pickup</h5>
                <div class="row">
                  <div class="cell-md-6">
                    <label for="">Location</label>
                    <input type="text" class="input input-small" name="google_address" stop-input google-autocomplete data-role="input" trip-info placeholder="Type the location zip, name or address..." value="">
                    <div class="gac-info pt-2">
                        <h5 class="name m-0" stop-input name="location_name"></h5>
                        <div class="">
                            <span class="street_number" stop-input name="street_number"></span> <span class="route" stop-input name="street_address">You must select a location from the dropdown</span>
                        </div>
                        <div class="">
                            <span class="locality" stop-input name="city"></span> <span class="administrative_area_level_1" stop-input name="state"></span> <span class="postal_code" stop-input name="zip_code"></span>
                        </div>
                        <div>
                            <span class="country" stop-input name="country"></span>
                        </div>
                    </div>
                  </div>
                  <div class="cell-md-3">
                    <label for="">Appt From</label>
                    <input type="date" class="input input-small" stop-input data-role="input" name="appt_date_from" value="2020-11-22">
                    <div class="d-flex mt-1">
                      <select class="input-small mr-1" stop-input data-role="select" data-filter="false"  name="appt_hour_from">
                          <option value="">Hour</option>
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
                          <option value="10" selected>10</option>
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
                      <select class="input-small ml-1" stop-input data-role="select" data-filter="false"  name="appt_minute_from">
                          <option value="">Minute</option>
                          <option value="00">00</option>
                          <option value="15">15</option>
                          <option value="30" selected>30</option>
                          <option value="45">45</option>
                      </select>
                    </div>
                  </div>
                  <div class="cell-md-3">
                    <label for="">Appt To</label>
                    <input type="date" class="input input-small" stop-input data-role="input" name="appt_date_to" value="2020-11-22">
                    <div class="d-flex mt-1">
                      <select class="input-small mr-1" stop-input data-role="select" data-filter="false"  name="appt_hour_to">
                          <option value="">Hour</option>
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
                          <option value="17" selected>17</option>
                          <option value="18">18</option>
                          <option value="19">19</option>
                          <option value="20">20</option>
                          <option value="21">21</option>
                          <option value="22">22</option>
                          <option value="23">23</option>
                      </select>
                      <select class="input-small ml-1" stop-input data-role="select" data-filter="false"  name="appt_minute_to">
                          <option value="">Minute</option>
                          <option value="00">00</option>
                          <option value="15">15</option>
                          <option value="30">30</option>
                          <option value="45" selected>45</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mb-1 mt-4 trip-stop">
                  <div class="d-flex flex-justify-between w-100">
                      <h5 class="stop-heading m-0" stop-input name="stop_label">Stop 1</h5>
                      <button class="button link float-left fg-red float-right remove-stop" style="height: auto"> <span class="mif-cross-light"></span> </button>
                  </div>
                  <div class="row">
                      <div class="cell-md-6">
                        <label for="">Location</label>
                        <input type="text" class="input input-small" google-autocomplete calculate-distance stop-input data-role="input" placeholder="Type the location zip, name or address..." name="google_address" value="">
                        <div class="gac-info pt-2">
                            <h5 class="name m-0" stop-input name="location_name"></h5>
                            <div class="">
                                <span class="street_number" stop-input name="street_number"></span> <span class="route" stop-input name="street_address">You must select a location from the dropdown</span>
                            </div>
                            <div class="">
                                <span class="locality" stop-input name="city"></span> <span class="administrative_area_level_1" stop-input name="state"></span> <span class="postal_code" stop-input name="zip_code"></span>
                            </div>
                            <div>
                                <span class="country" stop-input name="country"></span>
                            </div>
                        </div>
                      </div>
                      <div class="cell-md-3">
                        <label for="">Appt From</label>
                        <input type="date" class="input input-small" stop-input data-role="input" name="appt_date_from" value="2020-11-26">
                        <div class="d-flex mt-1">
                          <select class="input-small mr-1" stop-input data-role="select" data-filter="false"  name="appt_hour_from">
                              <option value="">Hour</option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                              <option value="7">7</option>
                              <option value="8">8</option>
                              <option value="9">9</option>
                              <option value="10" selected>10</option>
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
                          <select class="input-small ml-1" stop-input data-role="select" data-filter="false"  name="appt_minute_from">
                              <option value="">Minute</option>
                              <option value="00">00</option>
                              <option value="15">15</option>
                              <option value="30" selected>30</option>
                              <option value="45">45</option>
                          </select>
                        </div>
                      </div>
                      <div class="cell-md-3">
                        <label for="">Appt To</label>
                        <input type="date" class="input input-small" stop-input data-role="input" name="appt_date_to" value="2020-11-26">
                        <div class="d-flex mt-1">
                          <select class="input-small mr-1" stop-input data-role="select" data-filter="false"  name="appt_hour_to">
                              <option value="">Hour</option>
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
                              <option value="13" selected>13</option>
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
                          <select class="input-small ml-1" stop-input data-role="select" data-filter="false"  name="appt_minute_to">
                              <option value="">Minute</option>
                              <option value="00">00</option>
                              <option value="15">15</option>
                              <option value="30" selected>30</option>
                              <option value="45">45</option>
                          </select>
                        </div>
                        <div class="f-flex mt-1">
                          <select class="input input-small" stop-input name="movement_type">
                            <option value="L" selected>Loaded</option>
                            <option value="E">Empty</option>
                            <!-- <option value="EAL">Emtpy As Loaded</option> -->
                          </select>
                          <div class="text-right">
                            <span class="miles" stop-input name="miles">0</span> Miles
                          </div>
                        </div>
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
              <input type="text" class="input input-small" data-role="input" trailer-input trip-input name="trailer" value="U90003">
            </div>
            <div class="form-group">
              <label for="">Conveyance</label>
              <div class="row">
                <div class="col-lg-4">
                  <input type="text" class="input input-small" truck-input data-role="input" trip-input name="tractor" data-prepend="Truck Number" value="T049">
                </div>
                <div class="col-lg-4">
                  <input type="text" class="input input-small" driver-input data-role="input" trip-input name="driver" data-prepend="Main Driver" name="" value="Antonio Soto">
                </div>
                <div class="col-lg-4">
                  <input type="text" class="input input-small" driver-input data-role="input" trip-input name="team_driver" data-prepend="Team Driver" value="Jorge Mancillas">
                </div>
              </div>
            </div>
          </div>
      </div>

    </div>
    <button type="button" class="button large primary mt-2 float-right" id="confirmInfoBtn" name="button">Confirm Info</button>
  </div>


    <script src="/plsuite/Resources/JQuery/jquery-3.2.1.min.js" charset="utf-8"></script>
    <script src="/plsuite/Resources/JQuery/jquery.autocomplete.js" charset="utf-8"></script>
    <script src="/plsuite/Resources/swal/swal.min.js" charset="utf-8"></script>
    <script src="/plsuite/Resources/swal8/swal8.min.js" charset="utf-8"></script>
    <script src="/plsuite/Resources/alertify/alertify.min.js" charset="utf-8"></script>

    <script src="/plsuite/Resources/Metro/build/js/metro.min.js"></script>
    <script src="/plsuite/Resources/fa_5/js/fontawesome-all.min.js" data-auto-replace-svg="nest" charset="utf-8"></script>
    <script src="js/newTrip.js" charset="utf-8"></script>

</body>
</html>
