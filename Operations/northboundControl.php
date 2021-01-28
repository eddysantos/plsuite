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
  <?php require $root . "/Resources/SiteUtils/SiteMenu.html" ?>
  <div class="compacted" data-role="navview" data-compact="md" style="flex-grow: 1">
    <?php require "OperationsMenu.html" ?>
    <div class="navview-content pt-2">
      <div class="container-fluid">
        <div class="d-flex flex-justify-between mb-2">
          <a href="newTrip.php" class="button primary small" name="button"><span class="mif-add"></span> New NB Trip</a>
          <div data-role="buttongroup" date-mode="one">
            <button class="button active small">All</button>
            <button class="button small">Pending</button>
            <button class="button small">Scheduled</button>
            <button class="button small">In Transit</button>
            <button class="button small">Delivered</button>
          </div>
          <!-- <div class="" data-role="buttongroup" data-mode="one">
            <button class="button small active">All</button>
            <button class="button small">Northbound</button>
            <button class="button small">Southbound</button>
          </div> -->
          <div class="" data-role="buttongroup" data-mode="one">
            <button class="button small active" name="button"><span class="mif-list"></span></button>
            <button class="button small" name="button"><span class="mif-stack"></span></button>
          </div>
          <div class="d-flex flex-justify-between">
            <form class="" style="max-width: 450px">
              <input type="text" data-role="input" class="input-small" data-search-button="true">
            </form>
          </div>
        </div>
        <div class=""> <!-- TRIP LIST CONTAINER -->

          <div class="border bd-grayBlue p-1 mb-1 border-radius">
            <div class="w-100 d-flex flex-justify-between">
              <div class="">
                <div class="">
                  <b>2000013</b>
                  <span class="fg-red">(<i>CH Robinson</i>)</span>
                </div>
                <div class="">
                  Laredo, TX - Atlanta, GA
                </div>
                <div class="fg-darkGray">
                  <span class="">1008 Miles</span>|<span>$2.25</span> RPM
                </div>
              </div>
              <div class="">
                <div class=""><b>Start Date:</b> 2020-01-01</div>
                <div class=""><b>Appt Time:</b> 2020-01-01</div>
              </div>
              <div class="text-right">
                <div class="">Miguel Velez</div>
                <div class="">T015</div>
                <div class="">NONZ976976</div>
              </div>
            </div>

            <div class="m-3 p-2 bg-light border-radius">
              Aqui pondremos detalles adicionales. Esta ventana se esconderá hasta que le hagan click al renglón.
            </div>
          </div>

        </div> <!-- TRIP LIST CONTAINER -->
        <div class="">
          <table class="table subcompact">
            <thead>
              <tr>
                <th>Trip</th>
                <th>Reference</th>
                <th>Client</th>
                <th>Origin</th>
                <th>Destination</th>
                <th>RPM</th>
                <th>Start Date</th>
                <th>Appt Date</th>
                <th>Appt Time</th>
                <th>Delivery</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>2001101</td>
                <td>3345639948</td>
                <td>James Transportation</td>
                <td>Laredo, TX</td>
                <td>Mechanicsburg, PA</td>
                <td>$2.00</td>
                <td>
                  <span>2020-01-01</span>
                  <button type="button" class="button mini light" name="button"><span class="mif-calendar"></span></button>
                  <div data-role="calendar" data-picker-mode="true" class="compact pos-absolute" data-show-header="false" data-show-footer="false" style="display: none"></div>
                </td>
                <td>
                  <span>2020-01-01</span>
                  <button type="button" class="button mini light" name="button"><span class="mif-calendar"></span></button>
                  <div data-role="calendar" data-picker-mode="true" class="compact pos-absolute" data-show-header="false" data-show-footer="false" style="display: none"></div>
                </td>
                <td>1:00</td>
                <td>
                  <span>2020-01-01</span>
                  <button type="button" class="button mini light" name="button"><span class="mif-calendar"></span></button>
                  <div data-role="calendar" data-picker-mode="true" class="compact pos-absolute" data-show-header="false" data-show-footer="false" style="display: none"></div>
                </td>
              </tr>
              <tr>
                <td>2001101</td>
                <td>3345639948</td>
                <td>James Transportation</td>
                <td>Laredo, TX</td>
                <td>Mechanicsburg, PA</td>
                <td>$2.00</td>
                <td>2020-11-01</td>
                <td>2020-11-05</td>
                <td>1:00</td>
                <td>2020-11-05</td>
              </tr>
              <tr>
                <td>2001101</td>
                <td>3345639948</td>
                <td>James Transportation</td>
                <td>Laredo, TX</td>
                <td>Mechanicsburg, PA</td>
                <td>$2.00</td>
                <td>2020-11-01</td>
                <td>2020-11-05</td>
                <td>1:00</td>
                <td>2020-11-05</td>
              </tr>
              <tr>
                <td>2001101</td>
                <td>3345639948</td>
                <td>James Transportation</td>
                <td>Laredo, TX</td>
                <td>Mechanicsburg, PA</td>
                <td>$2.00</td>
                <td>2020-11-01</td>
                <td>2020-11-05</td>
                <td>1:00</td>
                <td>2020-11-05</td>
              </tr>
              <tr>
                <td>2001101</td>
                <td>3345639948</td>
                <td>James Transportation</td>
                <td>Laredo, TX</td>
                <td>Mechanicsburg, PA</td>
                <td>$2.00</td>
                <td>2020-11-01</td>
                <td>2020-11-05</td>
                <td>1:00</td>
                <td>2020-11-05</td>
              </tr>
              <tr>
                <td>2001101</td>
                <td>3345639948</td>
                <td>James Transportation</td>
                <td>Laredo, TX</td>
                <td>Mechanicsburg, PA</td>
                <td>$2.00</td>
                <td>2020-11-01</td>
                <td>2020-11-05</td>
                <td>1:00</td>
                <td>2020-11-05</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>



    <!-- Metro 4 -->
    <script src="/plsuite/Resources/Metro/build/js/metro.min.js"></script>
</body>
</html>
